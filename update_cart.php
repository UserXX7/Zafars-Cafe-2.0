<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = intval($_POST["product_id"]);
    $quantity = intval($_POST["quantity"]);

    if ($quantity < 1) {
        $quantity = 1;
    }

    if (isset($_SESSION["cart"][$product_id])) {
        $_SESSION["cart"][$product_id]["quantity"] = $quantity;
    }
}

header("Location: cart.php");
exit();
?>