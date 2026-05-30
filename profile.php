<?php
session_start();
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    header("Location: admin/admin_dashboard.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "SELECT user_id, full_name, email, phone, role, created_at 
          FROM users 
          WHERE user_id = $user_id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: logout.php");
    exit();
}

$order_count_query = "SELECT COUNT(*) AS total_orders FROM orders WHERE user_id = $user_id";
$order_count_result = mysqli_query($conn, $order_count_query);
$order_count_row = $order_count_result ? mysqli_fetch_assoc($order_count_result) : null;
$total_orders = $order_count_row["total_orders"] ?? 0;

include("includes/header.php");
?>

<section class="profile-page">
    <div class="profile-hero-card">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user["full_name"], 0, 1)); ?>
        </div>

        <div class="profile-hero-text">
            <p class="profile-tag">Customer Account</p>
            <h1>Welcome, <?php echo htmlspecialchars($user["full_name"]); ?>.</h1>
            <p>Manage your account information, view your order history, and continue shopping.</p>
        </div>

        <div class="profile-hero-actions">
            <a href="edit_profile.php" class="admin-primary-btn">Update Profile</a>
            <a href="products.php" class="admin-outline-btn">Shop Products</a>
        </div>
    </div>

    <div class="profile-dashboard-grid">
        <div class="profile-info-card">
            <h2>Account Details</h2>

            <div class="profile-info-row">
                <span>Name</span>
                <strong><?php echo htmlspecialchars($user["full_name"]); ?></strong>
            </div>

            <div class="profile-info-row">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($user["email"]); ?></strong>
            </div>

            <div class="profile-info-row">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($user["phone"]); ?></strong>
            </div>

            <div class="profile-info-row">
                <span>Account Type</span>
                <strong><?php echo htmlspecialchars(ucfirst($user["role"])); ?></strong>
            </div>

            <div class="profile-info-row">
                <span>Member Since</span>
                <strong><?php echo htmlspecialchars($user["created_at"]); ?></strong>
            </div>
        </div>

        <div class="profile-side-card">
            <h2>Account Summary</h2>

            <div class="profile-summary-box">
                <span>Total Orders</span>
                <strong><?php echo $total_orders; ?></strong>
            </div>

            <div class="profile-action-list">
                <a href="order_history.php">View Order History</a>
                <a href="edit_profile.php">Update Basic Info</a>
                <a href="cart.php">View Cart</a>
                <a href="products.php">Continue Shopping</a>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>