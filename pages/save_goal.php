<?php
session_start();
include __DIR__ . '/../projeeek/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? 'Custom';
    $target_date = $_POST['target_date'] ?? null;

    // 🟢 Tüm kullanıcılar için ortak hedef — user_id NULL olarak gönderilecek
    $default_duration = 30;

    // Doğrudan NULL göndermek için farklı bind yöntemi uygulanır
    $stmt = $conn->prepare("INSERT INTO goals (user_id, goal_type, target_value, start_date, end_date, details) VALUES (NULL, ?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sdsss", $category, $default_duration, $target_date, $target_date, $description);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            header("Location: add_goal.php?status=success");
            exit();
        } else {
            header("Location: add_goal.php?status=fail");
            exit();
        }
    } else {
        die("SQL Hatası: " . $conn->error);
    }
} else {
    header("Location: add_goal.php");
    exit();
}
