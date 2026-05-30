<?php
session_start();
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

if (!isset($_GET["id"])) {
    header("Location: products.php");
    exit();
}

$product_id = intval($_GET["id"]);
$quantity = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["quantity"])) {
    $quantity = intval($_POST["quantity"]);
}

if ($quantity < 1) {
    $quantity = 1;
}

$query = "SELECT * FROM products WHERE product_id = $product_id AND status = 'active'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) != 1) {
    header("Location: products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_SESSION["cart"][$product_id])) {
    $_SESSION["cart"][$product_id]["quantity"] += $quantity;
} else {
    $_SESSION["cart"][$product_id] = [
        "product_id" => $product["product_id"],
        "product_name" => $product["product_name"],
        "price" => $product["price"],
        "quantity" => $quantity,
        "image" => $product["image"]
    ];
}

header("Location: cart.php");
exit();
?>