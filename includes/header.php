<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zafar's Cafe</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="navbar">
    <h2 class="logo">Zafar's Cafe</h2>

    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="feedback.php">Feedback</a>

        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="homepage.php">Dashboard</a>
            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <a href="admin.php">Admin</a>
            <?php endif; ?>
            <span class="welcome">Hi, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></span>
            <a href="logout.php" class="logout-btn">Logout</a>
        <?php else: ?>
            <a href="login.php" class="login-btn">Login</a>
            <a href="register.php" class="register-btn">Register</a>
        <?php endif; ?>
    </nav>
</header>
