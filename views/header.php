<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Logistics Blockchain Tracker'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 600;
        }
        .nav-link {
            font-weight: 500;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 24px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            padding: 16px 20px;
        }
        .card-body {
            padding: 20px;
        }
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 20px;
        }
        .btn-primary {
            background-color: #3a5a9b;
            border-color: #3a5a9b;
        }
        .btn-success {
            background-color: #2c974b;
            border-color: #2c974b;
        }
        .btn-primary:hover {
            background-color: #2d4a7d;
            border-color: #2d4a7d;
        }
        .btn-success:hover {
            background-color: #25803e;
            border-color: #25803e;
        }
        .blockchain-badge {
            background-color: #f8f9fa;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 16px;
        }
        .badge {
            font-weight: 500;
            padding: 6px 10px;
        }
        .list-group-item {
            border-left: none;
            border-right: none;
            padding: 16px 20px;
        }
        .list-group-item:last-child {
            border-bottom: none;
        }
        .main-content {
            padding: 40px 0;
        }
        .page-header {
            margin-bottom: 32px;
        }
        .page-header h2 {
            font-weight: 600;
            color: #333;
        }
        .alert {
            border-radius: 8px;
            padding: 16px 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-box-seam me-2"></i>Logistics Tracker (Blockchain Based)
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'home') ? 'active' : ''; ?>" href="index.php">
                            <i class="bi bi-house me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'add_product') ? 'active' : ''; ?>" href="index.php?action=add_product">
                            <i class="bi bi-plus-circle me-1"></i> Add Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'products') ? 'active' : ''; ?>" href="index.php?action=products">
                            <i class="bi bi-box me-1"></i> View and Update Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($action == 'track') ? 'active' : ''; ?>" href="index.php?action=track">
                            <i class="bi bi-geo-alt me-1"></i> Track Product
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container main-content">
        <!-- MetaMask Status Indicator -->
        <div id="metamaskStatus">
           
        </div>
        
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><?php echo $_SESSION['success']; ?></div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?php echo $_SESSION['error']; ?></div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        

<!-- Optional custom CSS -->
<style>
    .blockchain-badge {
        background-color: #f8f9fa;
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 16px;
    }
    
    .blockchain-verified {
        color: #198754;
    }
    
    .timeline-item {
        position: relative;
    }
    
    .timeline-line {
        position: absolute;
        top: 45px;
        left: 22px;
        bottom: -13px;
        width: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }
</style>


