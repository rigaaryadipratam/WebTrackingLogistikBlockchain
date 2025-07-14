<?php

require_once 'config.php';

function getDbConnection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $conn->query("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
        $conn->select_db(DB_NAME);
        
        $conn->query("
            CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                barcode VARCHAR(50) UNIQUE NOT NULL,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        $conn->query("
            CREATE TABLE IF NOT EXISTS location_updates (
                id INT AUTO_INCREMENT PRIMARY KEY,
                barcode VARCHAR(50) NOT NULL,
                location VARCHAR(255) NOT NULL,
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                blockchain_synced BOOLEAN DEFAULT FALSE,
                FOREIGN KEY (barcode) REFERENCES products(barcode)
            )
        ");
    }
    return $conn;
}

function saveProductToDatabase($barcode, $name) {
    $conn = getDbConnection();
    
    try {
        $stmt = $conn->prepare("INSERT INTO products (barcode, name) VALUES (?, ?)");
        $stmt->bind_param("ss", $barcode, $name);
        $result = $stmt->execute();
        
        if ($result) {
            $productId = $conn->insert_id; 
            $stmt->close();
            return $productId; 
        } else {
            $stmt->close();
            $_SESSION['error'] = "Failed to save product to database";
            return false;
        }
        
    } catch (mysqli_sql_exception $e) {
        
        if ($e->getCode() == 1062) {
            
            $_SESSION['error'] = "Product dengan barcode '$barcode' sudah ada!";
            return false;
        } else {
            
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            return false;
        }
    }
}


function checkBarcodeExists($barcode) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id FROM products WHERE barcode = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}


function saveLocationUpdate($barcode, $location, $blockchainSynced = false) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("INSERT INTO location_updates (barcode, location, blockchain_synced) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $barcode, $location, $blockchainSynced);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function getProductFromDatabase($barcode) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("
        SELECT p.*, 
            (SELECT COUNT(*) FROM location_updates WHERE barcode = p.barcode) AS updates_count 
        FROM products p 
        WHERE p.barcode = ?
    ");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    
    if ($product) {
        $stmt = $conn->prepare("
            SELECT location, timestamp 
            FROM location_updates 
            WHERE barcode = ? 
            ORDER BY timestamp DESC
        ");
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        $updates = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        $product['updates'] = $updates;
    }
    
    return $product;
}

function getAllProducts() {
    $conn = getDbConnection();
    $result = $conn->query("
        SELECT p.*, 
            (SELECT COUNT(*) FROM location_updates WHERE barcode = p.barcode) AS updates_count 
        FROM products p 
        ORDER BY created_at DESC
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
}


function getProductById($id) {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
    return $product;
}


function updateProduct($id, $barcode, $name) {
    $conn = getDbConnection();
    
    try {
        $stmt = $conn->prepare("UPDATE products SET barcode = ?, name = ? WHERE id = ?");
        $stmt->bind_param("ssi", $barcode, $name, $id);
        $result = $stmt->execute();
        $stmt->close();
        
        if ($result) {
            return true;
        } else {
            $_SESSION['error'] = "Failed to update product";
            return false;
        }
        
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1062) {
            $_SESSION['error'] = "Product dengan barcode '$barcode' sudah ada!";
            return false;
        } else {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            return false;
        }
    }
}


function deleteProduct($id) {
    $conn = getDbConnection();
    
    try {
      
        $stmt = $conn->prepare("SELECT barcode FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        
        if ($product) {
            
            $stmt = $conn->prepare("DELETE FROM location_updates WHERE barcode = ?");
            $stmt->bind_param("s", $product['barcode']);
            $stmt->execute();
            $stmt->close();
            
            
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        }
        
        return false;
        
    } catch (mysqli_sql_exception $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        return false;
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'add_product' && $_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $barcode = trim($_POST['barcode'] ?? '');
    $name = trim($_POST['name'] ?? '');
    
    
    if (empty($barcode) || empty($name)) {
        $_SESSION['error'] = 'Barcode and name are required';
        
        $_SESSION['old_barcode'] = $barcode;
        $_SESSION['old_name'] = $name;
        header('Location: index.php?action=add_product_form');
        exit;
    }
    
    if (!preg_match('/^[a-zA-Z0-9\-_]+$/', $barcode)) {
        $_SESSION['error'] = 'Barcode hanya boleh berisi huruf, angka, dash (-), dan underscore (_)';
        $_SESSION['old_barcode'] = $barcode;
        $_SESSION['old_name'] = $name;
        header('Location: index.php?action=add_product_form');
        exit;
    }
    
    if (strlen($barcode) > 50) {
        $_SESSION['error'] = 'Barcode maksimal 50 karakter';
        $_SESSION['old_barcode'] = $barcode;
        $_SESSION['old_name'] = $name;
        header('Location: index.php?action=add_product_form');
        exit;
    }
    
    if (strlen($name) > 255) {
        $_SESSION['error'] = 'Product name maksimal 255 karakter';
        $_SESSION['old_barcode'] = $barcode;
        $_SESSION['old_name'] = $name;
        header('Location: index.php?action=add_product_form');
        exit;
    }
    
    $productId = saveProductToDatabase($barcode, $name);
    
    if ($productId) {
    
        $_SESSION['success'] = 'Product berhasil ditambahkan!';
        $_SESSION['show_location_popup'] = true;
        $_SESSION['new_product_id'] = $productId;
        $_SESSION['new_product_name'] = $name;
        header('Location: index.php?action=products');
        exit;
    } else {
        $_SESSION['old_barcode'] = $barcode;
        $_SESSION['old_name'] = $name;
        header('Location: index.php?action=add_product_form');
        exit;
    }
}

?>


