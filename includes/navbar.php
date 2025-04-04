<!-- Navigation bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.svg" alt="<?php echo APP_NAME; ?>" height="40" class="me-2">
            <?php echo APP_NAME; ?>
        </a>
        <div class="d-flex align-items-center w-100 justify-content-between">
            <!-- Quick navigation links -->
            <div class="mx-auto d-flex align-items-center">
                <a href="index.php" class="text-white text-decoration-none px-4 nav-link" onclick="this.style.color='#F9C784'">Dashboard</a>
                <a href="artworks.php" class="text-white text-decoration-none px-4 nav-link" onclick="this.style.color='#F9C784'">Artworks</a>
                <a href="warehouses.php" class="text-white text-decoration-none px-4 nav-link" onclick="this.style.color='#F9C784'">Warehouses</a>
            </div>
            <!-- Logout link -->
            <a class="text-white text-decoration-none" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav> 