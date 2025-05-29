<?php
session_start();
include __DIR__ . '/../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Form verilerini al ve temizle
$full_name    = $_POST['full_name'] ?? '';
$email        = $_POST['email'] ?? '';
$birthdate    = $_POST['birthdate'] ?? null;
$weight       = $_POST['weight'] ?? null;
$height       = $_POST['height'] ?? null;
$goal_weight  = $_POST['goal_weight'] ?? null;
$chest        = $_POST['chest'] ?? null;
$waist        = $_POST['waist'] ?? null;
$hip          = $_POST['hip'] ?? null;

// SQL sorgusu
$stmt = $conn->prepare("
    UPDATE users 
    SET full_name = ?, email = ?, birthdate = ?, weight = ?, height = ?, goal_weight = ?, chest = ?, waist = ?, hip = ?
    WHERE id = ?
");

$stmt->bind_param(
    "sssddddddi",
    $full_name,
    $email,
    $birthdate,
    $weight,
    $height,
    $goal_weight,
    $chest,
    $waist,
    $hip,
    $user_id
);

if ($stmt->execute()) {
    header("Location: profile.php?update=success");
    exit();
} else {
    echo "❌ Update failed: " . $conn->error;
}
?>
