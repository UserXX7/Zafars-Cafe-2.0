<?php
include("../includes/header.php");
require_once("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$admin_name = $_SESSION["full_name"] ?? "Admin";

/* Helper function for single count queries */
function getCount($conn, $query) {
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row["total"] ?? 0;
    }

    return 0;
}

/* Dashboard counts */
$total_products = getCount($conn, "SELECT COUNT(*) AS total FROM products");
$active_products = getCount($conn, "SELECT COUNT(*) AS total FROM products WHERE status = 'active'");
$inactive_products = getCount($conn, "SELECT COUNT(*) AS total FROM products WHERE status != 'active'");
$featured_products = getCount($conn, "SELECT COUNT(*) AS total FROM products WHERE is_featured = 1");
$sale_products = getCount($conn, "SELECT COUNT(*) AS total FROM products WHERE is_on_sale = 1");
$total_orders = getCount($conn, "SELECT COUNT(*) AS total FROM orders");
$total_feedback = getCount($conn, "SELECT COUNT(*) AS total FROM feedback");
$new_feedback = getCount($conn, "SELECT COUNT(*) AS total FROM feedback WHERE status = 'new'");

/* Recent products */
$recent_products_query = "
    SELECT product_id, product_name, category, price, stock_quantity, status
    FROM products
    ORDER BY product_id DESC
    LIMIT 5
";
$recent_products = mysqli_query($conn, $recent_products_query);

/* Recent feedback */
$recent_feedback_query = "
    SELECT feedback_id, full_name, feedback_type, subject, status, submitted_at
    FROM feedback
    ORDER BY submitted_at DESC
    LIMIT 5
";
$recent_feedback = mysqli_query($conn, $recent_feedback_query);

/* Recent orders */
$recent_orders_query = "
    SELECT order_id, user_id, customer_name, order_total, order_status, order_date
    FROM orders
    ORDER BY order_id DESC
    LIMIT 5
";
$recent_orders = mysqli_query($conn, $recent_orders_query);
?>

<main class="admin-dashboard-container">

    <section class="admin-hero-panel">
        <div>
            <span class="admin-global-badge">ADMIN CONTROL PANEL</span>
            <h1>Welcome, <?php echo htmlspecialchars($admin_name); ?>.</h1>
            <p>
                Manage products, orders, customer feedback, storefront activity, and store operations from one place.
            </p>
        </div>

        <div class="admin-hero-actions">
            <a href="../index.php" class="admin-outline-btn">Website Home</a>
            <a href="../products.php" class="admin-outline-btn">View Storefront</a>
            <a href="../logout.php" class="admin-danger-btn">Logout</a>
        </div>
    </section>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <span>Total Products</span>
            <h2><?php echo $total_products; ?></h2>
            <p>All products in database</p>
        </div>

        <div class="admin-stat-card success">
            <span>Active Products</span>
            <h2><?php echo $active_products; ?></h2>
            <p>Visible to customers</p>
        </div>

        <div class="admin-stat-card warning">
            <span>Featured Products</span>
            <h2><?php echo $featured_products; ?></h2>
            <p>Highlighted on homepage</p>
        </div>

        <div class="admin-stat-card danger">
            <span>On Sale Products</span>
            <h2><?php echo $sale_products; ?></h2>
            <p>Discounted items</p>
        </div>

        <div class="admin-stat-card">
            <span>Total Orders</span>
            <h2><?php echo $total_orders; ?></h2>
            <p>Customer checkout records</p>
        </div>

        <div class="admin-stat-card">
            <span>Feedback Messages</span>
            <h2><?php echo $total_feedback; ?></h2>
            <p><?php echo $new_feedback; ?> new message(s)</p>
        </div>

        <div class="admin-stat-card muted">
            <span>Inactive Products</span>
            <h2><?php echo $inactive_products; ?></h2>
            <p>Hidden or disabled items</p>
        </div>
    </section>

    <section class="admin-actions-grid">
        <a href="manage_products.php" class="admin-action-card">
            <div class="admin-action-icon">📦</div>
            <h3>Manage Products</h3>
            <p>Search, filter, edit, delete, feature, and update product listings.</p>
        </a>

        <a href="add_product.php" class="admin-action-card">
            <div class="admin-action-icon">➕</div>
            <h3>Add New Product</h3>
            <p>Create new store items with category, stock, image, and price.</p>
        </a>

        <a href="view_feedback.php" class="admin-action-card">
            <div class="admin-action-icon">💬</div>
            <h3>View Feedback</h3>
            <p>Review customer messages, product suggestions, and website issues.</p>
        </a>

        <a href="../products.php" class="admin-action-card">
            <div class="admin-action-icon">🛒</div>
            <h3>View Storefront</h3>
            <p>Check how products appear to customers on the website.</p>
        </a>

        <a href="../order_history.php" class="admin-action-card">
            <div class="admin-action-icon">🧾</div>
            <h3>Order Records</h3>
            <p>Review customer orders and order history records.</p>
        </a>

        <a href="../feedback.php" class="admin-action-card">
            <div class="admin-action-icon">⭐</div>
            <h3>Test Feedback Form</h3>
            <p>Open the customer-facing feedback form and test submissions.</p>
        </a>
    </section>

    <section class="admin-dashboard-panels">

        <div class="admin-panel-card">
            <div class="admin-panel-header">
                <h3>Recent Products</h3>
                <a href="manage_products.php">View All</a>
            </div>

            <div class="admin-mini-table-wrap">
                <table class="admin-mini-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($recent_products && mysqli_num_rows($recent_products) > 0): ?>
                            <?php while ($product = mysqli_fetch_assoc($recent_products)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product["product_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($product["category"]); ?></td>
                                    <td>$<?php echo number_format($product["price"], 2); ?></td>
                                    <td><?php echo intval($product["stock_quantity"]); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No recent products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-panel-card">
            <div class="admin-panel-header">
                <h3>Recent Feedback</h3>
                <a href="view_feedback.php">View All</a>
            </div>

            <div class="admin-mini-list">
                <?php if ($recent_feedback && mysqli_num_rows($recent_feedback) > 0): ?>
                    <?php while ($feedback = mysqli_fetch_assoc($recent_feedback)): ?>
                        <div class="admin-mini-list-item">
                            <div>
                                <strong><?php echo htmlspecialchars($feedback["subject"]); ?></strong>
                                <p>
                                    <?php echo htmlspecialchars($feedback["full_name"]); ?> · 
                                    <?php echo htmlspecialchars(ucfirst($feedback["feedback_type"])); ?>
                                </p>
                            </div>

                            <span class="admin-status-pill <?php echo htmlspecialchars($feedback["status"]); ?>">
                                <?php echo htmlspecialchars(ucfirst($feedback["status"])); ?>
                            </span>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="admin-empty-text">No feedback submitted yet.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="admin-panel-card">
            <div class="admin-panel-header">
                <h3>Recent Orders</h3>
                <span>Latest 5</span>
            </div>

            <div class="admin-mini-table-wrap">
                <table class="admin-mini-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if ($recent_orders && mysqli_num_rows($recent_orders) > 0): ?>
                            <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                <tr>
                                    <td>#<?php echo intval($order["order_id"]); ?></td>

                                    <td>
                                        <?php echo htmlspecialchars($order["customer_name"] ?? "Customer"); ?>
                                    </td>

                                    <td>
                                        $<?php echo number_format($order["order_total"] ?? 0, 2); ?>
                                    </td>

                                    <td>
                                        <?php echo htmlspecialchars(ucfirst($order["order_status"] ?? "Pending")); ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No recent orders found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </section>

</main>

<?php include("../includes/footer.php"); ?>