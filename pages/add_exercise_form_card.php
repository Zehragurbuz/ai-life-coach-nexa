<?php
session_start();
include '../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $type = 'exercise';
  $title = $_POST['title'];
  $description = $_POST['description'];
  $date = $_POST['date'];

  $stmt = $conn->prepare("INSERT INTO activities (user_id, type, title, description, date) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $type, $title, $description, $date);

  if ($stmt->execute()) {
    header("Location: exercise.php?success=1");
    exit();
  } else {
    echo "Hata: " . $stmt->error;
  }
}
?>
