<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'includes/db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // REQUIRED FIELDS
    if (empty($full_name)) $errors[] = "Full name is required";
    if (empty($email)) $errors[] = "Email is required";
    if (empty($phone)) $errors[] = "Phone is required";
    if (empty($password)) $errors[] = "Password is required";

    // EMAIL FORMAT
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

    // PASSWORD LENGTH
    if (!empty($password) && strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

    // CONFIRM PASSWORD
    if (!empty($password) && $password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    // CHECK IF EMAIL EXISTS
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $errors[] = "Email already exists";
    }

    // IF NO ERRORS → INSERT
    if (empty($errors)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (full_name, email, phone, password)
                  VALUES ('$full_name', '$email', '$phone', '$hashed_password')";

        if (mysqli_query($conn, $query)) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Something went wrong";
        }
    }
}
?>

<?php include("includes/header.php"); ?>

<section class="auth-page">
    <div class="auth-card">
        <div class="auth-left">
            <p class="auth-tag">Join Zafar's Cafe & Convenience</p>
            <h1>Create your account</h1>
            <p>
                Register to shop faster, manage your profile, and view your past orders anytime.
            </p>

            <div class="auth-highlights">
                <span>Quick checkout</span>
                <span>Profile access</span>
                <span>Order tracking</span>
            </div>
        </div>

        <div class="auth-right">
            <h2>Create Account</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert-box error-alert">';
                foreach ($errors as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo '</div>';
            }

            if (!empty($success)) {
                echo '<div class="alert-box success-alert">';
                echo "<p>" . htmlspecialchars($success) . "</p>";
                echo '</div>';
            }
            ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a password">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your password">
                </div>

                <button type="submit" class="auth-submit-btn">Register</button>
            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="login.php">Login here</a>
            </p>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>