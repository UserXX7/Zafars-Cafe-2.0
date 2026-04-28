<?php include("includes/header.php"); ?>

<section class="hero-section">
    <div class="hero-background-shape shape-one"></div>
    <div class="hero-background-shape shape-two"></div>

    <div class="hero-content modern-hero">
        <div class="hero-text">
            <p class="hero-tag">Elmhurst, Queens</p>
            <h1>Your Neighborhood Cafe & Grocery in Elmhurst</h1>
            <p class="hero-subtext">
                Stop by for fresh bites, drinks, snacks, and everyday essentials.
                Convenient, local, and always welcoming.
            </p>

            <div class="hero-buttons">
                <a href="products.php" class="btn btn-green">Browse Products</a>
                <?php if (!isset($_SESSION["user_id"])): ?>
                    <a href="register.php" class="btn btn-red">Create Account</a>
                <?php else: ?>
                    <a href="homepage.php" class="btn btn-red">My Account</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-image-card">
            <img src="images/hero-cafe.jpg" alt="Zafar's Cafe products">
        </div>
    </div>
</section>

<?php
include("includes/db.php");
$featured_query = "SELECT * FROM products ORDER BY product_id DESC LIMIT 4";
$featured_result = mysqli_query($conn, $featured_query);
?>

<section class="featured-products-section">
    <div class="section-heading">
        <h2>Featured Favorites</h2>
        <p>A few popular picks from Zafar’s Cafe.</p>
    </div>

    <div class="featured-products-grid">
        <?php while ($product = mysqli_fetch_assoc($featured_result)) { ?>
            <div class="featured-product-card">
                <div class="featured-product-image">
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>

                <div class="featured-product-info">
                    <span class="featured-badge"><?php echo htmlspecialchars($product['category']); ?></span>
                    <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <div class="featured-product-bottom">
                        <span class="featured-price">$<?php echo number_format($product['price'], 2); ?></span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="featured-products-btn">
        <a href="products.php" class="btn btn-green">View All Products</a>
    </div>
</section>

<section class="featured-section">
    <div class="section-heading">
        <h2>Why Customers Love Zafar’s Cafe</h2>
        <p>Fresh favorites, neighborhood convenience, and quick access to daily essentials.</p>
    </div>

    <div class="feature-grid">
        <div class="feature-box">
            <h3>Fresh Food</h3>
            <p>From hot coffee and bagels to pizza slices and patties, there is always something ready to enjoy.</p>
        </div>

        <div class="feature-box">
            <h3>Everyday Essentials</h3>
            <p>Pick up drinks, chips, milk, and grocery staples all in one quick stop.</p>
        </div>

        <div class="feature-box">
            <h3>Local Convenience</h3>
            <p>Built for the neighborhood with a simple, friendly experience and easy browsing online.</p>
        </div>
    </div>
</section>

<section class="category-preview-section">
    <div class="section-heading">
        <h2>Shop by Category</h2>
        <p>Explore the categories customers search for most.</p>
    </div>

    <div class="category-preview-grid">
        <a href="products.php?category=Coffee" class="category-preview-card">Coffee</a>
        <a href="products.php?category=Cold%20Drinks" class="category-preview-card">Cold Drinks</a>
        <a href="products.php?category=Breakfast" class="category-preview-card">Breakfast</a>
        <a href="products.php?category=Snacks" class="category-preview-card">Snacks</a>
        <a href="products.php?category=Food" class="category-preview-card">Food</a>
        <a href="products.php?category=Grocery" class="category-preview-card">Grocery</a>
    </div>
</section>

<section class="cta-section">
    <div class="cta-box">
        <h2>Fresh bites, drinks, and essentials right around the corner</h2>
        <p>Browse products, create an account, and explore what Zafar’s Cafe has to offer.</p>

        <div class="hero-buttons">
            <a href="products.php" class="btn btn-green">View Products</a>
            <?php if (!isset($_SESSION["user_id"])): ?>
                <a href="login.php" class="btn btn-red">Login</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>