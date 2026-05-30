<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$errors = [];
$success = "";

$product_name = "";
$category = "";
$description = "";
$price = "";
$stock_quantity = "";
$image = "";
$is_featured = 0;
$is_on_sale = 0;
$discount_type = "none";
$discount_value = "0.00";
$status = "active";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST["product_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $stock_quantity = trim($_POST["stock_quantity"] ?? "");
    $image = trim($_POST["image"] ?? "");
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;
    $is_on_sale = isset($_POST["is_on_sale"]) ? 1 : 0;
    $discount_type = $_POST["discount_type"] ?? "none";
    $discount_value = trim($_POST["discount_value"] ?? "0");
    $status = $_POST["status"] ?? "active";

    if (empty($product_name)) {
        $errors[] = "Product name is required.";
    }

    if (empty($category)) {
        $errors[] = "Category is required.";
    }

    if ($price === "" || !is_numeric($price) || $price < 0) {
        $errors[] = "Valid price is required.";
    }

    if ($stock_quantity === "" || !is_numeric($stock_quantity) || $stock_quantity < 0) {
        $errors[] = "Valid stock quantity is required.";
    }

    if ($status !== "active" && $status !== "inactive") {
        $errors[] = "Invalid product status.";
    }

    if ($is_on_sale == 1) {
        if ($discount_type !== "percentage" && $discount_type !== "amount") {
            $errors[] = "Please select a valid discount type.";
        }

        if ($discount_value === "" || !is_numeric($discount_value) || $discount_value <= 0) {
            $errors[] = "Please enter a valid discount value.";
        }

        if ($discount_type === "percentage" && $discount_value > 100) {
            $errors[] = "Percentage discount cannot be more than 100.";
        }

        if ($discount_type === "amount" && is_numeric($price) && $discount_value > $price) {
            $errors[] = "Dollar discount cannot be greater than the product price.";
        }
    } else {
        $discount_type = "none";
        $discount_value = 0;
    }

    if (empty($errors)) {
        $product_name = mysqli_real_escape_string($conn, $product_name);
        $category = mysqli_real_escape_string($conn, $category);
        $description = mysqli_real_escape_string($conn, $description);
        $price = mysqli_real_escape_string($conn, $price);
        $stock_quantity = mysqli_real_escape_string($conn, $stock_quantity);
        $image = mysqli_real_escape_string($conn, $image);
        $status = mysqli_real_escape_string($conn, $status);
        $discount_type = mysqli_real_escape_string($conn, $discount_type);
        $discount_value = mysqli_real_escape_string($conn, $discount_value);

        $insert_query = "INSERT INTO products 
            (product_name, category, description, price, stock_quantity, image, is_featured, is_on_sale, discount_type, discount_value, status)
            VALUES 
            ('$product_name', '$category', '$description', '$price', '$stock_quantity', '$image', '$is_featured', '$is_on_sale', '$discount_type', '$discount_value', '$status')";

        if (mysqli_query($conn, $insert_query)) {
            $success = "Product added successfully.";

            $product_name = "";
            $category = "";
            $description = "";
            $price = "";
            $stock_quantity = "";
            $image = "";
            $is_featured = 0;
            $is_on_sale = 0;
            $discount_type = "none";
            $discount_value = "0.00";
            $status = "active";
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
    <title>Add Product | Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-form-container">

    <div class="admin-page-header">
        <div>
            <p class="admin-small-title">Product Management</p>
            <h1>Add New Product</h1>
            <p>Create a new store item with price, stock, image, featured status, sale discount, and visibility.</p>
        </div>

        <div class="admin-header-actions">
            <a href="admin_dashboard.php" class="admin-outline-btn">Dashboard</a>
            <a href="manage_products.php" class="admin-outline-btn">Manage Products</a>
            <a href="../logout.php" class="admin-danger-btn">Logout</a>
        </div>
    </div>

    <div class="admin-form-card">

        <?php
        if (!empty($errors)) {
            echo '<div class="alert-box error-alert">';
            foreach ($errors as $error) {
                echo "<p>" . htmlspecialchars($error) . "</p>";
            }
            echo '</div>';
        }

        if (!empty($success)) {
            echo '<div class="alert-box success-alert">';
            echo "<p>" . htmlspecialchars($success) . "</p>";
            echo '</div>';
        }
        ?>

        <form method="POST" class="modern-admin-form">

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" name="product_name" placeholder="Example: Iced Coffee"
                           value="<?php echo htmlspecialchars($product_name); ?>">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" placeholder="Example: Coffee"
                           value="<?php echo htmlspecialchars($category); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Write a short product description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="price" placeholder="Example: 3.99"
                           value="<?php echo htmlspecialchars($price); ?>">
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity" placeholder="Example: 25"
                           value="<?php echo htmlspecialchars($stock_quantity); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Image Filename</label>
                <input type="text" name="image" placeholder="Example: coke-can.jpg"
                       value="<?php echo htmlspecialchars($image); ?>">
                <small>Image files should be placed inside <strong>images/product_images/</strong>.</small>
            </div>

            <div class="form-grid-2">
                <div class="form-group checkbox-card">
                    <label>
                        <input type="checkbox" name="is_featured" <?php if ($is_featured == 1) echo "checked"; ?>>
                        Mark as Featured Product
                    </label>
                </div>

                <div class="form-group checkbox-card">
                    <label>
                        <input type="checkbox" name="is_on_sale" <?php if ($is_on_sale == 1) echo "checked"; ?>>
                        Mark as On Sale Product
                    </label>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Discount Type</label>
                    <select name="discount_type">
                        <option value="none" <?php if ($discount_type == "none") echo "selected"; ?>>No Discount</option>
                        <option value="percentage" <?php if ($discount_type == "percentage") echo "selected"; ?>>Percentage Off</option>
                        <option value="amount" <?php if ($discount_type == "amount") echo "selected"; ?>>Dollar Amount Off</option>
                    </select>
                    <small>Only required when the product is marked as on sale.</small>
                </div>

                <div class="form-group">
                    <label>Discount Value</label>
                    <input type="text" name="discount_value" placeholder="Example: 10 or 1.50"
                           value="<?php echo htmlspecialchars($discount_value); ?>">
                    <small>Use 10 for 10% off, or 1.50 for $1.50 off.</small>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?php if ($status == "active") echo "selected"; ?>>Active</option>
                    <option value="inactive" <?php if ($status == "inactive") echo "selected"; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="admin-submit-btn">Add Product</button>
        </form>
    </div>
</div>

</body>
</html>