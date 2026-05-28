<?php
include("../includes/admin_auth.php");
include("../includes/db.php");

if (!isset($_GET["id"])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET["id"]);

$query = "DELETE FROM products WHERE product_id = $product_id";

if (mysqli_query($conn, $query)) {
    header("Location: manage_products.php?deleted=success");
    exit();
} else {
    echo "Error deleting product: " . mysqli_error($conn);
}
?>