<?php
include("includes/header.php");
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$category = isset($_GET["category"]) ? trim($_GET["category"]) : "";
$sort = isset($_GET["sort"]) ? trim($_GET["sort"]) : "default";

$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$limit = 12;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$where = "WHERE status = 'active'";

if (!empty($search)) {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $where .= " AND (product_name LIKE '%$safe_search%' OR category LIKE '%$safe_search%' OR description LIKE '%$safe_search%')";
}

if (!empty($category) && $category !== "All") {
    $safe_category = mysqli_real_escape_string($conn, $category);
    $where .= " AND category = '$safe_category'";
}

$order_by = "ORDER BY 
    CASE 
        WHEN display_order = 0 THEN 9999 
        ELSE display_order 
    END ASC,
    product_name ASC";

if ($sort === "price_low") {
    $order_by = "ORDER BY price ASC";
} elseif ($sort === "price_high") {
    $order_by = "ORDER BY price DESC";
} elseif ($sort === "name_az") {
    $order_by = "ORDER BY product_name ASC";
} elseif ($sort === "name_za") {
    $order_by = "ORDER BY product_name DESC";
}

$count_query = "SELECT COUNT(*) AS total FROM products $where";
$count_result = mysqli_query($conn, $count_query);

if (!$count_result) {
    die("Count query failed: " . mysqli_error($conn));
}

$count_row = mysqli_fetch_assoc($count_result);
$total_products = $count_row["total"] ?? 0;
$total_pages = ceil($total_products / $limit);

$query = "SELECT * FROM products $where $order_by LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Products query failed: " . mysqli_error($conn));
}

$categories_query = "SELECT DISTINCT category FROM products WHERE status = 'active' ORDER BY category ASC";
$categories_result = mysqli_query($conn, $categories_query);

/*helper function to calculate final price based on sale status and discount*/

function calculateFinalPrice($price, $is_on_sale, $discount_type, $discount_value) {
    $price = floatval($price);
    $discount_value = floatval($discount_value);
    $discount_type = strtolower(trim($discount_type ?? ""));

    if (intval($is_on_sale) !== 1 || $discount_value <= 0) {
        return round($price, 2);
    }

    if ($discount_type === "percentage") {
        return round(max(0, $price - ($price * ($discount_value / 100))), 2);
    }

    if ($discount_type === "fixed") {
        return round(max(0, $price - $discount_value), 2);
    }

    return round($price, 2);
}
?>

<section class="modern-products-page">

    <div class="products-hero-card">
        <div>
            <p class="profile-tag">Shop Local</p>
            <h1>Our Products</h1>
            <p>Fresh bites, drinks, snacks, and everyday essentials from Zafar's Cafe & Convenience.</p>
        </div>

        <div class="products-hero-actions">
            <a href="cart.php" class="admin-primary-btn">View Cart</a>
            <a href="profile.php" class="admin-outline-btn">My Account</a>
        </div>
    </div>

    <div class="products-control-card">
        <form method="GET" class="modern-products-search">
            <input type="text" name="search" placeholder="Search coffee, snacks, breakfast..."
                   value="<?php echo htmlspecialchars($search); ?>">

            <?php if (!empty($category)) { ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php } ?>

            <?php if (!empty($sort) && $sort !== "default") { ?>
                <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
            <?php } ?>

            <button type="submit">Search</button>
        </form>

        <form method="GET" class="modern-sort-form">
            <?php if (!empty($search)) { ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <?php } ?>

            <?php if (!empty($category)) { ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
            <?php } ?>

            <label>Sort by</label>
            <select name="sort" onchange="this.form.submit()">
                <option value="default" <?php if ($sort === "default") echo "selected"; ?>>Default</option>
                <option value="price_low" <?php if ($sort === "price_low") echo "selected"; ?>>Price: Low to High</option>
                <option value="price_high" <?php if ($sort === "price_high") echo "selected"; ?>>Price: High to Low</option>
                <option value="name_az" <?php if ($sort === "name_az") echo "selected"; ?>>Name: A to Z</option>
                <option value="name_za" <?php if ($sort === "name_za") echo "selected"; ?>>Name: Z to A</option>
            </select>
        </form>
    </div>

    <div class="modern-category-row">
        <a href="products.php" class="<?php echo empty($category) ? 'active-category-pill' : ''; ?>">All</a>

        <?php if ($categories_result) { ?>
            <?php while ($cat = mysqli_fetch_assoc($categories_result)) { ?>
                <a href="products.php?category=<?php echo urlencode($cat["category"]); ?>"
                   class="<?php echo ($category === $cat["category"]) ? 'active-category-pill' : ''; ?>">
                    <?php echo htmlspecialchars($cat["category"]); ?>
                </a>
            <?php } ?>
        <?php } ?>
    </div>

    <div class="products-result-bar">
        <p>
            <?php if (!empty($search)) { ?>
                Showing results for <strong><?php echo htmlspecialchars($search); ?></strong>
            <?php } elseif (!empty($category)) { ?>
                Showing <strong><?php echo htmlspecialchars($category); ?></strong> products
            <?php } else { ?>
                Showing all products
            <?php } ?>
        </p>

        <span><?php echo $total_products; ?> items found</span>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <div class="modern-products-grid">
            <?php while ($product = mysqli_fetch_assoc($result)) { ?>
            <?php
                $original_price = floatval($product["price"]);

                $final_price = round(calculateFinalPrice(
                    $product["price"],
                    $product["is_on_sale"],
                    $product["discount_type"],
                    $product["discount_value"]
                ), 2);

                $is_sale = intval($product["is_on_sale"]) === 1 && floatval($product["discount_value"]) > 0;
                ?>
                <div class="modern-product-card">

                    <div class="modern-product-image">
                        <?php
                        $image_file = $product["image"] ?? "";
                        $image_path = "images/product_images/" . $image_file;

                        if (!empty($image_file) && file_exists($image_path)) {
                            echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($product["product_name"]) . '">';
                        } else {
                            echo '<div class="product-image-placeholder">No Image</div>';
                        }
                        ?>
                    </div>

                    <div class="modern-product-info">
                    <?php if ($is_sale) { ?>
                        <span class="product-sale-badge">
                            <?php
                                if (strtolower(trim($product["discount_type"])) === "percentage") {
                                    echo intval($product["discount_value"]) . "% OFF";
                                } else {
                                    echo "$" . number_format($product["discount_value"], 2) . " OFF";
                                }
                            ?>
                        </span>
                    <?php } ?>
                        <span class="modern-category-pill">
                            <?php echo htmlspecialchars($product["category"]); ?>
                        </span>

                        <h3><?php echo htmlspecialchars($product["product_name"]); ?></h3>

                        <p>
                            <?php
                            $description = $product["description"] ?? "";
                            echo htmlspecialchars(strlen($description) > 90 ? substr($description, 0, 90) . "..." : $description);
                            ?>
                        </p>

                        <div class="modern-product-meta">
                            <div class="product-price-box">
                                <?php if ($is_sale) { ?>
                                    <span class="original-price">$<?php echo number_format($original_price, 2); ?></span>
                                    <strong class="sale-price">$<?php echo number_format($final_price, 2); ?></strong>
                                <?php } else { ?>
                                    <strong>$<?php echo number_format($original_price, 2); ?></strong>
                                <?php } ?>
                            </div>

                            <span>In Stock: <?php echo htmlspecialchars($product["stock_quantity"]); ?></span>
                        </div>

                        <form method="POST" action="add_to_cart.php?id=<?php echo $product["product_id"]; ?>" class="product-add-cart-form">
                            <div class="product-qty-control">
                                <button type="button" onclick="changeProductQty(this, -1)">−</button>
                                <input type="number" name="quantity" value="1" min="1">
                                <button type="button" onclick="changeProductQty(this, 1)">+</button>
                            </div>

                            <button type="submit" class="modern-add-cart-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if ($total_pages > 1) { ?>
            <div class="modern-pagination">
                <?php
                $query_params = [];

                if (!empty($search)) {
                    $query_params["search"] = $search;
                }

                if (!empty($category)) {
                    $query_params["category"] = $category;
                }

                if (!empty($sort)) {
                    $query_params["sort"] = $sort;
                }
                ?>

                <?php if ($page > 1) { 
                    $query_params["page"] = $page - 1;
                ?>
                    <a href="products.php?<?php echo http_build_query($query_params); ?>">Previous</a>
                <?php } ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) { 
                    $query_params["page"] = $i;
                ?>
                    <a href="products.php?<?php echo http_build_query($query_params); ?>"
                       class="<?php echo $i === $page ? 'active-page' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php } ?>

                <?php if ($page < $total_pages) { 
                    $query_params["page"] = $page + 1;
                ?>
                    <a href="products.php?<?php echo http_build_query($query_params); ?>">Next</a>
                <?php } ?>
            </div>
        <?php } ?>

    <?php } else { ?>

        <div class="empty-state-card">
            <h2>No products found.</h2>
            <p>Try a different search or category.</p>
            <a href="products.php" class="admin-primary-btn">View All Products</a>
        </div>

    <?php } ?>

</section>

<script>
function changeProductQty(button, amount) {
    const input = button.parentElement.querySelector("input[type='number']");
    let currentValue = parseInt(input.value);

    if (isNaN(currentValue)) {
        currentValue = 1;
    }

    currentValue += amount;

    if (currentValue < 1) {
        currentValue = 1;
    }

    input.value = currentValue;
}
</script>

<?php include("includes/footer.php"); ?>