<?php
// Include configuration file and check user authentication
require_once 'config.php';
requireLogin();

// Initialize error and success message variables
$error = '';
$success = '';

// Handle artwork deletion
if (isset($_POST['delete']) && isset($_POST['artwork_id'])) {
    // Prepare and execute delete query
    $stmt = $pdo->prepare('DELETE FROM artworks WHERE id = ?');
    if ($stmt->execute([$_POST['artwork_id']])) {
        $success = "Artwork successfully deleted";
    }
}

// Handle artwork addition or update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete'])) {
    // Get form data with null coalescing operator
    $artwork_id = $_POST['artwork_id'] ?? null;
    $title = $_POST['title'] ?? '';
    $production_year = $_POST['production_year'] ?? '';
    $artist_name = $_POST['artist_name'] ?? '';
    $width = $_POST['width'] ?? null;
    $height = $_POST['height'] ?? null;
    $warehouse_id = $_POST['warehouse_id'] ?: null;

    // Validate required fields
    if (!empty($title) && !empty($production_year) && !empty($artist_name)) {
        if ($artwork_id) {
            // Update existing artwork
            $stmt = $pdo->prepare('UPDATE artworks SET title = ?, production_year = ?, artist_name = ?, width = ?, height = ?, warehouse_id = ? WHERE id = ?');
            if ($stmt->execute([$title, $production_year, $artist_name, $width, $height, $warehouse_id, $artwork_id])) {
                $success = "Artwork successfully updated";
            }
        } else {
            // Add new artwork
            $stmt = $pdo->prepare('INSERT INTO artworks (title, production_year, artist_name, width, height, warehouse_id) VALUES (?, ?, ?, ?, ?, ?)');
            if ($stmt->execute([$title, $production_year, $artist_name, $width, $height, $warehouse_id])) {
                $success = "Artwork successfully added";
            }
        }
    } else {
        $error = "Please fill in all required fields";
    }
}

// Get all warehouses for the dropdown menu
$warehouses = $pdo->query('SELECT id, name FROM warehouses ORDER BY name')->fetchAll();

// Get all artworks with their associated warehouse information
$artworks = $pdo->query('
    SELECT a.*, w.name as warehouse_name 
    FROM artworks a 
    LEFT JOIN warehouses w ON a.warehouse_id = w.id 
    ORDER BY a.title
')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artworks - <?php echo APP_NAME; ?></title>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#artworkModal">
                <i class="bi bi-plus-lg"></i> Add Artwork
            </button>
        </div>

        <h1 class="mb-4">Artwork Management</h1>

        <!-- Display error message if any -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <!-- Display success message if any -->
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Artworks table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Artist</th>
                        <th>Year</th>
                        <th>Dimensions</th>
                        <th>Warehouse</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artworks as $artwork): ?>
                        <tr>
                            <td data-label="Title"><?php echo htmlspecialchars($artwork['title']); ?></td>
                            <td data-label="Artist"><?php echo htmlspecialchars($artwork['artist_name']); ?></td>
                            <td data-label="Year"><?php echo htmlspecialchars($artwork['production_year']); ?></td>
                            <td data-label="Dimensions">
                                <?php if ($artwork['width'] && $artwork['height']): ?>
                                    <?php echo htmlspecialchars($artwork['width']); ?> x <?php echo htmlspecialchars($artwork['height']); ?> cm
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td data-label="Warehouse"><?php echo htmlspecialchars($artwork['warehouse_name'] ?? '-'); ?></td>
                            <td data-label="Actions">
                                <!-- Edit button -->
                                <button type="button" class="btn btn-sm btn-primary edit-artwork" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#artworkModal"
                                        data-id="<?php echo $artwork['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($artwork['title']); ?>"
                                        data-artist="<?php echo htmlspecialchars($artwork['artist_name']); ?>"
                                        data-year="<?php echo htmlspecialchars($artwork['production_year']); ?>"
                                        data-width="<?php echo htmlspecialchars($artwork['width'] ?? ''); ?>"
                                        data-height="<?php echo htmlspecialchars($artwork['height'] ?? ''); ?>"
                                        data-warehouse="<?php echo htmlspecialchars($artwork['warehouse_id'] ?? ''); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Delete form -->
                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this artwork?');">
                                    <input type="hidden" name="artwork_id" value="<?php echo $artwork['id']; ?>">
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

    <!-- Modal for adding/editing artwork -->
    <div class="modal fade" id="artworkModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Artwork</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="artwork_id" id="artwork_id">
                        <!-- Title field -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <!-- Artist field -->
                        <div class="mb-3">
                            <label for="artist_name" class="form-label">Artist *</label>
                            <input type="text" class="form-control" id="artist_name" name="artist_name" required>
                        </div>
                        <!-- Production year field -->
                        <div class="mb-3">
                            <label for="production_year" class="form-label">Production Year *</label>
                            <input type="number" class="form-control" id="production_year" name="production_year" required>
                        </div>
                        <!-- Dimensions fields -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="width" class="form-label">Width (cm)</label>
                                    <input type="number" step="0.01" class="form-control" id="width" name="width">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
                                    <input type="number" step="0.01" class="form-control" id="height" name="height">
                                </div>
                            </div>
                        </div>
                        <!-- Warehouse selection -->
                        <div class="mb-3">
                            <label for="warehouse_id" class="form-label">Warehouse</label>
                            <select class="form-select" id="warehouse_id" name="warehouse_id">
                                <option value="">Select a warehouse</option>
                                <?php foreach ($warehouses as $warehouse): ?>
                                    <option value="<?php echo $warehouse['id']; ?>">
                                        <?php echo htmlspecialchars($warehouse['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
        document.querySelectorAll('.edit-artwork').forEach(button => {
            button.addEventListener('click', function() {
                // Populate form fields with artwork data
                document.getElementById('artwork_id').value = this.dataset.id;
                document.getElementById('title').value = this.dataset.title;
                document.getElementById('artist_name').value = this.dataset.artist;
                document.getElementById('production_year').value = this.dataset.year;
                document.getElementById('width').value = this.dataset.width;
                document.getElementById('height').value = this.dataset.height;
                document.getElementById('warehouse_id').value = this.dataset.warehouse;
            });
        });

        // Reset form when adding new artwork
        document.querySelector('[data-bs-target="#artworkModal"]').addEventListener('click', function() {
            document.getElementById('artwork_id').value = '';
            document.getElementById('title').value = '';
            document.getElementById('artist_name').value = '';
            document.getElementById('production_year').value = '';
            document.getElementById('width').value = '';
            document.getElementById('height').value = '';
            document.getElementById('warehouse_id').value = '';
        });
    </script>
</body>
</html> 