<?php
include("includes/db.php");
include("includes/header.php");

$selected_category = isset($_GET["category"]) ? $_GET["category"] : "All";
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$sort = isset($_GET["sort"]) ? $_GET["sort"] : "";

$limit = 12;
$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;

if ($page < 1) {
    $page = 1;
}

$selected_category_safe = mysqli_real_escape_string($conn, $selected_category);
$search_safe = mysqli_real_escape_string($conn, $search);

$base_query = "FROM products WHERE 1";

if ($selected_category != "All") {
    $base_query .= " AND category='$selected_category_safe'";
}

if (!empty($search)) {
    $base_query .= " AND (
        product_name LIKE '%$search_safe%' OR
        description LIKE '%$search_safe%' OR
        category LIKE '%$search_safe%'
    )";
}

$order_by = " ORDER BY category, product_name";

if ($sort == "name_asc") {
    $order_by = " ORDER BY product_name ASC";
} elseif ($sort == "name_desc") {
    $order_by = " ORDER BY product_name DESC";
} elseif ($sort == "price_asc") {
    $order_by = " ORDER BY price ASC";
} elseif ($sort == "price_desc") {
    $order_by = " ORDER BY price DESC";
}

$count_query = "SELECT COUNT(*) AS total " . $base_query;
$count_result = mysqli_query($conn, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_products = $count_row["total"];
$total_pages = ceil($total_products / $limit);

if ($total_pages < 1) {
    $total_pages = 1;
}

if ($page > $total_pages) {
    $page = $total_pages;
}

$offset = ($page - 1) * $limit;

$query = "SELECT * " . $base_query . $order_by . " LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);
?>

<div class="page-title">
    <h1>Our Products</h1>
    <p>Fresh bites, drinks, snacks, and everyday essentials.</p>
</div>

<form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
    <input type="hidden" name="category" value="All">
    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
    <button type="submit">Search</button>
</form>

<form method="GET" class="sort-form">
    <input type="hidden" name="category" value="<?php echo htmlspecialchars($selected_category); ?>">
    <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">

    <label for="sort">Sort by:</label>
    <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="" <?php if ($sort == "") echo "selected"; ?>>Default</option>
        <option value="name_asc" <?php if ($sort == "name_asc") echo "selected"; ?>>Name: A to Z</option>
        <option value="name_desc" <?php if ($sort == "name_desc") echo "selected"; ?>>Name: Z to A</option>
        <option value="price_asc" <?php if ($sort == "price_asc") echo "selected"; ?>>Price: Low to High</option>
        <option value="price_desc" <?php if ($sort == "price_desc") echo "selected"; ?>>Price: High to Low</option>
    </select>
</form>

<div class="category-filters">
    <a href="products.php?category=All&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'All' && empty($search)) ? 'active-filter' : ''; ?>">All</a>
    <a href="products.php?category=Coffee&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Coffee') ? 'active-filter' : ''; ?>">Coffee</a>
    <a href="products.php?category=Cold Drinks&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Cold Drinks') ? 'active-filter' : ''; ?>">Cold Drinks</a>
    <a href="products.php?category=Water&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Water') ? 'active-filter' : ''; ?>">Water</a>
    <a href="products.php?category=Breakfast&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Breakfast') ? 'active-filter' : ''; ?>">Breakfast</a>
    <a href="products.php?category=Grocery&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Grocery') ? 'active-filter' : ''; ?>">Grocery</a>
    <a href="products.php?category=Snacks&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Snacks') ? 'active-filter' : ''; ?>">Snacks</a>
    <a href="products.php?category=Food&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>" class="<?php echo ($selected_category == 'Food') ? 'active-filter' : ''; ?>">Food</a>
</div>

<div class="results-info">
    <?php
    if (!empty($search) && $selected_category != "All") {
        echo "Showing <strong>" . htmlspecialchars($selected_category) . "</strong> results for: <strong>" . htmlspecialchars($search) . "</strong>";
    } elseif (!empty($search)) {
        echo "Showing results for: <strong>" . htmlspecialchars($search) . "</strong>";
    } elseif ($selected_category != "All") {
        echo "Showing category: <strong>" . htmlspecialchars($selected_category) . "</strong>";
    } else {
        echo "Showing all products";
    }
    ?>
</div>

<div class="products-container">
    <?php if (mysqli_num_rows($result) > 0) { ?>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

            <div class="product-card">

                <?php if (!empty($row["image"])) { ?>
                    <img 
                        src="images/<?php echo htmlspecialchars($row["image"]); ?>" 
                        class="product-image"
                        alt="<?php echo htmlspecialchars($row["product_name"]); ?>"
                    >
                <?php } else { ?>
                    <img 
                        src="images/default-product.jpg" 
                        class="product-image"
                        alt="Default product image"
                    >
                <?php } ?>

                <div class="product-category">
                    <?php echo htmlspecialchars($row["category"]); ?>
                </div>

                <h3>
                    <?php echo htmlspecialchars($row["product_name"]); ?>
                </h3>

                <p class="product-description">
                    <?php echo htmlspecialchars($row["description"]); ?>
                </p>

                <p class="product-price">
                    $<?php echo number_format($row["price"], 2); ?>
                </p>

                <p class="product-stock">
                    In Stock: <?php echo (int)$row["stock_quantity"]; ?>
                </p>

            </div>

        <?php } ?>

    <?php } else { ?>

        <p class="no-products">No products found.</p>

    <?php } ?>
</div>

<?php if ($total_pages > 1) { ?>
    <div class="pagination">

        <?php if ($page > 1) { ?>
            <a href="products.php?category=<?php echo urlencode($selected_category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page - 1; ?>">Previous</a>
        <?php } ?>

        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="products.php?category=<?php echo urlencode($selected_category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $i; ?>"
               class="<?php echo ($i == $page) ? 'active-page' : ''; ?>">
               <?php echo $i; ?>
            </a>
        <?php } ?>

        <?php if ($page < $total_pages) { ?>
            <a href="products.php?category=<?php echo urlencode($selected_category); ?>&search=<?php echo urlencode($search); ?>&sort=<?php echo urlencode($sort); ?>&page=<?php echo $page + 1; ?>">Next</a>
        <?php } ?>

    </div>
<?php } ?>

<?php include("includes/footer.php"); ?>