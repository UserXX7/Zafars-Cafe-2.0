<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "/MAC272/zafars_cafe/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style.css">
</head>
<body>

<header class="navbar">
    <h2 class="logo">Zafar's Cafe & Convenience</h2>

    <nav>
        <a href="<?php echo $base_url; ?>index.php">Home</a>
        <a href="<?php echo $base_url; ?>products.php">Products</a>

        <?php if (isset($_SESSION["user_id"])): ?>

            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") { ?>

                <a href="admin/admin_dashboard.php">Dashboard</a>
                <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>

            <?php } else { ?>

                <a href="profile.php">Profile</a>

                <span class="welcome">
                    Hi, <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
                </span>

                <?php
                $cart_count = 0;

                if (isset($_SESSION["cart"]) && !empty($_SESSION["cart"])) {
                    foreach ($_SESSION["cart"] as $cart_item) {
                        $cart_count += $cart_item["quantity"];
                    }
                }
                ?>

                <a href="cart.php" class="nav-cart-btn">
                    <span class="cart-label">Cart</span>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                </a>

                <a href="logout.php" class="logout-btn">Logout</a>

            <?php } ?>

        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        <?php endif; ?>
    </nav>
</header>