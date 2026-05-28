<?php
include("../includes/admin_auth.php");
include("../includes/db.php");

$query = "SELECT * FROM feedback ORDER BY submitted_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Feedback | Zafar's Cafe</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="form-container" style="max-width: 1000px;">
    <h2>Customer Feedback</h2>

    <div style="text-align:center; margin-bottom:20px;">
        <a href="admin_dashboard.php">Back to Dashboard</a> |
        <a href="manage_products.php">Manage Products</a> |
        <a href="../logout.php">Logout</a>
    </div>

    <?php if (mysqli_num_rows($result) > 0) { ?>

        <table border="1" cellpadding="10" cellspacing="0" style="width:100%; border-collapse:collapse; text-align:center;">
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
                    <td><?php echo $row["feedback_id"]; ?></td>
                    <td><?php echo $row["user_id"]; ?></td>
                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["subject"]); ?></td>
                    <td><?php echo htmlspecialchars($row["message"]); ?></td>
                    <td><?php echo $row["submitted_at"]; ?></td>
                </tr>
            <?php } ?>
        </table>

    <?php } else { ?>

        <p style="text-align:center;">No feedback messages found.</p>

    <?php } ?>
</div>

</body>
</html>