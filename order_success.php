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

if (!isset($_GET["order_id"])) {
    header("Location: products.php");
    exit();
}

$order_id = intval($_GET["order_id"]);
$user_id = $_SESSION["user_id"];

$order_query = "SELECT * FROM orders 
                WHERE order_id = $order_id 
                AND user_id = $user_id";

$order_result = mysqli_query($conn, $order_query);

if (!$order_result) {
    die("Order query failed: " . mysqli_error($conn));
}

$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    header("Location: order_history.php");
    exit();
}

$item_query = "SELECT * FROM order_items WHERE order_id = $order_id";
$item_result = mysqli_query($conn, $item_query);

if (!$item_result) {
    die("Order items query failed: " . mysqli_error($conn));
}

include("includes/header.php");
?>

<section class="order-success-page">
    <div class="order-success-hero">
        <div class="success-icon">✓</div>

        <p class="profile-tag">Order Confirmed</p>

        <h1>Order Placed Successfully!</h1>

        <p class="success-message">
            Thank you, <?php echo htmlspecialchars($order["customer_name"]); ?>. 
            Your order has been saved and is currently pending.
        </p>

        <div class="success-order-number">
            <span>Order Number</span>
            <strong>#<?php echo htmlspecialchars($order["order_id"]); ?></strong>
        </div>

        <div class="success-actions">
            <a href="order_history.php" class="admin-primary-btn">View Order History</a>
            <a href="products.php" class="admin-outline-btn">Continue Shopping</a>
        </div>
    </div>

    <div class="order-success-grid">
        <div class="order-success-card">
            <h2>Order Summary</h2>

            <div class="success-info-row">
                <span>Status</span>
                <strong class="status-pill"><?php echo htmlspecialchars($order["order_status"]); ?></strong>
            </div>

            <div class="success-info-row">
                <span>Total</span>
                <strong>$<?php echo number_format($order["order_total"], 2); ?></strong>
            </div>

            <div class="success-info-row">
                <span>Order Date</span>
                <strong><?php echo htmlspecialchars($order["order_date"]); ?></strong>
            </div>
        </div>

        <div class="order-success-card">
            <h2>Customer Details</h2>

            <div class="success-info-row">
                <span>Name</span>
                <strong><?php echo htmlspecialchars($order["customer_name"]); ?></strong>
            </div>

            <div class="success-info-row">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($order["customer_email"]); ?></strong>
            </div>

            <div class="success-info-row">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($order["customer_phone"]); ?></strong>
            </div>
        </div>
    </div>

    <div class="order-items-card">
        <h2>Items Ordered</h2>

        <div class="table-scroll">
            <table class="modern-cart-table">
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>

                <?php while ($item = mysqli_fetch_assoc($item_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item["product_name"]); ?></td>
                        <td>$<?php echo number_format($item["price"], 2); ?></td>
                        <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
                        <td><strong>$<?php echo number_format($item["subtotal"], 2); ?></strong></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>