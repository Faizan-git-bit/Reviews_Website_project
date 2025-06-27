<?php
include 'includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashedPassword);

    if ($stmt->execute()) {
         header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up - ReviewSphere</title>
  <link rel="stylesheet" href="css/signup.css" />
</head>
<body>

  <div class="signup-container">
   <form id="signupForm" class="signup-form" method="POST" action="signup.php">
  <h2>Create Your ReviewSphere Account</h2>

  <div class="form-group">
    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" required />
    <span class="error" id="nameError"></span>
  </div>

  <div class="form-group">
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" required />
    <span class="error" id="emailError"></span>
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required minlength="6" />
    <span class="error" id="passwordError"></span>
  </div>

  <div class="form-group">
    <label for="confirmPassword">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" required />
    <span class="error" id="confirmPasswordError"></span>
  </div>

  <button type="submit">Sign Up</button>
  <p class="footer-text">Already have an account? <a href="login.php">Login here</a></p>
</form>

  </div>

  <script>
    const signupForm = document.getElementById("signupForm");

    signupForm.addEventListener("submit", function (e) {
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const confirmPassword = document.getElementById("confirmPassword").value.trim();

      let valid = true;

      // Clear previous errors
      document.getElementById("nameError").textContent = "";
      document.getElementById("emailError").textContent = "";
      document.getElementById("passwordError").textContent = "";
      document.getElementById("confirmPasswordError").textContent = "";

      // Name validation
      if (name.length < 2) {
        document.getElementById("nameError").textContent = "Please enter your full name.";
        valid = false;
      }

      // Email validation
      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      if (!emailPattern.test(email)) {
        document.getElementById("emailError").textContent = "Enter a valid email address.";
        valid = false;
      }

      // Password validation
      if (password.length < 6) {
        document.getElementById("passwordError").textContent = "Password must be at least 6 characters.";
        valid = false;
      }

      // Confirm password match
      if (password !== confirmPassword) {
        document.getElementById("confirmPasswordError").textContent = "Passwords do not match.";
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
      }
    });
  </script>

</body>
</html>
