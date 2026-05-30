<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include("includes/db.php");

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        $email = mysqli_real_escape_string($conn, $email);

        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["full_name"] = $user["full_name"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] === "admin") {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: profile.php");
                }
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "Email not found";
        }
    }
}
?>

<?php include("includes/header.php"); ?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-left">
            <p class="auth-tag">Welcome Back</p>
            <h1>Login to Zafar's Cafe & Convenience</h1>
            <p>
                Access your profile, view your order history, and continue shopping from your account.
            </p>

            <div class="auth-highlights">
                <span>Fresh cafe items</span>
                <span>Easy checkout</span>
                <span>Order history</span>
            </div>
        </div>

        <div class="auth-right">
            <h2>Login</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert-box error-alert">';
                foreach ($errors as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo '</div>';
            }
            ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password">
                </div>

                <button type="submit" class="auth-submit-btn">Login</button>
            </form>

            <p class="auth-switch">
                Don’t have an account?
                <a href="register.php">Create one</a>
            </p>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>