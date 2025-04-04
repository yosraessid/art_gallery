<?php
// Include configuration file and check user authentication
require_once 'config.php';
requireLogin();

// Initialize error and success message variables
$error = '';
$success = '';

// Handle warehouse deletion
if (isset($_POST['delete']) && isset($_POST['warehouse_id'])) {
    // Check if warehouse contains artworks before deletion
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM artworks WHERE warehouse_id = ?');
    $stmt->execute([$_POST['warehouse_id']]);
    $count = $stmt->fetch()['count'];

    if ($count > 0) {
        $error = "Cannot delete warehouse because it contains artworks";
    } else {
        // Delete warehouse if it's empty
        $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = ?');
        if ($stmt->execute([$_POST['warehouse_id']])) {
            $success = "Warehouse successfully deleted";
        }
    }
}

// Handle warehouse addition or update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    // Get form data with null coalescing operator
    $warehouse_id = $_POST['warehouse_id'] ?? null;
    $name = $_POST['name'] ?? '';
    $address = $_POST['address'] ?? '';

    // Validate required fields
    if (!empty($name) && !empty($address)) {
        if ($warehouse_id) {
            // Update existing warehouse
            $stmt = $pdo->prepare('UPDATE warehouses SET name = ?, address = ? WHERE id = ?');
            if ($stmt->execute([$name, $address, $warehouse_id])) {
                $success = "Warehouse successfully updated";
            }
        } else {
            // Add new warehouse
            $stmt = $pdo->prepare('INSERT INTO warehouses (name, address) VALUES (?, ?)');
            if ($stmt->execute([$name, $address])) {
                $success = "Warehouse successfully added";
            }
        }
    } else {
        $error = "Please fill in all fields";
    }
}

// Get all warehouses with their artwork counts
$warehouses = $pdo->query('
    SELECT w.*, COUNT(a.id) as artwork_count 
    FROM warehouses w 
    LEFT JOIN artworks a ON w.id = a.warehouse_id 
    GROUP BY w.id 
    ORDER BY w.name
')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warehouses - <?php echo APP_NAME; ?></title>
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
        <!-- Page header with add button and back button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#warehouseModal">
                <i class="bi bi-plus-lg"></i> Add Warehouse
            </button>
        </div>

        <h1 class="mb-4">Warehouse Management</h1>

        <!-- Display error message if any -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <!-- Display success message if any -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Warehouses table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Number of Artworks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($warehouses as $warehouse): ?>
                        <tr>
                            <td data-label="Name"><?php echo htmlspecialchars($warehouse['name']); ?></td>
                            <td data-label="Address"><?php echo htmlspecialchars($warehouse['address']); ?></td>
                            <td data-label="Number of Artworks"><?php echo $warehouse['artwork_count']; ?></td>
                            <td data-label="Actions">
                                <!-- Edit button -->
                                <button type="button" class="btn btn-sm btn-primary edit-warehouse" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#warehouseModal"
                                        data-id="<?php echo $warehouse['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($warehouse['name']); ?>"
                                        data-address="<?php echo htmlspecialchars($warehouse['address']); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Delete form -->
                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this warehouse?');">
                                    <input type="hidden" name="warehouse_id" value="<?php echo $warehouse['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for adding/editing warehouse -->
    <div class="modal fade" id="warehouseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Warehouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="warehouse_id" id="warehouse_id">
                        <!-- Name field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <!-- Address field -->
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit button clicks
        document.querySelectorAll('.edit-warehouse').forEach(button => {
            button.addEventListener('click', function() {
                // Populate form fields with warehouse data
                document.getElementById('warehouse_id').value = this.dataset.id;
                document.getElementById('name').value = this.dataset.name;
                document.getElementById('address').value = this.dataset.address;
            });
        });

        // Reset form when adding new warehouse
        document.querySelector('[data-bs-target="#warehouseModal"]').addEventListener('click', function() {
            document.getElementById('warehouse_id').value = '';
            document.getElementById('name').value = '';
            document.getElementById('address').value = '';
        });
    </script>
</body>
</html> 