<?php
session_start();
include '../projeeek/db.php';

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];
        $_SESSION['is_admin'] = $user['is_admin']; // admin bilgisi oturuma yazılıyor

        // 🧭 Yönlendirme: admin mi?
        if ($user['is_admin']) {
          header("Location: ../pages/admin_dashboard.php");
        } else {
          header("Location: ../pages/dashboard.php");
        }
        exit();
      } else {
        $login_error = "Incorrect password.";
      }
    } else {
      $login_error = "User not found.";
    }
  } else {
    $login_error = "Database error: " . $conn->error;
  }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - NEXA</title>
  <link rel="stylesheet" href="../css_styles/login.css">
</head>
<body>

  <div class="login-container">
    
    <div class="left-section">
      <img src="../images/koltuk.png" alt="AI Illustration" class="illustration">
    </div>

    <div class="right-section">
      <div class="login-box">
        <img src="../images/logo.png" alt="NEXA Logo" class="logo">

        <!-- Slogan -->
        <p class="slogan">Next Generation AI Coach</p>

        <h2>Login</h2>
        <p class="subtitle">Glad you're back.!</p>

        <?php
        if (!empty($login_error)) {
          echo "<p class='error-message'>$login_error</p>";
        }
        ?>

        <form action="login.php" method="POST">
          <label>Email</label>
          <input type="email" name="email" placeholder="Value" required>

          <label>Password</label>
          <input type="password" name="password" placeholder="Value" required>

          <button type="submit">Sign In</button>

          <div class="forgot-password">
            <a href="#">Forgot password?</a>
          </div>
        </form>

        <div class="divider"><span>or</span></div>

        <!-- Auth Buttons -->
        <div class="auth-buttons">
          <button class="auth-btn google-btn">
            <a href="../functions/google-login.php" class="google-btn">
  <img src="../images/google-icon.png" class="icon" alt="Google" />
  Sign in with Google
</a>

          </button>

        </div>

        <div class="tagline">
          <p><strong>Eat Healthy</strong></p>
          <p><strong>Track Your Health</strong></p>
        </div>

        <!-- Register link -->
        <p class="register-link">Don't have an account? <a href="register.php">Create one</a></p>

      </div>
    </div>

  </div>

</body>
</html>
