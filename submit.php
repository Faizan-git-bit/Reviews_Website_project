
       <label for="email">email</label>
      <input type="email" id="email" name="email" required>
<?php
session_start();
include 'includes/config.php';

// Redirect if user not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_email = $_SESSION['user'];
    $title = trim($_POST['title'] ?? '');
    $rating = intval($_POST['rating'] ?? 0);
    $body = trim($_POST['body'] ?? '');

    if (strlen($title) < 5 || strlen($body) < 20 || $rating < 1 || $rating > 5) {
        $message = "Validation failed. Title must be at least 5 characters, body at least 20 characters, and rating between 1 and 5.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reviews (user_email, title, rating, body, submitted_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssis", $user_email, $title, $rating, $body);

        if ($stmt->execute()) {
            header("Location: dashboard.php?submitted=1");
            exit();
        } else {
            $message = "Failed to submit review: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Submit Review - ReviewSphere</title>
  <link rel="stylesheet" href="css/submit.css">
</head>
<body>
  <?php require('includes/nav.php'); ?>

  <header class="page-header">
    <h1>Write a Review</h1>
    <p>Share your thoughts and help others make better choices.</p>
  </header>

  <main class="form-container">
    <?php if (!empty($message)): ?>
      <div id="formMessage" style="color: red;"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form id="reviewForm" action="submit.php" method="POST">
       <label for="email">email</label>
      <input type="email" id="email" name="email" required>
      
      <label for="reviewTitle">Title</label>
      <input type="text" id="reviewTitle" name="title" required>

      <label for="rating">Rating</label>
      <select id="rating" name="rating" required>
        <option value="">-- Select Rating --</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
      </select>

      <label for="reviewBody">Review</label>
      <textarea id="reviewBody" name="body" rows="6" required></textarea>

      <button type="submit">Submit Review</button>
    </form>
  </main>

  <?php require('includes/footer.php'); ?>
</body>
</html>
