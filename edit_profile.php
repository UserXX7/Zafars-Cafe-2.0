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

if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") {
    header("Location: admin/admin_dashboard.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$errors = [];
$success = "";

$query = "SELECT full_name, email, phone FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("User query failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: logout.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);

    if (empty($full_name)) {
        $errors[] = "Full name is required.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($errors)) {
        $full_name = mysqli_real_escape_string($conn, $full_name);
        $phone = mysqli_real_escape_string($conn, $phone);

        $update_query = "UPDATE users 
                         SET full_name = '$full_name', phone = '$phone'
                         WHERE user_id = $user_id";

        if (mysqli_query($conn, $update_query)) {
            $_SESSION["full_name"] = $full_name;
            $success = "Profile updated successfully.";

            $query = "SELECT full_name, email, phone FROM users WHERE user_id = $user_id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $user = mysqli_fetch_assoc($result);
            }
        } else {
            $errors[] = "Error updating profile: " . mysqli_error($conn);
        }
    }
}

include("includes/header.php");
?>

<section class="edit-profile-page">
    <div class="edit-profile-header-card">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user["full_name"], 0, 1)); ?>
        </div>

        <div>
            <p class="profile-tag">Account Settings</p>
            <h1>Update Profile</h1>
            <p>Keep your account information updated for faster checkout and order records.</p>
        </div>

        <div class="profile-hero-actions">
            <a href="profile.php" class="admin-outline-btn">Back to Profile</a>
            <a href="order_history.php" class="admin-primary-btn">Order History</a>
        </div>
    </div>

    <div class="edit-profile-grid">
        <div class="edit-profile-form-card">
            <h2>Basic Information</h2>

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

            <form method="POST" class="modern-profile-form">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" placeholder="Full Name"
                           value="<?php echo htmlspecialchars($user["full_name"] ?? ""); ?>">
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Email"
                           value="<?php echo htmlspecialchars($user["email"] ?? ""); ?>" disabled>
                    <small>Email is used as your login and cannot be changed here.</small>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" placeholder="Phone"
                           value="<?php echo htmlspecialchars($user["phone"] ?? ""); ?>">
                </div>

                <button type="submit" class="admin-submit-btn">Save Changes</button>
            </form>
        </div>

        <div class="edit-profile-help-card">
            <h2>Account Shortcuts</h2>

            <div class="profile-action-list">
                <a href="profile.php">View Profile</a>
                <a href="products.php">Continue Shopping</a>
                <a href="cart.php">View Cart</a>
                <a href="order_history.php">View Order History</a>
            </div>

            <div class="profile-summary-box edit-profile-note">
                <span>Reminder</span>
                <p>Your name and phone number may be used later for checkout and order records.</p>
            </div>
        </div>
    </div>
</section>

<?php include("includes/footer.php"); ?>