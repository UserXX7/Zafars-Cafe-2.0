<?php
include("../includes/admin_auth.php");
include("../includes/db.php");

$errors = [];
$success = "";

if (!isset($_GET["id"])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET["id"]);

$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    header("Location: manage_products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

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

        $update_query = "UPDATE products SET
            product_name = '$product_name',
            category = '$category',
            description = '$description',
            price = '$price',
            stock_quantity = '$stock_quantity',
            is_featured = '$is_featured',
            status = '$status',
            image = '$image'
            WHERE product_id = $product_id";

        if (mysqli_query($conn, $update_query)) {
            $success = "Product updated successfully.";

            $query = "SELECT * FROM products WHERE product_id = $product_id";
            $result = mysqli_query($conn, $query);
            $product = mysqli_fetch_assoc($result);
        } else {
            $errors[] = "Error updating product: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Zafar's Cafe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container">
    <h2>Edit Product</h2>

    <div style="text-align:center; margin-bottom:20px;">
        <a href="admin_dashboard.php">Back to Dashboard</a> |
        <a href="manage_products.php">Manage Products</a>
    </div>

    <?php
    foreach ($errors as $error) {
        echo "<p style='color:red; margin-bottom:10px;'>$error</p>";
    }

    if (!empty($success)) {
        echo "<p style='color:green; margin-bottom:10px;'>$success</p>";
    }
    ?>

    <form method="POST">
        <input type="text" name="product_name" placeholder="Product Name"
               value="<?php echo htmlspecialchars($product['product_name']); ?>">

        <input type="text" name="category" placeholder="Category"
               value="<?php echo htmlspecialchars($product['category']); ?>">

        <textarea name="description" placeholder="Description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>

        <input type="text" name="price" placeholder="Price"
               value="<?php echo htmlspecialchars($product['price']); ?>">

        <input type="number" name="stock_quantity" placeholder="Stock Quantity"
               value="<?php echo htmlspecialchars($product['stock_quantity']); ?>">

        <input type="text" name="image" placeholder="Image filename"
               value="<?php echo htmlspecialchars($product['image']); ?>">

        <label>
            <input type="checkbox" name="is_featured" <?php if ($product["is_featured"] == 1) echo "checked"; ?>>
            Featured Product
        </label>

        <select name="status">
            <option value="active" <?php if ($product["status"] == "active") echo "selected"; ?>>Active</option>
            <option value="inactive" <?php if ($product["status"] == "inactive") echo "selected"; ?>>Inactive</option>
        </select>

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>