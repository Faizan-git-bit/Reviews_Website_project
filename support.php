<?php
// submit_support.php

include 'includes/config.php'; // 🔁 Ensure this contains your database connection

// Validate form input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "All fields are required.";
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO support_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Your message has been submitted. We will contact you soon.'); window.location.href='support.php';</script>";
    } else {
        echo "Failed to submit message. Please try again.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customer Support - ReviewSphere</title>
  <link rel="stylesheet" href="css/support.css" />
</head>
<body>

  <?php  require('includes/nav.php'); ?>

  <!-- SUPPORT SECTION -->
  <main class="support-section">
    <div class="container">
      <h1>Customer Support</h1>
      <p>Need help? We're here to assist you with any issues, inquiries, or feedback.</p>

      <div class="support-grid">
        <div class="support-info">
          <h2>Contact Information</h2>
          <p>Email: <a href="mailto:support@reviewsphere.com">support@reviewsphere.com</a></p>
          <p>Phone: +1 (800) 123-4567</p>
          <p>Support Hours: Mon - Fri, 9:00 AM - 6:00 PM (GMT)</p>
        </div>

        <div class="support-form">
          <h2>Send Us a Message</h2>
          <form id="supportForm" method="POST" action="support.php">

            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required />

            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" required />

            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required />

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <button type="submit">Submit</button>
          </form>
          <p id="formMessage"></p>
        </div>
      </div>
    </div>
  </main>

 <?php  require('includes/footer.php'); ?>

  <!-- SCRIPT 
  <script>
    document.getElementById('supportForm').addEventListener('submit', function (e) {
      e.preventDefault();
      document.getElementById('formMessage').textContent = "Thanks for reaching out! We'll get back to you shortly.";
      this.reset();
    });
  </script>-->
</body>
</html>
