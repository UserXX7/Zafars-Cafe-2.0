<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
?>

<div class="form-container">
    <h2>Welcome, <?php echo $_SESSION["full_name"]; ?>!</h2>
    <p style="text-align:center; margin-top:15px;">You have successfully logged in to Zafar's Cafe.</p>

    <div class="dashboard-actions">
        <a href="products.php">Browse Products</a>
        <a href="feedback.php">Leave Feedback</a>
        <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
            <a href="admin.php">Open Admin Panel</a>
        <?php endif; ?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
