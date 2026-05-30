<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

if (!isset($_GET["id"])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET["id"]);

if ($product_id <= 0) {
    header("Location: manage_products.php");
    exit();
}

/* Check product exists first */
$check_query = "SELECT product_id FROM products WHERE product_id = $product_id";
$check_result = mysqli_query($conn, $check_query);

if (!$check_result) {
    die("Product check failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($check_result) != 1) {
    header("Location: manage_products.php");
    exit();
}

/* Delete product */
$delete_query = "DELETE FROM products WHERE product_id = $product_id";

if (mysqli_query($conn, $delete_query)) {
    header("Location: manage_products.php?deleted=success");
    exit();
} else {
    die("Error deleting product: " . mysqli_error($conn));
}
?>