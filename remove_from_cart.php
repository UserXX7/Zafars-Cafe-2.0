<?php
session_start();

if (isset($_GET["id"])) {
    $product_id = intval($_GET["id"]);

    if (isset($_SESSION["cart"][$product_id])) {
        unset($_SESSION["cart"][$product_id]);
    }
}

header("Location: cart.php");
exit();
?>