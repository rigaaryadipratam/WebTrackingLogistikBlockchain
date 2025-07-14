<?php
session_start();
require_once 'config.php';
require_once 'database.php';
require_once 'functions.php';


$action = isset($_GET['action']) ? $_GET['action'] : 'home';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {
        case 'add_product':
            $barcode = $_POST['barcode'] ?? '';
            $name = $_POST['name'] ?? '';
            $name = $_POST['name'] ?? '';

            if (empty($barcode) || empty($name)) {
                $_SESSION['error'] = 'Barcode and name are required';
            } else {
                
                if (saveProductToDatabase($barcode, $name)) {
                    $_SESSION['success'] = 'Product added successfully!';
                    $_SESSION['show_location_popup'] = true;
                    $_SESSION['new_product_id'] = $lastInsertedProductId; // ID produk baru
                    $_SESSION['new_product_name'] = $name;
                    $_SESSION['barcode'] = $barcode;

                } else {
                    $_SESSION['error'] = 'Failed to add product';
                }
            }
            header('Location: index.php?action=products');
            exit;

        case 'update_location':
            $barcode = $_POST['barcode'] ?? '';
            $location = $_POST['location'] ?? '';

            if (empty($barcode) || empty($location)) {
                $_SESSION['error'] = 'Barcode and location are required';
            } else {
                
                if (saveLocationUpdate($barcode, $location)) {
                    $_SESSION['success'] = 'Location updated successfully. Use the blockchain integration in the frontend to sync.';
                } else {
                    $_SESSION['error'] = 'Failed to update location';
                }
            }
            header('Location: index.php?action=track&barcode=' . urlencode($barcode));
            exit;
    }
}


$pageTitle = 'Logistics Blockchain Tracker';
include 'views/header.php';


if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

switch ($action) {
    case 'home':
        include 'views/home.php';
        break;

    case 'add_product':
        include 'views/add_product.php';
        break;

    case 'track':
        $barcode = $_GET['barcode'] ?? '';
        $productInfo = [];
        if ($barcode) {
            $productInfo = getProductFromDatabase($barcode);
        }
        include 'views/track_product.php';
        break;

    case 'update':
        $barcode = $_GET['barcode'] ?? '';
        $productInfo = [];
        if ($barcode) {
            $productInfo = getProductFromDatabase($barcode);
        }
        include 'views/update_product.php';
        break;

    case 'products':
        $products = getAllProducts();
        include 'views/product_list.php';
        break;

    default:
        include 'views/home.php';
}


include 'views/footer.php';
