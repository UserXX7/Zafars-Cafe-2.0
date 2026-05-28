<?php
include("../includes/admin_auth.php");
include("../includes/db.php");

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST["product_name"]);
    $category = trim($_POST["category"]);
    $description = trim($_POST["description"]);
    $price = trim($_POST["price"]);
    $stock_quantity = trim($_POST["stock_quantity"]);
    $image = trim($_POST["image"]);
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;
    $status = $_POST["status"];

    if (empty($product_name)) {
        $errors[] = "Product name is required.";
    }

    if (empty($category)) {
        $errors[] = "Category is required.";
    }

    if (empty($price) || !is_numeric($price)) {
        $errors[] = "Valid price is required.";
    }

    if ($stock_quantity === "" || !is_numeric($stock_quantity)) {
        $errors[] = "Valid stock quantity is required.";
    }

    if (empty($errors)) {
        $product_name = mysqli_real_escape_string($conn, $product_name);
        $category = mysqli_real_escape_string($conn, $category);
        $description = mysqli_real_escape_string($conn, $description);
        $image = mysqli_real_escape_string($conn, $image);
        $status = mysqli_real_escape_string($conn, $status);

        $query = "INSERT INTO products 
                  (product_name, category, description, price, stock_quantity, is_featured, status, image)
                  VALUES 
                  ('$product_name', '$category', '$description', '$price', '$stock_quantity', '$is_featured', '$status', '$image')";

        if (mysqli_query($conn, $query)) {
            $success = "Product added successfully.";
        } else {
            $errors[] = "Error adding product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product | Zafar's Cafe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">
    <h2>Add New Product</h2>

    <div style="text-align:center; margin-bottom:20px;">
        <a href="admin_dashboard.php">Back to Dashboard</a> |
        <a href="manage_products.php">Manage Products</a>
    </div>

    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red; margin-bottom:10px;'>$error</p>";
        }
    }

    if (!empty($success)) {
        echo "<p style='color:green; margin-bottom:10px;'>$success</p>";
    }
    ?>

    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name">

        <input type="text" name="category" placeholder="Category">

        <textarea name="description" placeholder="Description" rows="4"></textarea>

        <input type="text" name="price" placeholder="Price">

        <input type="number" name="stock_quantity" placeholder="Stock Quantity">

        <input type="text" name="image" placeholder="Image filename, example: coke-can.jpg">

        <label>
            <input type="checkbox" name="is_featured">
            Featured Product
        </label>

        <select name="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>

        <button type="submit">Add Product</button>
    </form>
</div>

</body>
</html>