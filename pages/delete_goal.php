<?php
session_start();
include '../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$goalId = $_POST['goal_id'] ?? 0;

// Sadece kullanıcıya ait hedefi silebilsin
$stmt = $conn->prepare("DELETE FROM goals WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $goalId, $userId);
$stmt->execute();

header("Location: goal_tracking.php");
exit();
