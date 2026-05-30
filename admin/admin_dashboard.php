<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}


function getCount($conn, $query, $columnName) {
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row[$columnName] ?? 0;
    }

    return 0;
}

// Dashboard counts
$product_count = getCount($conn, "SELECT COUNT(*) AS total_products FROM products", "total_products");

$active_count = getCount($conn, "SELECT COUNT(*) AS active_products FROM products WHERE status = 'active'", "active_products");

$feedback_count = getCount($conn, "SELECT COUNT(*) AS total_feedback FROM feedback", "total_feedback");

$order_count = getCount($conn, "SELECT COUNT(*) AS total_orders FROM orders", "total_orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-dashboard-container">
    <div class="admin-dashboard-header">
        <div>
            <p class="admin-small-title">Zafar's Cafe & Convenience Admin Panel</p>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"] ?? "Admin"); ?>.</h1>
            <p>Manage products, feedback, and customer activity from one place.</p>
        </div>

        <div class="admin-header-actions">
            <a href="../index.php" class="admin-outline-btn">Website Home</a>
            <a href="../logout.php" class="admin-danger-btn">Logout</a>
        </div>
    </div>

    <div class="admin-stats-grid">
        <div class="admin-stat-card">
            <span>Total Products</span>
            <h2><?php echo $product_count; ?></h2>
        </div>

        <div class="admin-stat-card">
            <span>Active Products</span>
            <h2><?php echo $active_count; ?></h2>
        </div>

        <div class="admin-stat-card">
            <span>Feedback Messages</span>
            <h2><?php echo $feedback_count; ?></h2>
        </div>

        <div class="admin-stat-card">
            <span>Total Orders</span>
            <h2><?php echo $order_count; ?></h2>
        </div>
    </div>

    <div class="admin-actions-grid">
        <a href="manage_products.php" class="admin-action-card">
            <h3>Manage Products</h3>
            <p>View, edit, delete, and review all product listings.</p>
        </a>

        <a href="add_product.php" class="admin-action-card">
            <h3>Add New Product</h3>
            <p>Create new store items with price, stock, image, and status.</p>
        </a>

        <a href="view_feedback.php" class="admin-action-card">
            <h3>View Feedback</h3>
            <p>Read customer messages and contact form submissions.</p>
        </a>

        <a href="../products.php" class="admin-action-card">
            <h3>View Storefront</h3>
            <p>Check how products appear to customers on the website.</p>
        </a>
    </div>
</div>

</body>
</html>