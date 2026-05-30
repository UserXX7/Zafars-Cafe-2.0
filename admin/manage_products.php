<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$category_filter = isset($_GET["category"]) ? trim($_GET["category"]) : "";
$sale_filter = isset($_GET["sale"]) ? trim($_GET["sale"]) : "";
$featured_filter = isset($_GET["featured"]) ? trim($_GET["featured"]) : "";
$status_filter = isset($_GET["status"]) ? trim($_GET["status"]) : "";
$sort = isset($_GET["sort"]) ? trim($_GET["sort"]) : "newest";

$where_conditions = [];

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);

    $where_conditions[] = "(
        product_name LIKE '%$safe_search%' 
        OR category LIKE '%$safe_search%' 
        OR status LIKE '%$safe_search%'
        OR description LIKE '%$safe_search%'
    )";
}

if (!empty($category_filter)) {
    $safe_category = mysqli_real_escape_string($conn, $category_filter);
    $where_conditions[] = "category = '$safe_category'";
}

if ($sale_filter === "sale") {
    $where_conditions[] = "is_on_sale = 1";
} elseif ($sale_filter === "not_sale") {
    $where_conditions[] = "is_on_sale = 0";
}

if ($featured_filter === "featured") {
    $where_conditions[] = "is_featured = 1";
} elseif ($featured_filter === "not_featured") {
    $where_conditions[] = "is_featured = 0";
}

if ($status_filter === "active") {
    $where_conditions[] = "status = 'active'";
} elseif ($status_filter === "inactive") {
    $where_conditions[] = "status = 'inactive'";
}

$where = "";

if (!empty($where_conditions)) {
    $where = "WHERE " . implode(" AND ", $where_conditions);
}

$order_by = "ORDER BY product_id DESC";

if ($sort === "name_az") {
    $order_by = "ORDER BY product_name ASC";
} elseif ($sort === "name_za") {
    $order_by = "ORDER BY product_name DESC";
} elseif ($sort === "price_low") {
    $order_by = "ORDER BY price ASC";
} elseif ($sort === "price_high") {
    $order_by = "ORDER BY price DESC";
} elseif ($sort === "oldest") {
    $order_by = "ORDER BY product_id ASC";
}

$query = "SELECT * FROM products $where $order_by";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Product query failed: " . mysqli_error($conn));
}

$count_query = "SELECT COUNT(*) AS total FROM products $where";
$count_result = mysqli_query($conn, $count_query);
$total_products = 0;

if ($count_result) {
    $count_row = mysqli_fetch_assoc($count_result);
    $total_products = $count_row["total"] ?? 0;
}

$category_query = "SELECT DISTINCT category FROM products ORDER BY category ASC";
$category_result = mysqli_query($conn, $category_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-container">
    <h2>Manage Products</h2>

    <?php
    if (isset($_GET["deleted"]) && $_GET["deleted"] == "success") {
        echo '<div class="alert-box success-alert">';
        echo "<p>Product deleted successfully.</p>";
        echo '</div>';
    }

    if (isset($_GET["updated"]) && $_GET["updated"] == "success") {
        echo '<div class="alert-box success-alert">';
        echo "<p>Product updated successfully.</p>";
        echo '</div>';
    }

    if (isset($_GET["added"]) && $_GET["added"] == "success") {
        echo '<div class="alert-box success-alert">';
        echo "<p>Product added successfully.</p>";
        echo '</div>';
    }
    ?>

    <div class="admin-page-actions">
        <a href="admin_dashboard.php" class="admin-outline-btn">Back to Dashboard</a>
        <a href="add_product.php" class="admin-primary-btn">Add New Product</a>
        <a href="../logout.php" class="admin-danger-btn">Logout</a>
    </div>

    <div class="admin-search-card">
        <form method="GET" class="admin-filter-form">

            <div class="admin-search-row">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Search by product name, category, status, or description..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >

                <button type="submit">Apply</button>

                <a href="manage_products.php" class="admin-clear-search-btn">Reset</a>
            </div>

            <div class="admin-filter-grid">
                <div class="filter-group">
                    <label>Category</label>
                    <select name="category">
                        <option value="">All Categories</option>

                        <?php if ($category_result) { ?>
                            <?php while ($cat = mysqli_fetch_assoc($category_result)) { ?>
                                <option value="<?php echo htmlspecialchars($cat["category"]); ?>"
                                    <?php if ($category_filter === $cat["category"]) echo "selected"; ?>>
                                    <?php echo htmlspecialchars($cat["category"]); ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Sale Status</label>
                    <select name="sale">
                        <option value="">All Products</option>
                        <option value="sale" <?php if ($sale_filter === "sale") echo "selected"; ?>>On Sale Only</option>
                        <option value="not_sale" <?php if ($sale_filter === "not_sale") echo "selected"; ?>>Not On Sale</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Featured</label>
                    <select name="featured">
                        <option value="">All Products</option>
                        <option value="featured" <?php if ($featured_filter === "featured") echo "selected"; ?>>Featured Only</option>
                        <option value="not_featured" <?php if ($featured_filter === "not_featured") echo "selected"; ?>>Not Featured</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="active" <?php if ($status_filter === "active") echo "selected"; ?>>Active</option>
                        <option value="inactive" <?php if ($status_filter === "inactive") echo "selected"; ?>>Inactive</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label>Sort By</label>
                    <select name="sort">
                        <option value="newest" <?php if ($sort === "newest") echo "selected"; ?>>Newest First</option>
                        <option value="oldest" <?php if ($sort === "oldest") echo "selected"; ?>>Oldest First</option>
                        <option value="name_az" <?php if ($sort === "name_az") echo "selected"; ?>>Name A-Z</option>
                        <option value="name_za" <?php if ($sort === "name_za") echo "selected"; ?>>Name Z-A</option>
                        <option value="price_low" <?php if ($sort === "price_low") echo "selected"; ?>>Price Low to High</option>
                        <option value="price_high" <?php if ($sort === "price_high") echo "selected"; ?>>Price High to Low</option>
                    </select>
                </div>
            </div>
        </form>

        <p>
            <?php if (!empty($search) || !empty($category_filter) || !empty($sale_filter) || !empty($featured_filter) || !empty($status_filter)) { ?>
                Showing <?php echo $total_products; ?> filtered product(s)
            <?php } else { ?>
                Showing <?php echo $total_products; ?> total product(s)
            <?php } ?>
        </p>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <div class="table-scroll">
            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Featured</th>
                    <th>On Sale</th>
                    <th>Discount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                    <?php
                    $row_class = "";

                    if (($row["is_on_sale"] ?? 0) == 1 && ($row["is_featured"] ?? 0) == 1) {
                        $row_class = "featured-sale-row";
                    } elseif (($row["is_on_sale"] ?? 0) == 1) {
                        $row_class = "sale-row";
                    } elseif (($row["is_featured"] ?? 0) == 1) {
                        $row_class = "featured-row";
                    }
                    ?>

                    <tr class="<?php echo $row_class; ?>">
                        <td><?php echo htmlspecialchars($row["product_id"]); ?></td>

                        <td>
                            <?php
                            $image_file = $row["image"] ?? "";
                            $image_path = "../images/product_images/" . $image_file;

                            if (!empty($image_file) && file_exists($image_path)) {
                                echo '<img src="' . htmlspecialchars($image_path) . '" 
                                           alt="' . htmlspecialchars($row["product_name"]) . '" 
                                           class="admin-product-img">';
                            } else {
                                echo '<span class="no-image">No Image</span>';
                            }
                            ?>
                        </td>

                        <td><?php echo htmlspecialchars($row["product_name"]); ?></td>

                        <td><?php echo htmlspecialchars($row["category"]); ?></td>

                        <td>$<?php echo number_format($row["price"], 2); ?></td>

                        <td><?php echo htmlspecialchars($row["stock_quantity"]); ?></td>

                        <td>
                            <?php if (($row["is_featured"] ?? 0) == 1) { ?>
                                <span class="admin-status-pill featured-pill">Yes</span>
                            <?php } else { ?>
                                <span class="admin-status-pill neutral-pill">No</span>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (($row["is_on_sale"] ?? 0) == 1) { ?>
                                <span class="admin-status-pill sale-pill">Yes</span>
                            <?php } else { ?>
                                <span class="admin-status-pill neutral-pill">No</span>
                            <?php } ?>
                        </td>

                        <td>
                            <?php
                            if (($row["is_on_sale"] ?? 0) == 1) {
                                $discount_type = $row["discount_type"] ?? "none";
                                $discount_value = floatval($row["discount_value"] ?? 0);

                                if ($discount_type === "percentage") {
                                    echo '<span class="discount-pill">' . htmlspecialchars($discount_value) . '% Off</span>';
                                } elseif ($discount_type === "amount") {
                                    echo '<span class="discount-pill">$' . number_format($discount_value, 2) . ' Off</span>';
                                } else {
                                    echo '<span class="admin-status-pill neutral-pill">None</span>';
                                }
                            } else {
                                echo '<span class="admin-status-pill neutral-pill">-</span>';
                            }
                            ?>
                        </td>

                        <td>
                            <?php if (($row["status"] ?? "") === "active") { ?>
                                <span class="admin-status-pill active-pill">Active</span>
                            <?php } else { ?>
                                <span class="admin-status-pill inactive-pill">Inactive</span>
                            <?php } ?>
                        </td>

                        <td>
                            <div class="admin-table-actions">
                                <a href="edit_product.php?id=<?php echo $row["product_id"]; ?>" class="table-edit-btn">Edit</a>

                                <a href="delete_product.php?id=<?php echo $row["product_id"]; ?>" 
                                   class="table-delete-btn"
                                   onclick="return confirm('Are you sure you want to delete this product?');">
                                   Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>

            </table>
        </div>

    <?php } else { ?>

        <div class="empty-state-card">
            <h2>No products found.</h2>
            <p>Try changing your search, filter, or sort options.</p>
            <a href="manage_products.php" class="admin-primary-btn">View All Products</a>
        </div>

    <?php } ?>
</div>

</body>
</html>