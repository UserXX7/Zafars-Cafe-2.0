<?php
include("includes/db.php");

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

<div class="form-container">
    <h2>Create Account</h2>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>

    <form method="POST">
        <input type="text" name="full_name" placeholder="Full Name">
        <input type="email" name="email" placeholder="Email">
        <input type="text" name="phone" placeholder="Phone">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="confirm_password" placeholder="Confirm Password">
        <button type="submit">Register</button>
    </form>
</div>

<?php include("includes/footer.php"); ?>