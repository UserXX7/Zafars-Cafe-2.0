<?php
require_once("../includes/admin_auth.php");
require_once("../includes/db.php");

/** @var mysqli $conn */
if (!isset($conn) || !$conn) {
    die("Database connection failed.");
}

function getCount(mysqli $conn, string $query, string $columnName): int {
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return isset($row[$columnName]) ? (int)$row[$columnName] : 0;
    }

    return 0;
}

$query = "SELECT * FROM feedback ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Feedback query failed: " . mysqli_error($conn));
}

$feedback_count = getCount(
    $conn,
    "SELECT COUNT(*) AS total_feedback FROM feedback",
    "total_feedback"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedback | Zafar's Cafe & Convenience</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="admin-feedback-container">

    <div class="admin-page-header">
        <div>
            <p class="admin-small-title">Customer Messages</p>
            <h1>Customer Feedback</h1>
            <p>Review customer questions, concerns, and contact form submissions.</p>
        </div>

        <div class="admin-header-actions">
            <a href="admin_dashboard.php" class="admin-outline-btn">Dashboard</a>
            <a href="manage_products.php" class="admin-outline-btn">Products</a>
            <a href="../logout.php" class="admin-danger-btn">Logout</a>
        </div>
    </div>

    <div class="admin-feedback-summary">
        <div class="admin-stat-card">
            <span>Total Feedback Messages</span>
            <h2><?php echo $feedback_count; ?></h2>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <div class="table-scroll">
            <table class="admin-table">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["feedback_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["user_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["email"]); ?></td>
                        <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                        <td class="feedback-message-cell">
                            <?php echo htmlspecialchars($row["message"]); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row["submitted_at"]); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    <?php } else { ?>

        <div class="empty-state-card">
            <h2>No feedback messages found.</h2>
            <p>Customer messages submitted from the website will appear here.</p>
            <a href="admin_dashboard.php" class="admin-outline-btn">Back to Dashboard</a>
        </div>

    <?php } ?>

</div>

</body>
</html>