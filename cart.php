<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");

$cart = $_SESSION["cart"] ?? [];
$total = 0;
?>

<section class="modern-cart-page">
    <div class="modern-cart-header">
        <div>
            <p class="profile-tag">Shopping Cart</p>
            <h1>Your Cart</h1>
            <p>Review your selected items, update quantities, or continue shopping.</p>
        </div>

        <div class="profile-hero-actions">
            <a href="products.php" class="admin-outline-btn">Continue Shopping</a>
            <?php if (!empty($cart)) { ?>
                <a href="checkout.php" class="admin-primary-btn">Proceed to Checkout</a>
            <?php } ?>
        </div>
    </div>

    <?php if (!empty($cart)) { ?>

        <div class="modern-cart-card">
            <div class="table-scroll">
                <table class="modern-cart-table">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>

                    <?php foreach ($cart as $product_id => $item) { 
                        $subtotal = $item["price"] * $item["quantity"];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <div class="cart-product-info">
                                    <?php
                                    $image_file = $item["image"] ?? "";
                                    $image_path = "images/product_images/" . $image_file;

                                    if (!empty($image_file) && file_exists($image_path)) {
                                        echo '<img src="' . htmlspecialchars($image_path) . '" alt="' . htmlspecialchars($item["product_name"]) . '">';
                                    } else {
                                        echo '<div class="cart-no-image">No Image</div>';
                                    }
                                    ?>

                                    <div>
                                        <strong><?php echo htmlspecialchars($item["product_name"]); ?></strong>
                                    </div>
                                </div>
                            </td>

                            <td>$<?php echo number_format($item["price"], 2); ?></td>

                            <td>
                                <form method="POST" action="update_cart.php" class="cart-qty-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                                    <div class="cart-qty-control">
                                        <button type="button" onclick="changeQty(this, -1)">−</button>
                                        <input type="number" name="quantity" value="<?php echo $item["quantity"]; ?>" min="1">
                                        <button type="button" onclick="changeQty(this, 1)">+</button>
                                    </div>

                                    <button type="submit" class="cart-update-btn">Update</button>
                                </form>
                            </td>

                            <td>
                                <strong>$<?php echo number_format($subtotal, 2); ?></strong>
                            </td>

                            <td>
                                <a href="remove_from_cart.php?id=<?php echo $product_id; ?>" 
                                   class="cart-remove-btn"
                                   onclick="return confirm('Remove this item from your cart?');">
                                   Remove
                                </a>
                            </td>
                        </tr>
                    <?php } ?>

                    <tr class="cart-total-row">
                        <td colspan="3">Total</td>
                        <td colspan="2">$<?php echo number_format($total, 2); ?></td>
                    </tr>
                </table>
            </div>

            <div class="cart-bottom-actions">
                <a href="products.php" class="admin-outline-btn">Continue Shopping</a>
                <a href="checkout.php" class="admin-primary-btn">Proceed to Checkout</a>
            </div>
        </div>

    <?php } else { ?>

        <div class="empty-state-card">
            <h2>Your cart is empty.</h2>
            <p>Add products to your cart and they will appear here.</p>
            <a href="products.php" class="admin-primary-btn">Browse Products</a>
        </div>

    <?php } ?>
</section>

<script>
function changeQty(button, amount) {
    const input = button.parentElement.querySelector("input[type='number']");
    let currentValue = parseInt(input.value);

    if (isNaN(currentValue)) {
        currentValue = 1;
    }

    currentValue += amount;

    if (currentValue < 1) {
        currentValue = 1;
    }

    input.value = currentValue;
}
</script>

<?php include("includes/footer.php"); ?>