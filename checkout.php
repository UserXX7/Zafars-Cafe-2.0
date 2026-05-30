<?php
session_start();
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION["cart"])) {
    header("Location: cart.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$cart = $_SESSION["cart"];
$errors = [];
$subtotal_total = 0;
$delivery_fee = 0;
$final_total = 0;

$user_query = "SELECT full_name, email, phone FROM users WHERE user_id = $user_id";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($user_result);

if (!$user) {
    header("Location: logout.php");
    exit();
}

foreach ($cart as $item) {
    $subtotal_total += $item["price"] * $item["quantity"];
}

$order_type = "Pickup";
$delivery_address = "";
$delivery_city = "";
$delivery_zip = "";
$delivery_instructions = "";
$requested_time = "";
$payment_method = "Pay at Store";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = trim($_POST["customer_name"]);
    $customer_email = trim($_POST["customer_email"]);
    $customer_phone = trim($_POST["customer_phone"]);

    $order_type = $_POST["order_type"] ?? "Pickup";
    $delivery_address = trim($_POST["delivery_address"] ?? "");
    $delivery_city = trim($_POST["delivery_city"] ?? "");
    $delivery_zip = trim($_POST["delivery_zip"] ?? "");
    $delivery_instructions = trim($_POST["delivery_instructions"] ?? "");
    $requested_time = trim($_POST["requested_time"] ?? "");
    $payment_method = $_POST["payment_method"] ?? "Pay at Store";

    if (empty($customer_name)) {
        $errors[] = "Customer name is required.";
    }

    if (empty($customer_email)) {
        $errors[] = "Customer email is required.";
    }

    if (!empty($customer_email) && !filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($customer_phone)) {
        $errors[] = "Customer phone is required.";
    }

    if ($order_type !== "Pickup" && $order_type !== "Delivery") {
        $errors[] = "Please select pickup or delivery.";
    }

    if ($order_type === "Delivery") {
        $delivery_fee = 3.99;

        if (empty($delivery_address)) {
            $errors[] = "Delivery address is required.";
        }

        if (empty($delivery_city)) {
            $errors[] = "Delivery city is required.";
        }

        if (empty($delivery_zip)) {
            $errors[] = "Delivery ZIP code is required.";
        }

        if ($payment_method !== "Cash on Delivery") {
            $payment_method = "Cash on Delivery";
        }
    } else {
        $delivery_fee = 0.00;
        $delivery_address = "";
        $delivery_city = "";
        $delivery_zip = "";
        $delivery_instructions = "";

        if ($payment_method !== "Pay at Store") {
            $payment_method = "Pay at Store";
        }
    }

    $final_total = $subtotal_total + $delivery_fee;

    if (empty($errors)) {
        $customer_name = mysqli_real_escape_string($conn, $customer_name);
        $customer_email = mysqli_real_escape_string($conn, $customer_email);
        $customer_phone = mysqli_real_escape_string($conn, $customer_phone);
        $order_type = mysqli_real_escape_string($conn, $order_type);
        $delivery_address = mysqli_real_escape_string($conn, $delivery_address);
        $delivery_city = mysqli_real_escape_string($conn, $delivery_city);
        $delivery_zip = mysqli_real_escape_string($conn, $delivery_zip);
        $delivery_instructions = mysqli_real_escape_string($conn, $delivery_instructions);
        $requested_time = mysqli_real_escape_string($conn, $requested_time);
        $payment_method = mysqli_real_escape_string($conn, $payment_method);

        $order_query = "INSERT INTO orders 
                        (user_id, customer_name, customer_email, customer_phone, order_type, delivery_address, delivery_city, delivery_zip, delivery_instructions, requested_time, payment_method, delivery_fee, order_total, order_status)
                        VALUES 
                        ('$user_id', '$customer_name', '$customer_email', '$customer_phone', '$order_type', '$delivery_address', '$delivery_city', '$delivery_zip', '$delivery_instructions', '$requested_time', '$payment_method', '$delivery_fee', '$final_total', 'Pending')";

        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);

            foreach ($cart as $product_id => $item) {
                $product_id = intval($product_id);
                $product_name = mysqli_real_escape_string($conn, $item["product_name"]);
                $price = floatval($item["price"]);
                $quantity = intval($item["quantity"]);
                $subtotal = $price * $quantity;

                $item_query = "INSERT INTO order_items 
                               (order_id, product_id, product_name, price, quantity, subtotal)
                               VALUES 
                               ('$order_id', '$product_id', '$product_name', '$price', '$quantity', '$subtotal')";

                mysqli_query($conn, $item_query);
            }

            unset($_SESSION["cart"]);

            header("Location: order_success.php?order_id=$order_id");
            exit();
        } else {
            $errors[] = "Error placing order: " . mysqli_error($conn);
        }
    }
} else {
    $final_total = $subtotal_total;
}

include("includes/header.php");
?>

<section class="modern-checkout-page">
    <div class="checkout-header-card">
        <div>
            <p class="profile-tag">Secure Checkout</p>
            <h1>Checkout</h1>
            <p>Choose pickup or delivery, review your order, and confirm your information.</p>
        </div>

        <div class="profile-hero-actions">
            <a href="cart.php" class="admin-outline-btn">Back to Cart</a>
            <a href="products.php" class="admin-primary-btn">Continue Shopping</a>
        </div>
    </div>

    <div class="checkout-grid">
        <div class="checkout-summary-card">
            <h2>Order Summary</h2>

            <div class="checkout-items-list">
                <?php foreach ($cart as $item) { 
                    $item_subtotal = $item["price"] * $item["quantity"];
                ?>
                    <div class="checkout-item">
                        <div>
                            <strong><?php echo htmlspecialchars($item["product_name"]); ?></strong>
                            <span>Qty: <?php echo $item["quantity"]; ?></span>
                        </div>

                        <p>$<?php echo number_format($item_subtotal, 2); ?></p>
                    </div>
                <?php } ?>
            </div>

            <div class="checkout-price-breakdown">
                <div>
                    <span>Subtotal</span>
                    <strong>$<?php echo number_format($subtotal_total, 2); ?></strong>
                </div>

                <div id="deliveryFeeRow" style="display:none;">
                    <span>Delivery Fee</span>
                    <strong>$3.99</strong>
                </div>
            </div>

            <div class="checkout-total-box">
                <span>Total</span>
                <strong id="checkoutTotal">$<?php echo number_format($subtotal_total, 2); ?></strong>
            </div>
        </div>

        <div class="checkout-form-card">
            <h2>Checkout Details</h2>

            <?php
            if (!empty($errors)) {
                echo '<div class="alert-box error-alert">';
                foreach ($errors as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                echo '</div>';
            }
            ?>

            <form method="POST" class="modern-checkout-form">
                <div class="checkout-option-toggle">
                    <label class="checkout-option-card active-option" id="pickupCard">
                        <input type="radio" name="order_type" value="Pickup" checked onchange="toggleCheckoutType()">
                        <span>Pickup</span>
                        <small>Pick up from store</small>
                    </label>

                    <label class="checkout-option-card" id="deliveryCard">
                        <input type="radio" name="order_type" value="Delivery" onchange="toggleCheckoutType()">
                        <span>Delivery</span>
                        <small>Local delivery</small>
                    </label>
                </div>

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="customer_name"
                           value="<?php echo htmlspecialchars($user["full_name"] ?? ""); ?>">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="customer_email"
                           value="<?php echo htmlspecialchars($user["email"] ?? ""); ?>">
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="customer_phone"
                           value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>">
                </div>

                <div class="form-group">
                    <label id="timeLabel">Pickup Time</label>
                    <select name="requested_time">
                        <option value="">Select a time</option>
                        <option value="ASAP">ASAP</option>
                        <option value="15-30 minutes">15-30 minutes</option>
                        <option value="30-45 minutes">30-45 minutes</option>
                        <option value="1 hour">1 hour</option>
                        <option value="Later today">Later today</option>
                    </select>
                </div>

                <div id="deliveryFields" style="display:none;">
                    <div class="form-group">
                        <label>Delivery Address</label>
                        <input type="text" name="delivery_address" placeholder="Street address, apartment, floor">
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="delivery_city" placeholder="Example: Elmhurst">
                        </div>

                        <div class="form-group">
                            <label>ZIP Code</label>
                            <input type="text" name="delivery_zip" placeholder="Example: 11373">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Delivery Instructions</label>
                        <textarea name="delivery_instructions" placeholder="Optional: door code, call on arrival, leave at front desk"></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" id="paymentMethod">
                        <option value="Pay at Store">Pay at Store</option>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                    </select>
                </div>

                <div class="checkout-note-box" id="checkoutNote">
                    <strong>Pickup Order</strong>
                    <span>Your order will be saved as Pending. Please pay at the store during pickup.</span>
                </div>

                <button type="submit" class="checkout-place-btn">Place Order</button>
            </form>
        </div>
    </div>
</section>

<script>
function toggleCheckoutType() {
    const selectedType = document.querySelector("input[name='order_type']:checked").value;
    const deliveryFields = document.getElementById("deliveryFields");
    const deliveryFeeRow = document.getElementById("deliveryFeeRow");
    const checkoutTotal = document.getElementById("checkoutTotal");
    const paymentMethod = document.getElementById("paymentMethod");
    const checkoutNote = document.getElementById("checkoutNote");
    const timeLabel = document.getElementById("timeLabel");
    const pickupCard = document.getElementById("pickupCard");
    const deliveryCard = document.getElementById("deliveryCard");

    const subtotal = <?php echo number_format($subtotal_total, 2, ".", ""); ?>;
    const deliveryFee = 3.99;

    if (selectedType === "Delivery") {
        deliveryFields.style.display = "block";
        deliveryFeeRow.style.display = "flex";
        checkoutTotal.textContent = "$" + (subtotal + deliveryFee).toFixed(2);

        paymentMethod.value = "Cash on Delivery";
        timeLabel.textContent = "Delivery Time";

        checkoutNote.innerHTML = "<strong>Delivery Order</strong><span>A $3.99 local delivery fee will be added. Payment is set to Cash on Delivery.</span>";

        pickupCard.classList.remove("active-option");
        deliveryCard.classList.add("active-option");
    } else {
        deliveryFields.style.display = "none";
        deliveryFeeRow.style.display = "none";
        checkoutTotal.textContent = "$" + subtotal.toFixed(2);

        paymentMethod.value = "Pay at Store";
        timeLabel.textContent = "Pickup Time";

        checkoutNote.innerHTML = "<strong>Pickup Order</strong><span>Your order will be saved as Pending. Please pay at the store during pickup.</span>";

        deliveryCard.classList.remove("active-option");
        pickupCard.classList.add("active-option");
    }
}

toggleCheckoutType();
</script>

<?php include("includes/footer.php"); ?>