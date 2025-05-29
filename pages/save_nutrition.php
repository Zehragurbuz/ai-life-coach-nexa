<?php
include '../projeeek/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meal_name   = $_POST['meal_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $calories    = $_POST['calories'] ?? 0;
    $protein     = $_POST['protein'] ?? 0;
    $carbs       = $_POST['carbs'] ?? 0;
    $fat         = $_POST['fat'] ?? 0;
    $date        = $_POST['date'] ?? date('Y-m-d');
    $user_id     = $_SESSION['user_id']; // Admin kullanıcı ID'si

    $stmt = $conn->prepare("INSERT INTO nutrition (user_id, meal_name, description, calories, protein, carbs, fat, date)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("issdddss", $user_id, $meal_name, $description, $calories, $protein, $carbs, $fat, $date);

        if ($stmt->execute()) {
            header("Location: add_nutrition.php?status=success");
            exit();
        } else {
            echo "Veri kaydedilemedi: " . $stmt->error;
        }
    } else {
        echo "Hazırlama hatası: " . $conn->error;
    }
} else {
    header("Location: add_nutrition.php");
    exit();
}
?>
