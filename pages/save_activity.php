<?php
include '../projeeek/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $level = $_POST['level'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $date = $_POST['date'] ?? '';
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO activities (user_id, date, title, description, level, duration) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $date, $title, $description, $level, $duration);

    if ($stmt->execute()) {
        header("Location: add_activity.php");
        exit();
    } else {
        echo "Hata: " . $stmt->error;
    }
}
?>
