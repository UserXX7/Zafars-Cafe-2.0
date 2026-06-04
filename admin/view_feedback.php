<?php
include("../includes/header.php");
require_once("../includes/db.php");

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

$total_feedback_query = "SELECT COUNT(*) AS total FROM feedback";
$total_feedback_result = mysqli_query($conn, $total_feedback_query);
$total_feedback_row = mysqli_fetch_assoc($total_feedback_result);
$total_feedback = $total_feedback_row["total"] ?? 0;

$new_feedback_query = "SELECT COUNT(*) AS total FROM feedback WHERE status = 'new'";
$new_feedback_result = mysqli_query($conn, $new_feedback_query);
$new_feedback_row = mysqli_fetch_assoc($new_feedback_result);
$new_feedback = $new_feedback_row["total"] ?? 0;

$feedback_query = "
    SELECT 
        feedback_id,
        user_id,
        full_name,
        email,
        phone,
        feedback_type,
        rating,
        order_id,
        subject,
        message,
        status,
        admin_notes,
        reviewed_at,
        submitted_at
    FROM feedback
    ORDER BY submitted_at DESC
";

$feedback_result = mysqli_query($conn, $feedback_query);

if (!$feedback_result) {
    die("Feedback query failed: " . mysqli_error($conn));
}
?>

<main class="admin-feedback-container">

    <section class="admin-feedback-hero">
        <div>
            <span class="admin-global-badge">ADMIN FEEDBACK CENTER</span>
            <h1>Customer Feedback</h1>
            <p>Review customer suggestions, website issues, order concerns, and service feedback.</p>
        </div>

        <div class="admin-hero-actions">
            <a href="admin_dashboard.php" class="admin-outline-btn">Back to Dashboard</a>
            <a href="../feedback.php" class="admin-outline-btn">Open Feedback Form</a>
        </div>
    </section>

    <section class="admin-feedback-summary">
        <div class="admin-stat-card">
            <span>Total Feedback Messages</span>
            <h2><?php echo $total_feedback; ?></h2>
            <p>All customer submissions</p>
        </div>

        <div class="admin-stat-card warning">
            <span>New Messages</span>
            <h2><?php echo $new_feedback; ?></h2>
            <p>Need admin review</p>
        </div>
    </section>

    <section class="admin-feedback-list">

        <?php if (mysqli_num_rows($feedback_result) > 0): ?>
            <?php while ($feedback = mysqli_fetch_assoc($feedback_result)): ?>

                <article class="admin-feedback-card">
                    <div class="admin-feedback-card-top">
                        <div>
                            <h3><?php echo htmlspecialchars($feedback["subject"]); ?></h3>

                            <p class="admin-feedback-meta">
                                From 
                                <strong><?php echo htmlspecialchars($feedback["full_name"]); ?></strong>
                                · <?php echo htmlspecialchars($feedback["email"]); ?>
                            </p>
                        </div>

                        <span class="admin-status-pill <?php echo htmlspecialchars($feedback["status"]); ?>">
                            <?php echo htmlspecialchars(ucfirst($feedback["status"])); ?>
                        </span>
                    </div>

                    <div class="admin-feedback-details">
                        <div>
                            <span>Feedback Type</span>
                            <strong><?php echo htmlspecialchars(ucfirst($feedback["feedback_type"])); ?></strong>
                        </div>

                        <div>
                            <span>Rating</span>
                            <strong>
                                <?php 
                                    echo $feedback["rating"] 
                                        ? htmlspecialchars($feedback["rating"]) . " / 5" 
                                        : "Not rated"; 
                                ?>
                            </strong>
                        </div>

                        <div>
                            <span>Order ID</span>
                            <strong>
                                <?php 
                                    echo $feedback["order_id"] 
                                        ? "#" . htmlspecialchars($feedback["order_id"]) 
                                        : "N/A"; 
                                ?>
                            </strong>
                        </div>

                        <div>
                            <span>Phone</span>
                            <strong>
                                <?php 
                                    echo !empty($feedback["phone"]) 
                                        ? htmlspecialchars($feedback["phone"]) 
                                        : "N/A"; 
                                ?>
                            </strong>
                        </div>

                        <div>
                            <span>Submitted</span>
                            <strong><?php echo htmlspecialchars($feedback["submitted_at"]); ?></strong>
                        </div>
                    </div>

                    <div class="admin-feedback-message">
                        <span>Message</span>
                        <p><?php echo nl2br(htmlspecialchars($feedback["message"])); ?></p>
                    </div>

                    <?php if (!empty($feedback["admin_notes"])): ?>
                        <div class="admin-feedback-notes">
                            <span>Admin Notes</span>
                            <p><?php echo nl2br(htmlspecialchars($feedback["admin_notes"])); ?></p>
                        </div>
                    <?php endif; ?>

                </article>

            <?php endwhile; ?>
        <?php else: ?>

            <div class="admin-empty-feedback">
                <h3>No feedback yet</h3>
                <p>Customer feedback submissions will appear here after users submit the form.</p>
            </div>

        <?php endif; ?>

    </section>

</main>

<?php include("../includes/footer.php"); ?>