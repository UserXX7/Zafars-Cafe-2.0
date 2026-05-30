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

$order_query = "SELECT * FROM orders 
                WHERE user_id = $user_id 
                ORDER BY order_date DESC";

$order_result = mysqli_query($conn, $order_query);

if (!$order_result) {
    die("Order history query failed: " . mysqli_error($conn));
}

include("includes/header.php");
?>

<section class="modern-order-history-page">
    <div class="order-history-header-card">
        <div>
            <p class="profile-tag">Account Orders</p>
            <h1>My Order History</h1>
            <p>View your past orders, order status, pickup or delivery details, and item summaries.</p>
        </div>

        <div class="profile-hero-actions">
            <a href="profile.php" class="admin-outline-btn">Back to Profile</a>
            <a href="products.php" class="admin-primary-btn">Continue Shopping</a>
        </div>
    </div>

    <?php if (mysqli_num_rows($order_result) > 0) { ?>

        <div class="modern-orders-list">

            <?php while ($order = mysqli_fetch_assoc($order_result)) { 
                $order_id = $order["order_id"];

                $items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
                $items_result = mysqli_query($conn, $items_query);

                $order_type = $order["order_type"] ?? "Pickup";
                $delivery_fee = $order["delivery_fee"] ?? 0;
            ?>

                <div class="modern-order-card">
                    <div class="modern-order-top">
                        <div>
                            <span class="order-number-pill">Order #<?php echo htmlspecialchars($order["order_id"]); ?></span>
                            <h2><?php echo htmlspecialchars($order_type); ?> Order</h2>
                            <p><?php echo htmlspecialchars($order["order_date"]); ?></p>
                        </div>

                        <div class="order-status-box">
                            <span>Status</span>
                            <strong><?php echo htmlspecialchars($order["order_status"]); ?></strong>
                        </div>
                    </div>

                    <div class="order-info-grid">
                        <div class="order-info-box">
                            <span>Order Total</span>
                            <strong>$<?php echo number_format($order["order_total"], 2); ?></strong>
                        </div>

                        <div class="order-info-box">
                            <span>Payment Method</span>
                            <strong><?php echo htmlspecialchars($order["payment_method"] ?? "Pay at Store"); ?></strong>
                        </div>

                        <div class="order-info-box">
                            <span>Requested Time</span>
                            <strong>
                                <?php 
                                echo !empty($order["requested_time"]) 
                                    ? htmlspecialchars($order["requested_time"]) 
                                    : "Not selected"; 
                                ?>
                            </strong>
                        </div>

                        <div class="order-info-box">
                            <span>Delivery Fee</span>
                            <strong>$<?php echo number_format($delivery_fee, 2); ?></strong>
                        </div>
                    </div>

                    <?php if ($order_type === "Delivery") { ?>
                        <div class="delivery-details-box">
                            <h3>Delivery Details</h3>

                            <p>
                                <strong>Address:</strong>
                                <?php echo htmlspecialchars($order["delivery_address"] ?? ""); ?>,
                                <?php echo htmlspecialchars($order["delivery_city"] ?? ""); ?>
                                <?php echo htmlspecialchars($order["delivery_zip"] ?? ""); ?>
                            </p>

                            <?php if (!empty($order["delivery_instructions"])) { ?>
                                <p>
                                    <strong>Instructions:</strong>
                                    <?php echo htmlspecialchars($order["delivery_instructions"]); ?>
                                </p>
                            <?php } ?>
                        </div>
                    <?php } ?>

                    <div class="order-items-section">
                        <h3>Items Ordered</h3>

                        <?php if ($items_result && mysqli_num_rows($items_result) > 0) { ?>
                            <div class="table-scroll">
                                <table class="modern-order-items-table">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Subtotal</th>
                                    </tr>

                                    <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item["product_name"]); ?></td>
                                            <td>$<?php echo number_format($item["price"], 2); ?></td>
                                            <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
                                            <td><strong>$<?php echo number_format($item["subtotal"], 2); ?></strong></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        <?php } else { ?>
                            <p>No items found for this order.</p>
                        <?php } ?>
                    </div>
                </div>

            <?php } ?>

        </div>

    <?php } else { ?>

        <div class="empty-state-card">
            <h2>No orders found.</h2>
            <p>Your order history will appear here after you place your first order.</p>
            <a href="products.php" class="admin-primary-btn">Browse Products</a>
        </div>

    <?php } ?>
</section>

<?php include("includes/footer.php"); ?>