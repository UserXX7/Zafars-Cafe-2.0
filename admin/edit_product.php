<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$errors = [];
$success = "";

if (!isset($_GET["id"])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET["id"]);

if ($product_id <= 0) {
    header("Location: manage_products.php");
    exit();
}

$query = "SELECT * FROM products WHERE product_id = $product_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Product query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) != 1) {
    header("Location: manage_products.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = trim($_POST["product_name"] ?? "");
    $category = trim($_POST["category"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $price = trim($_POST["price"] ?? "");
    $stock_quantity = trim($_POST["stock_quantity"] ?? "");
    $image = trim($_POST["image"] ?? "");
    $is_featured = isset($_POST["is_featured"]) ? 1 : 0;
    $is_on_sale = intval($_POST["is_on_sale"] ?? 0);
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
            $errors[] = "Please select Percentage Off or Dollar Amount Off for sale products.";
        }

        if ($discount_value === "" || !is_numeric($discount_value) || floatval($discount_value) <= 0) {
            $errors[] = "Please enter a valid discount value.";
        }

        if ($discount_type === "percentage" && floatval($discount_value) > 100) {
            $errors[] = "Percentage discount cannot be more than 100.";
        }

        if ($discount_type === "amount" && is_numeric($price) && floatval($discount_value) > floatval($price)) {
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

        $update_query = "UPDATE products SET
            product_name = '$product_name',
            category = '$category',
            description = '$description',
            price = '$price',
            stock_quantity = '$stock_quantity',
            image = '$image',
            is_featured = '$is_featured',
            is_on_sale = '$is_on_sale',
            discount_type = '$discount_type',
            discount_value = '$discount_value',
            status = '$status'
            WHERE product_id = $product_id";

        if (mysqli_query($conn, $update_query)) {
            $success = "Product updated successfully.";

            $query = "SELECT * FROM products WHERE product_id = $product_id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $product = mysqli_fetch_assoc($result);
            }
        } else {
            $errors[] = "Error updating product: " . mysqli_error($conn);
        }
    }
}

$preview_image = $product["image"] ?? "";
$preview_path = "../images/product_images/" . $preview_image;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product | Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-form-container">

    <div class="admin-page-header">
        <div>
            <p class="admin-small-title">Product Management</p>
            <h1>Edit Product</h1>
            <p>Update product details, featured status, sale discount, image filename, stock, and visibility.</p>
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
                           value="<?php echo htmlspecialchars($product["product_name"] ?? ""); ?>">
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <input type="text" name="category" placeholder="Example: Coffee"
                           value="<?php echo htmlspecialchars($product["category"] ?? ""); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Write a short product description"><?php echo htmlspecialchars($product["description"] ?? ""); ?></textarea>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Price</label>
                    <input type="text" name="price" id="productPrice" placeholder="Example: 3.99"
                        value="<?php echo htmlspecialchars($product["price"] ?? ""); ?>"
                        oninput="calculateSalePrice()">
                </div>

                <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" name="stock_quantity" placeholder="Example: 25"
                           value="<?php echo htmlspecialchars($product["stock_quantity"] ?? ""); ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Image Filename</label>
                <input type="text" name="image" placeholder="Example: coke-can.jpg"
                       value="<?php echo htmlspecialchars($product["image"] ?? ""); ?>">
                <small>Image files should be placed inside <strong>images/product_images/</strong>.</small>

                <div style="margin-top: 15px;">
                    <?php if (!empty($preview_image) && file_exists($preview_path)) { ?>
                        <img src="<?php echo htmlspecialchars($preview_path); ?>" 
                             alt="<?php echo htmlspecialchars($product["product_name"] ?? "Product Image"); ?>"
                             style="width: 120px; height: 120px; object-fit: contain; background: #f8fbf8; border-radius: 16px; padding: 10px;">
                    <?php } else { ?>
                        <div style="width: 120px; height: 120px; display:flex; align-items:center; justify-content:center; background:#f4f4f4; color:#777; border-radius:16px; font-style:italic;">
                            No Image
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group checkbox-card">
                    <label>
                        <input type="checkbox" name="is_featured" <?php if (($product["is_featured"] ?? 0) == 1) echo "checked"; ?>>
                        Mark as Featured Product
                    </label>
                </div>

                <div class="form-group checkbox-card">
                    <label>
                        <input type="hidden" name="is_on_sale" value="0">
                        <input type="checkbox" name="is_on_sale" value="1" id="isOnSaleCheckbox"
                            <?php if (($product["is_on_sale"] ?? 0) == 1) echo "checked"; ?>
                            onchange="calculateSalePrice()">
                        Mark as On Sale Product
                    </label>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label>Discount Type</label>
                    <select name="discount_type" id="discountType" onchange="calculateSalePrice()">
                        <option value="none" <?php if (($product["discount_type"] ?? "") == "none") echo "selected"; ?>>No Discount</option>
                        <option value="percentage" <?php if (($product["discount_type"] ?? "") == "percentage") echo "selected"; ?>>Percentage Off</option>
                        <option value="amount" <?php if (($product["discount_type"] ?? "") == "amount") echo "selected"; ?>>Dollar Amount Off</option>
                    </select>
                    <small>Only required when the product is marked as on sale.</small>
                </div>

                <div class="form-group">
                    <label>Discount Value</label>
                    <input type="text" name="discount_value" id="discountValue" placeholder="Example: 10 or 1.50"
                        value="<?php echo htmlspecialchars($product["discount_value"] ?? "0.00"); ?>"
                        oninput="calculateSalePrice()">
                    <small>Use 10 for 10% off, or 1.50 for $1.50 off.</small>
                </div>
            </div>

            <div class="sale-price-preview-box">
                <span>Sale Price Preview</span>

                <div>
                    <p>Original Price: <strong id="originalPricePreview">$<?php echo number_format(floatval($product["price"] ?? 0), 2); ?></strong></p>
                    <p>Discounted Price: <strong id="salePricePreview">$<?php echo number_format(floatval($product["price"] ?? 0), 2); ?></strong></p>
                    <p id="discountMessage">Select discount type and value to preview sale price.</p>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?php if (($product["status"] ?? "") == "active") echo "selected"; ?>>Active</option>
                    <option value="inactive" <?php if (($product["status"] ?? "") == "inactive") echo "selected"; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" class="admin-submit-btn">Update Product</button>
        </form>
    </div>
</div>

<script>
function calculateSalePrice() {
    const priceInput = document.getElementById("productPrice");
    const discountType = document.getElementById("discountType");
    const discountValue = document.getElementById("discountValue");
    const originalPricePreview = document.getElementById("originalPricePreview");
    const salePricePreview = document.getElementById("salePricePreview");
    const discountMessage = document.getElementById("discountMessage");

    let price = parseFloat(priceInput.value);
    let discount = parseFloat(discountValue.value);
    let salePrice = price;

    if (isNaN(price) || price < 0) {
        originalPricePreview.textContent = "$0.00";
        salePricePreview.textContent = "$0.00";
        discountMessage.textContent = "Enter a valid product price first.";
        return;
    }

    originalPricePreview.textContent = "$" + price.toFixed(2);

    if (discountType.value === "percentage") {
        if (isNaN(discount) || discount <= 0) {
            salePricePreview.textContent = "$" + price.toFixed(2);
            discountMessage.textContent = "Enter a valid percentage discount.";
            return;
        }

        if (discount > 100) {
            salePricePreview.textContent = "$0.00";
            discountMessage.textContent = "Percentage discount cannot be more than 100%.";
            return;
        }

        salePrice = price - (price * discount / 100);
        discountMessage.textContent = discount + "% discount applied.";

    } else if (discountType.value === "amount") {
        if (isNaN(discount) || discount <= 0) {
            salePricePreview.textContent = "$" + price.toFixed(2);
            discountMessage.textContent = "Enter a valid dollar discount.";
            return;
        }

        if (discount > price) {
            salePricePreview.textContent = "$0.00";
            discountMessage.textContent = "Dollar discount cannot be greater than product price.";
            return;
        }

        salePrice = price - discount;
        discountMessage.textContent = "$" + discount.toFixed(2) + " discount applied.";

    } else {
        salePrice = price;
        discountMessage.textContent = "No discount selected.";
    }

    if (salePrice < 0) {
        salePrice = 0;
    }

    salePricePreview.textContent = "$" + salePrice.toFixed(2);
}

calculateSalePrice();
</script>

</body>
</html>