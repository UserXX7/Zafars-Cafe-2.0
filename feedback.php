<?php
include("includes/header.php");
require_once("includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$errors = [];
$success = "";

$user_id = $_SESSION["user_id"] ?? null;

$full_name = "";
$email = "";
$phone = "";
$feedback_type = "general";
$rating = "";
$order_id = "";
$subject = "";
$message = "";

/* Prefill user info if logged in */
if ($user_id) {
    $safe_user_id = intval($user_id);

    $user_query = "SELECT full_name, email, phone FROM users WHERE user_id = $safe_user_id LIMIT 1";
    $user_result = mysqli_query($conn, $user_query);

    if ($user_result && mysqli_num_rows($user_result) > 0) {
        $user = mysqli_fetch_assoc($user_result);
        $full_name = $user["full_name"] ?? "";
        $email = $user["email"] ?? "";
        $phone = $user["phone"] ?? "";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $full_name = trim($_POST["full_name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $feedback_type = trim($_POST["feedback_type"] ?? "general");
    $rating = trim($_POST["rating"] ?? "");
    $order_id = trim($_POST["order_id"] ?? "");
    $subject = trim($_POST["subject"] ?? "");
    $message = trim($_POST["message"] ?? "");

    $allowed_types = ["general", "product", "order", "delivery", "website", "service"];

    if ($full_name === "") {
        $errors[] = "Full name is required.";
    }

    if ($email === "") {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if (!in_array($feedback_type, $allowed_types)) {
        $errors[] = "Please select a valid feedback type.";
    }

    if ($rating !== "" && (!is_numeric($rating) || intval($rating) < 1 || intval($rating) > 5)) {
        $errors[] = "Rating must be between 1 and 5.";
    }

    if ($order_id !== "" && (!is_numeric($order_id) || intval($order_id) < 1)) {
        $errors[] = "Order number must be valid.";
    }

    if ($subject === "") {
        $errors[] = "Subject is required.";
    }

    if ($message === "") {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $rating_value = ($rating === "") ? null : intval($rating);
        $order_id_value = ($order_id === "") ? null : intval($order_id);

        $stmt = mysqli_prepare($conn, "
            INSERT INTO feedback 
            (user_id, full_name, email, phone, feedback_type, rating, order_id, subject, message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "issssiiss",
                $user_id,
                $full_name,
                $email,
                $phone,
                $feedback_type,
                $rating_value,
                $order_id_value,
                $subject,
                $message
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Thank you! Your feedback has been submitted successfully.";

                $feedback_type = "general";
                $rating = "";
                $order_id = "";
                $subject = "";
                $message = "";

                if (!$user_id) {
                    $full_name = "";
                    $email = "";
                    $phone = "";
                }
            } else {
                $errors[] = "Feedback could not be submitted. Please try again.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<main class="feedback-page">

    <section class="feedback-hero">
        <div class="feedback-hero-content">
            <span class="feedback-pill">Customer Feedback</span>

            <h1>Help Us Improve Your Experience</h1>

            <p>
                Share your thoughts about our products, service, website, pickup, delivery,
                or recent order experience. Your feedback helps Zafar's Cafe & Convenience serve you better.
            </p>

            <div class="feedback-hero-actions">
                <a href="products.php" class="feedback-outline-btn">Continue Shopping</a>

                <?php if ($user_id): ?>
                    <a href="order_history.php" class="feedback-solid-btn">View Orders</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="feedback-hero-card">
            <h3>We review every message</h3>
            <p>Feedback is saved directly for admin review and follow-up.</p>
        </div>
    </section>

    <section class="feedback-layout">

        <div class="feedback-info-card">
            <h2>What Can You Share?</h2>

            <div class="feedback-info-item">
                <span>01</span>
                <div>
                    <h4>Product Suggestions</h4>
                    <p>Tell us what products you want us to add or improve.</p>
                </div>
            </div>

            <div class="feedback-info-item">
                <span>02</span>
                <div>
                    <h4>Order Experience</h4>
                    <p>Let us know about checkout, pickup, or delivery experience.</p>
                </div>
            </div>

            <div class="feedback-info-item">
                <span>03</span>
                <div>
                    <h4>Website Feedback</h4>
                    <p>Report broken pages, confusing design, or missing information.</p>
                </div>
            </div>

            <div class="feedback-info-item">
                <span>04</span>
                <div>
                    <h4>Customer Service</h4>
                    <p>Share compliments, concerns, or service improvement ideas.</p>
                </div>
            </div>
        </div>

        <div class="feedback-form-card">
            <div class="feedback-form-header">
                <span>Feedback Form</span>
                <h2>Submit Your Feedback</h2>
                <p>Please fill out the form below. Required fields are marked with *.</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="feedback-alert feedback-error">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="feedback-alert feedback-success">
                    <p><?php echo htmlspecialchars($success); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="feedback-form">

                <div class="feedback-row">
                    <div class="feedback-group">
                        <label>Full Name *</label>
                        <input 
                            type="text" 
                            name="full_name" 
                            value="<?php echo htmlspecialchars($full_name); ?>" 
                            placeholder="Enter your full name"
                        >
                    </div>

                    <div class="feedback-group">
                        <label>Email Address *</label>
                        <input 
                            type="email" 
                            name="email" 
                            value="<?php echo htmlspecialchars($email); ?>" 
                            placeholder="Enter your email address"
                        >
                    </div>
                </div>

                <div class="feedback-row">
                    <div class="feedback-group">
                        <label>Phone Number</label>
                        <input 
                            type="text" 
                            name="phone" 
                            value="<?php echo htmlspecialchars($phone); ?>" 
                            placeholder="Optional phone number"
                        >
                    </div>

                    <div class="feedback-group">
                        <label>Feedback Type *</label>
                        <select name="feedback_type">
                            <option value="general" <?php if ($feedback_type === "general") echo "selected"; ?>>General Feedback</option>
                            <option value="product" <?php if ($feedback_type === "product") echo "selected"; ?>>Product Suggestion</option>
                            <option value="order" <?php if ($feedback_type === "order") echo "selected"; ?>>Order Issue</option>
                            <option value="delivery" <?php if ($feedback_type === "delivery") echo "selected"; ?>>Delivery Feedback</option>
                            <option value="website" <?php if ($feedback_type === "website") echo "selected"; ?>>Website Issue</option>
                            <option value="service" <?php if ($feedback_type === "service") echo "selected"; ?>>Customer Service</option>
                        </select>
                    </div>
                </div>

                <div class="feedback-row">
                    <div class="feedback-group">
                        <label>Rating</label>
                        <select name="rating">
                            <option value="">Select rating</option>
                            <option value="5" <?php if ($rating === "5") echo "selected"; ?>>5 - Excellent</option>
                            <option value="4" <?php if ($rating === "4") echo "selected"; ?>>4 - Good</option>
                            <option value="3" <?php if ($rating === "3") echo "selected"; ?>>3 - Average</option>
                            <option value="2" <?php if ($rating === "2") echo "selected"; ?>>2 - Poor</option>
                            <option value="1" <?php if ($rating === "1") echo "selected"; ?>>1 - Very Poor</option>
                        </select>
                    </div>

                    <div class="feedback-group">
                        <label>Order Number</label>
                        <input 
                            type="number" 
                            name="order_id" 
                            value="<?php echo htmlspecialchars($order_id); ?>" 
                            placeholder="Optional order number"
                        >
                    </div>
                </div>

                <div class="feedback-group">
                    <label>Subject *</label>
                    <input 
                        type="text" 
                        name="subject" 
                        value="<?php echo htmlspecialchars($subject); ?>" 
                        placeholder="Example: Add more coffee options"
                    >
                </div>

                <div class="feedback-group">
                    <label>Message *</label>
                    <textarea 
                        name="message" 
                        rows="6" 
                        placeholder="Write your feedback here..."
                    ><?php echo htmlspecialchars($message); ?></textarea>
                </div>

                <button type="submit" class="feedback-submit-btn">Submit Feedback</button>

            </form>
        </div>

    </section>

</main>

<?php include("includes/footer.php"); ?>