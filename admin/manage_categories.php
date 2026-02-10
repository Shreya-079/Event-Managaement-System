<?php
session_start();
include('../config/db.php');

// Only allow admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Handle Add/Edit/Delete
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['category_name']);
    $conn->query("INSERT INTO categories (name) VALUES ('$name')");
    header("Location: manage_categories.php");
    exit;
}

if (isset($_POST['edit_category'])) {
    $id = intval($_POST['category_id']);
    $name = $conn->real_escape_string($_POST['category_name']);
    $conn->query("UPDATE categories SET name='$name' WHERE id=$id");
    header("Location: manage_categories.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM categories WHERE id=$id");
    header("Location: manage_categories.php");
    exit;
}

// Fetch all categories
$result = $conn->query("SELECT * FROM categories ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Categories</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/admin/categories.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">EventSphere</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-secondary me-2" href="dashboard.php">← Back to Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2>Manage Event Categories</h2>
    <div class="table-container">
    <!-- Add New Category -->
    <form method="POST" class="mb-4 row g-2 align-items-center">
        <div class="col-md-8">
            <input type="text" name="category_name" class="form-control" placeholder="New Category Name" required>
        </div>
        <div class="col-md-4">
            <button type="submit" name="add_category" class="btn btn-primary w-100">Add Category</button>
        </div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <!-- Edit inline -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="category_id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="category_name" value="<?php echo htmlspecialchars($row['name']); ?>" class="form-control d-inline w-auto" required>
                                <button type="submit" name="edit_category" class="btn btn-warning btn-sm">Update</button>
                            </form>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="3">No categories found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
