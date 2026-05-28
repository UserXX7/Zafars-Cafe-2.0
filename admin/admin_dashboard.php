<?php
include("../includes/admin_auth.php");
include("../includes/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Zafar's Cafe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">
    <h2>Admin Dashboard</h2>

    <p>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>.</p>

    <div style="text-align:center; margin-top:20px;">
        <a href="manage_products.php">Manage Products</a><br><br>
        <a href="add_product.php">Add New Product</a><br><br>
        <a href="view_feedback.php">View Feedback</a><br><br>
        <a href="../index.php">Back to Website Home</a><br><br>
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>