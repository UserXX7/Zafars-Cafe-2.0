<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: homepage.php");
    exit();
}

include("includes/db.php");
include("includes/header.php");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS feedback (
    feedback_id int(11) NOT NULL AUTO_INCREMENT,
    user_id int(11) DEFAULT NULL,
    name varchar(100) NOT NULL,
    email varchar(100) NOT NULL,
    subject varchar(150) NOT NULL,
    message text NOT NULL,
    submitted_at timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (feedback_id),
    KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$product_count = 0;
$user_count = 0;
$feedback_count = 0;

$product_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
if ($product_result) {
    $product_count = mysqli_fetch_assoc($product_result)["total"];
}

$user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
if ($user_result) {
    $user_count = mysqli_fetch_assoc($user_result)["total"];
}

$feedback_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM feedback");
if ($feedback_result) {
    $feedback_count = mysqli_fetch_assoc($feedback_result)["total"];
}

$recent_feedback = mysqli_query(
    $conn,
    "SELECT feedback_id, name, email, subject, message, submitted_at
     FROM feedback
     ORDER BY submitted_at DESC
     LIMIT 10"
);
?>

<div class="page-title">
    <h1>Admin Panel</h1>
    <p>Review site activity and customer feedback.</p>
</div>

<section class="admin-summary">
    <div class="admin-stat">
        <span><?php echo (int) $product_count; ?></span>
        <p>Products</p>
    </div>
    <div class="admin-stat">
        <span><?php echo (int) $user_count; ?></span>
        <p>Users</p>
    </div>
    <div class="admin-stat">
        <span><?php echo (int) $feedback_count; ?></span>
        <p>Feedback Messages</p>
    </div>
</section>

<section class="admin-panel">
    <h2>Recent Feedback</h2>

    <?php if ($recent_feedback && mysqli_num_rows($recent_feedback) > 0): ?>
        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($recent_feedback)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["email"]); ?></td>
                            <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                            <td><?php echo htmlspecialchars($row["message"]); ?></td>
                            <td><?php echo htmlspecialchars($row["submitted_at"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="empty-state">No feedback has been submitted yet.</p>
    <?php endif; ?>
</section>

<?php include("includes/footer.php"); ?>
