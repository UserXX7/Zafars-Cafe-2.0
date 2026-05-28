<?php
include("../includes/admin_auth.php");
include("../includes/db.php");

$query = "SELECT * FROM products ORDER BY product_id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Zafar's Cafe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container" style="max-width: 1100px;">
    <h2>Manage Products</h2>

    <?php
    if (isset($_GET["deleted"]) && $_GET["deleted"] == "success") {
        echo "<p style='color:green; text-align:center;'>Product deleted successfully.</p>";
    }
    ?>

    <div style="text-align:center; margin-bottom:20px;">
        <a href="admin_dashboard.php">Back to Dashboard</a> |
        <a href="add_product.php">Add New Product</a> |
        <a href="../logout.php">Logout</a>
    </div>

    <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; text-align:center;">
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Product Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Featured</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row["product_id"]; ?></td>

                <td>
                    <?php
                    $image_file = $row["image"];
                    $image_path = "../images/product_images/" . $image_file;

                    if (!empty($image_file) && file_exists($image_path)) {
                        echo '<img src="' . htmlspecialchars($image_path) . '" 
                                   alt="' . htmlspecialchars($row["product_name"]) . '" 
                                   style="width:70px; height:70px; object-fit:cover;">';
                    } else {
                        echo '<span style="color:#999; font-size:13px;">No Image</span>';
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($row["product_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["category"]); ?></td>
                <td>$<?php echo number_format($row["price"], 2); ?></td>
                <td><?php echo $row["stock_quantity"]; ?></td>
                <td><?php echo $row["is_featured"] == 1 ? "Yes" : "No"; ?></td>
                <td><?php echo htmlspecialchars($row["status"]); ?></td>

                <td>
                    <a href="edit_product.php?id=<?php echo $row["product_id"]; ?>">Edit</a> |
                    <a href="delete_product.php?id=<?php echo $row["product_id"]; ?>" 
                       onclick="return confirm('Are you sure you want to delete this product?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>