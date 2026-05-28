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
    <title>Zafar's Cafe</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>style.css">
</head>
<body>

<header class="navbar">
    <h2 class="logo">Zafar's Cafe</h2>

    <nav>
        <a href="<?php echo $base_url; ?>index.php">Home</a>
        <a href="<?php echo $base_url; ?>products.php">Products</a>

        <?php if (isset($_SESSION["user_id"])): ?>

            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <a href="<?php echo $base_url; ?>admin/admin_dashboard.php">Dashboard</a>
            <?php else: ?>
                <a href="<?php echo $base_url; ?>homepage.php">Dashboard</a>
            <?php endif; ?>

            <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
            <a href="<?php echo $base_url; ?>logout.php" class="logout-btn">Logout</a>

        <?php else: ?>

            <a href="<?php echo $base_url; ?>login.php" class="login-btn">Login</a>
            <a href="<?php echo $base_url; ?>register.php" class="register-btn">Register</a>

        <?php endif; ?>
    </nav>
</header>