<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'includes/config.php';
$user_email = $_SESSION['user'];

$sql = "SELECT title, rating, body, submitted_at FROM reviews WHERE user_email = ? ORDER BY submitted_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard - ReviewSphere</title>
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
  <?php require('includes/nav.php'); ?>

  <header class="page-header">
    <h1>Welcome, Reviewer!</h1>
    <p>Here are your submitted reviews.</p>
  </header>

  <main class="dashboard">
    <h2>Your Reviews</h2>
    <div class="review-list">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="review-card">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p class="rating">Rating: <?= str_repeat("⭐", $row['rating']) ?></p>
            <p><?= nl2br(htmlspecialchars($row['body'])) ?></p>
            <small>Submitted on: <?= htmlspecialchars($row['submitted_at']) ?></small>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>No reviews submitted yet.</p>
      <?php endif; ?>
    </div>
  </main>

  <?php require('includes/footer.php'); ?>
</body>
</html>
