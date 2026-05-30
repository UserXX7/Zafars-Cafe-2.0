<?php
include("includes/header.php");
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

/* Featured Products */
$featured_query = "SELECT * FROM products 
                   WHERE status = 'active' AND is_featured = 1
                   ORDER BY product_id DESC 
                   LIMIT 4";

$featured_result = mysqli_query($conn, $featured_query);

if (!$featured_result) {
    die("Featured products query failed: " . mysqli_error($conn));
}

/* Fallback if no featured products are selected */
if (mysqli_num_rows($featured_result) == 0) {
    $featured_query = "SELECT * FROM products 
                       WHERE status = 'active'
                       ORDER BY product_id DESC 
                       LIMIT 4";

    $featured_result = mysqli_query($conn, $featured_query);

    if (!$featured_result) {
        die("Featured fallback query failed: " . mysqli_error($conn));
    }
}

/* On Sale Products */
$sale_query = "SELECT * FROM products 
               WHERE status = 'active' AND is_on_sale = 1
               ORDER BY product_id DESC 
               LIMIT 4";

$sale_result = mysqli_query($conn, $sale_query);

if (!$sale_result) {
    die("Sale products query failed: " . mysqli_error($conn));
}
?>

<section class="premium-hero-section">
    <div class="premium-hero-bg-circle circle-one"></div>
    <div class="premium-hero-bg-circle circle-two"></div>
    <div class="premium-hero-bg-circle circle-three"></div>

    <div class="premium-hero-grid">
        <div class="premium-hero-content">
            <span class="premium-location-pill">Elmhurst, Queens</span>

            <h1>Your Local Cafe, Grocery, and Everyday Stop.</h1>

            <p class="premium-hero-subtitle">
                Fresh coffee, quick bites, snacks, drinks, and daily essentials — all from your neighborhood cafe and convenience store in Elmhurst.
            </p>

            <div class="premium-hero-actions">
                <a href="products.php" class="premium-btn premium-btn-green">Browse Products</a>

                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a href="register.php" class="premium-btn premium-btn-red">Create Account</a>
                <?php else: ?>
                    <a href="profile.php" class="premium-btn premium-btn-red">My Account</a>
                <?php endif; ?>
            </div>

            <div class="premium-hero-stats">
                <div>
                    <strong>Fresh</strong>
                    <span>Daily picks</span>
                </div>

                <div>
                    <strong>Local</strong>
                    <span>Elmhurst based</span>
                </div>

                <div>
                    <strong>Quick</strong>
                    <span>Pickup & delivery</span>
                </div>
            </div>
        </div>

        <div class="premium-hero-visual">
            <div class="premium-image-card">
                <img src="images/storefront.jpg" alt="Zafar's Cafe & Convenience storefront">
            </div>

            <div class="floating-info-card card-top">
                <span>Popular</span>
                <strong>Coffee & Breakfast</strong>
            </div>

            <div class="floating-info-card card-bottom">
                <span>Shop</span>
                <strong>Snacks • Drinks • Grocery</strong>
            </div>
        </div>
    </div>
</section>

<section class="premium-featured-section">
    <div class="premium-section-header">
        <span>Featured Picks</span>
        <h2>Featured Products</h2>
        <p>Handpicked items currently highlighted by Zafar's Cafe & Convenience.</p>
    </div>

    <div class="premium-products-grid">
        <?php while ($product = mysqli_fetch_assoc($featured_result)) { ?>
            <div class="premium-product-card">
                <div class="premium-product-image">
                    <?php
                    $image_file = $product["image"] ?? "";
                    $image_path = "images/product_images/" . $image_file;

                    if (!empty($image_file) && file_exists($image_path)) {
                        echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($product["product_name"]) . '">';
                    } else {
                        echo '<div class="premium-no-image">No Image</div>';
                    }
                    ?>
                </div>

                <div class="premium-product-info">
                    <span class="premium-category-pill"><?php echo htmlspecialchars($product["category"]); ?></span>

                    <h3><?php echo htmlspecialchars($product["product_name"]); ?></h3>

                    <p><?php echo htmlspecialchars($product["description"]); ?></p>

                    <div class="premium-product-bottom">
                        <strong>$<?php echo number_format($product["price"], 2); ?></strong>
                        <a href="add_to_cart.php?id=<?php echo $product["product_id"]; ?>">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="premium-center-action">
        <a href="products.php" class="premium-btn premium-btn-green">View All Products</a>
    </div>
</section>

<?php if (mysqli_num_rows($sale_result) > 0) { ?>
<section class="premium-sale-section">
    <div class="premium-section-header">
        <span>Limited Deals</span>
        <h2>On Sale Products</h2>
        <p>Save on selected products currently marked on sale by the store.</p>
    </div>

    <div class="premium-products-grid">
        <?php while ($product = mysqli_fetch_assoc($sale_result)) { ?>

            <?php
            $original_price = floatval($product["price"]);
            $discount_type = $product["discount_type"] ?? "none";
            $discount_value = floatval($product["discount_value"] ?? 0);
            $sale_price = $original_price;

            if ($discount_type === "percentage") {
                $sale_price = $original_price - ($original_price * ($discount_value / 100));
            } elseif ($discount_type === "amount") {
                $sale_price = $original_price - $discount_value;
            }

            if ($sale_price < 0) {
                $sale_price = 0;
            }
            ?>

            <div class="premium-product-card sale-product-card">
                <div class="sale-ribbon">ON SALE</div>
                
                <div class="premium-product-image">
                    <?php
                    $image_file = $product["image"] ?? "";
                    $image_path = "images/product_images/" . $image_file;

                    if (!empty($image_file) && file_exists($image_path)) {
                        echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($product["product_name"]) . '">';
                    } else {
                        echo '<div class="premium-no-image">No Image</div>';
                    }
                    ?>
                </div>

                <div class="premium-product-info">
                    <span class="sale-badge">
                        <?php
                        if ($discount_type === "percentage") {
                            echo htmlspecialchars($discount_value) . "% OFF";
                        } elseif ($discount_type === "amount") {
                            echo "$" . number_format($discount_value, 2) . " OFF";
                        } else {
                            echo "SALE";
                        }
                        ?>
                    </span>

                    <h3><?php echo htmlspecialchars($product["product_name"]); ?></h3>

                    <p><?php echo htmlspecialchars($product["description"]); ?></p>

                    <div class="premium-product-bottom sale-price-row">
                        <div>
                            <span class="old-price">$<?php echo number_format($original_price, 2); ?></span>
                            <strong>$<?php echo number_format($sale_price, 2); ?></strong>
                        </div>

                        <a href="add_to_cart.php?id=<?php echo $product["product_id"]; ?>">Add to Cart</a>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>

    <div class="premium-center-action">
        <a href="products.php" class="premium-btn premium-btn-red">Shop All Deals</a>
    </div>
</section>
<?php } ?>

<section class="premium-benefits-section">
    <div class="premium-section-header">
        <span>Why Zafar’s</span>
        <h2>Built for Neighborhood Convenience</h2>
        <p>A simple, local shopping experience for fresh food, drinks, snacks, and daily essentials.</p>
    </div>

    <div class="premium-benefits-grid">
        <div class="premium-benefit-card">
            <div class="benefit-icon">☕</div>
            <h3>Fresh Cafe Items</h3>
            <p>Hot coffee, bagels, breakfast items, pizza slices, patties, and quick bites ready when you need them.</p>
        </div>

        <div class="premium-benefit-card">
            <div class="benefit-icon">🥤</div>
            <h3>Drinks & Snacks</h3>
            <p>Browse cold drinks, chips, candy, and grab-and-go favorites from one convenient place.</p>
        </div>

        <div class="premium-benefit-card">
            <div class="benefit-icon">🛒</div>
            <h3>Everyday Essentials</h3>
            <p>Pick up grocery basics and daily items without needing a long trip to a larger store.</p>
        </div>
    </div>
</section>

<section class="premium-category-section">
    <div class="premium-section-header">
        <span>Shop Faster</span>
        <h2>Browse by Category</h2>
        <p>Find exactly what you need with simple category shortcuts.</p>
    </div>

    <div class="premium-category-grid">
        <a href="products.php?category=Coffee">Coffee</a>
        <a href="products.php?category=Cold%20Drinks">Cold Drinks</a>
        <a href="products.php?category=Breakfast">Breakfast</a>
        <a href="products.php?category=Snacks">Snacks</a>
        <a href="products.php?category=Food">Food</a>
        <a href="products.php?category=Grocery">Grocery</a>
    </div>
</section>

<section class="premium-cta-section">
    <div class="premium-cta-card">
        <span>Ready to browse?</span>
        <h2>Fresh bites, drinks, and essentials right around the corner.</h2>
        <p>Explore products, create an account, and enjoy a smoother local shopping experience.</p>

        <div class="premium-hero-actions cta-actions">
            <a href="products.php" class="premium-btn premium-btn-green">View Products</a>

            <?php if (!isset($_SESSION["user_id"])): ?>
                <a href="login.php" class="premium-btn premium-btn-red">Login</a>
            <?php else: ?>
                <a href="profile.php" class="premium-btn premium-btn-red">My Account</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="homepage-info-section">
    <div class="homepage-info-grid">

        <div class="homepage-info-column">
            <h3>About Us</h3>
            <p>
                Zafar's Cafe & Convenience is a neighborhood cafe and grocery stop in Elmhurst, Queens.
                We offer fresh coffee, breakfast items, snacks, drinks, and everyday essentials for the local community.
            </p>
        </div>

        <div class="homepage-info-column">
            <h3>Shop</h3>
            <a href="products.php?category=Coffee">Coffee</a>
            <a href="products.php?category=Breakfast">Breakfast</a>
            <a href="products.php?category=Cold%20Drinks">Cold Drinks</a>
            <a href="products.php?category=Snacks">Snacks</a>
            <a href="products.php?category=Grocery">Grocery</a>
        </div>

        <div class="homepage-info-column">
            <h3>Customer Help</h3>
            <a href="products.php">Browse Products</a>
            <a href="cart.php">View Cart</a>
            <a href="profile.php">My Account</a>
            <a href="order_history.php">Order History</a>
        </div>

        <div class="homepage-info-column">
            <h3>Store Info</h3>
            <p><strong>Location:</strong> Elmhurst, Queens</p>
            <p><strong>Order Options:</strong> Pickup & Delivery</p>
            <p><strong>Payment:</strong> Pay at Store / Cash on Delivery</p>
        </div>

    </div>
</section>

<?php include("includes/footer.php"); ?>