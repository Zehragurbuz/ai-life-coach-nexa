<?php
session_start();
include '../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = $_SESSION['user_id'];
  $goal_type = $_POST['goal_type'];
  $target_value = $_POST['target_value'];
  $details = $_POST['details'];
  $start_date = date('Y-m-d');
  $end_date = date('Y-m-d', strtotime('+7 days'));

  $stmt = $conn->prepare("
    INSERT INTO goals (user_id, goal_type, target_value, start_date, end_date, details)
    VALUES (?, ?, ?, ?, ?, ?)
  ");
  $stmt->bind_param("isdsss", $user_id, $goal_type, $target_value, $start_date, $end_date, $details);

  if ($stmt->execute()) {
    header("Location: goal_tracking.php?success=1");
    exit();
  } else {
    echo "Kayıt hatası: " . $stmt->error;
  }
}
?>
