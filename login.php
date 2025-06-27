<?php
session_start();
include 'includes/config.php';

$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $sql = "SELECT * FROM users WHERE LOWER(email) = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['email'];
                header("Location: category.php");
                exit();
            } else {
                $loginError = "Invalid password.";
            }
        } else {
            $loginError = "No user found with this email.";
        }

        $stmt->close();
    } else {
        $loginError = "Please enter email and password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - ReviewSphere</title>
  <link rel="stylesheet" href="css/login.css" />
</head>
<body>
  <div class="login-container">
    <h2>Login to ReviewSphere</h2>
    <?php if (!empty($loginError)) echo "<p style='color:red;'>$loginError</p>"; ?>
    <form id="loginForm" action="login.php" method="POST">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit">Login</button>
    </form>
    <p class="footer-text">Don't have an account? <a href="signup.php">Sign up here</a></p>
  </div>
</body>
</html>
