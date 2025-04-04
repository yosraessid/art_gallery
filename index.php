<?php
// Include configuration file and check user authentication
require_once 'config.php';
requireLogin();

// Get statistics for the dashboard
$stmt = $pdo->query('SELECT COUNT(*) as artwork_count FROM artworks');  // Count total artworks
$artworkCount = $stmt->fetch()['artwork_count'];

$stmt = $pdo->query('SELECT COUNT(*) as warehouse_count FROM warehouses');  // Count total warehouses
$warehouseCount = $stmt->fetch()['warehouse_count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Include custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Include navigation bar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Main content container -->
    <div class="container mt-4">
        <h1 class="mb-4">Dashboard</h1>
        
        <!-- Statistics cards -->
        <div class="row">
            <!-- Total Artworks card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Artworks</h5>
                        <p class="card-text display-4"><?php echo $artworkCount; ?></p>
                        <a href="artworks.php" class="btn btn-light">Manage Artworks</a>
                    </div>
                </div>
            </div>
            
            <!-- Total Warehouses card -->
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Warehouses</h5>
                        <p class="card-text display-4"><?php echo $warehouseCount; ?></p>
                        <a href="warehouses.php" class="btn btn-light">Manage Warehouses</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome message and quick actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <p>Welcome to the Art Gallery Management System. Use the navigation above to:</p>
                        <ul>
                            <li>Add, modify or remove artworks</li>
                            <li>Manage warehouse locations</li>
                            <li>Assign artworks to warehouses</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 