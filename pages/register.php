
<?php
include '../projeeek/db.php';
include '../projeeek/hash.php';

$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $gender = $_POST['gender'] ?? 'other';
    $birthdate = $_POST['birthdate'] ?? null;
    $weight = $_POST['weight'] ?? null;
    $height = $_POST['height'] ?? null;

    // Şifreyi hashle
    $hashedPassword = hashPassword($password);

    $stmt = $conn->prepare("INSERT INTO users (email, password, full_name, phone_number, gender, birthdate, weight, height)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssssssss", $email, $hashedPassword, $full_name, $phone_number, $gender, $birthdate, $weight, $height);

        if ($stmt->execute()) {
            header("Location: login.php"); // Kayıt başarılıysa login sayfasına yönlendir
            exit();
        } else {
            $register_error = "Kayıt başarısız: " . $stmt->error;
        }
    } else {
        $register_error = "Veritabanı hatası: " . $conn->error;
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - NEXA</title>
  <link rel="stylesheet" href="../css_styles/register.css">
</head>
<body>

  <div class="register-container">

    <!-- Sağ üst köşe logo -->
    <div class="top-logo">
      <img src="../images/logo.png" alt="NEXA Logo">
    </div>

    <div class="left-section">
      <img src="../images/koltuk.png" alt="Illustration" class="illustration">
    </div>

    <div class="right-section">
      <div class="form-box">
        <h2>Register</h2>
        <form action="register.php" method="POST">

          <label>Full Name</label>
          <input type="text" name="full_name" required>

          <label>Email</label>
          <input type="email" name="email" required>

          <label>Password</label>
          <input type="password" name="password" required>

          <label>Phone Number</label>
          <input type="tel" name="phone_number" placeholder="+90 555 555 55 55">

          <label>Gender</label>
          <select name="gender">
            <option value="female">Female</option>
            <option value="male">Male</option>
            <option value="other">Other</option>
          </select>

          <label>Birthdate</label>
          <input type="date" name="birthdate">

          <label>Weight (kg)</label>
          <input type="number" name="weight">

          <label>Height (cm)</label>
          <input type="number" name="height">

          <button type="submit">Register</button>
        </form>

        <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
      </div>
    </div>

  </div>

</body>
</html>
