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

function calculateFinalPrice($price, $is_on_sale, $discount_type, $discount_value) {
    $price = floatval($price);
    $discount_value = floatval($discount_value);

    if (intval($is_on_sale) !== 1 || $discount_value <= 0) {
        return $price;
    }

    if ($discount_type === "percentage") {
        return max(0, $price - ($price * ($discount_value / 100)));
    }

    if ($discount_type === "fixed") {
        return max(0, $price - $discount_value);
    }

    return $price;
}

$query = "
    SELECT 
        product_id,
        product_name,
        price,
        image,
        stock_quantity,
        is_on_sale,
        discount_type,
        discount_value
    FROM products
    WHERE product_id = $product_id 
    AND status = 'active'
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) != 1) {
    header("Location: products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

$final_price = calculateFinalPrice(
    $product["price"],
    $product["is_on_sale"],
    $product["discount_type"],
    $product["discount_value"]
);

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

if (isset($_SESSION["cart"][$product_id])) {
    $_SESSION["cart"][$product_id]["quantity"] += $quantity;

    /* Refresh price in case sale status changed */
    $_SESSION["cart"][$product_id]["price"] = $final_price;
    $_SESSION["cart"][$product_id]["original_price"] = $product["price"];
    $_SESSION["cart"][$product_id]["is_on_sale"] = $product["is_on_sale"];
    $_SESSION["cart"][$product_id]["discount_type"] = $product["discount_type"];
    $_SESSION["cart"][$product_id]["discount_value"] = $product["discount_value"];
} else {
    $_SESSION["cart"][$product_id] = [
        "product_id" => $product["product_id"],
        "product_name" => $product["product_name"],
        "price" => $final_price,
        "original_price" => $product["price"],
        "is_on_sale" => $product["is_on_sale"],
        "discount_type" => $product["discount_type"],
        "discount_value" => $product["discount_value"],
        "quantity" => $quantity,
        "image" => $product["image"]
    ];
}

header("Location: cart.php");
exit();
?>