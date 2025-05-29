<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>404 - Page Not Found</title>
  <style>
    body {
      background-color: #f2f6fa;
      font-family: 'Poppins', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      flex-direction: column;
      text-align: center;
      color: #1c5b8a;
    }

    .error-container {
      max-width: 500px;
    }
.error-logo {
  width: 120px;         /* Logo genişliği */
  height: auto;         /* Oranı koru */
  max-width: 100%;      /* Taşmayı engelle */
  margin-bottom: 20px;
  cursor: pointer;
  display: block;
  margin-left: auto;
  margin-right: auto;
}



    .error-code {
      font-size: 100px;
      font-weight: bold;
    }

    .error-message {
      font-size: 24px;
      margin-bottom: 10px;
    }

    .slogan {
      font-size: 16px;
      color: #5a7ca4;
      margin-bottom: 30px;
    }

    .back-button {
      padding: 10px 20px;
      background-color: #1c5b8a;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: #15507c;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <a href="login.php">
<img src="<?php echo '../images/logo.png'; ?>"  alt="NEXA Logo" class="error-logo" />
    </a>
    <div class="error-code">404</div>
    <div class="error-message">Page Not Found</div>
    <div class="slogan">NEXA — Next Generation AI Coache</div>
    <a href="login.php" class="back-button">🔙 Back to Login</a>
  </div>
</body>
</html>
