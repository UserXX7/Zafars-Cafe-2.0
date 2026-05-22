<?php
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

$errors = [];
$success = "";

$name = isset($_SESSION["full_name"]) ? $_SESSION["full_name"] : "";
$email = "";
$subject = "";
$message = "";
$user_id = isset($_SESSION["user_id"]) ? (int) $_SESSION["user_id"] : null;

if ($user_id) {
    $stmt = mysqli_prepare($conn, "SELECT email FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $email = $row["email"];
    }
    mysqli_stmt_close($stmt);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $subject = trim($_POST["subject"]);
    $message = trim($_POST["message"]);

    if ($name === "") {
        $errors[] = "Name is required.";
    }

    if ($email === "") {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    }

    if ($subject === "") {
        $errors[] = "Subject is required.";
    }

    if ($message === "") {
        $errors[] = "Message is required.";
    }

    if (empty($errors)) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO feedback (user_id, name, email, subject, message) VALUES (?, ?, ?, ?, ?)"
        );
        mysqli_stmt_bind_param($stmt, "issss", $user_id, $name, $email, $subject, $message);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Thank you. Your feedback has been submitted.";
            $subject = "";
            $message = "";
        } else {
            $errors[] = "Something went wrong while saving your feedback.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<div class="page-title">
    <h1>Feedback</h1>
    <p>Tell us what went well or what we can improve.</p>
</div>

<div class="form-container wide-form">
    <?php if ($success !== ""): ?>
        <p class="success-message"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <?php foreach ($errors as $error): ?>
        <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <input type="text" name="name" placeholder="Your Name" value="<?php echo htmlspecialchars($name); ?>">
        <input type="email" name="email" placeholder="Your Email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="text" name="subject" placeholder="Subject" value="<?php echo htmlspecialchars($subject); ?>">
        <textarea name="message" placeholder="Write your feedback here..."><?php echo htmlspecialchars($message); ?></textarea>
        <button type="submit">Submit Feedback</button>
    </form>
</div>

<?php include("includes/footer.php"); ?>
