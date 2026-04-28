<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
?>

<div class="form-container">
    <h2>Welcome, <?php echo $_SESSION["full_name"]; ?>!</h2>
    <p style="text-align:center; margin-top:15px;">You have successfully logged in to Zafar's Cafe.</p>
</div>

<?php include("includes/footer.php"); ?>