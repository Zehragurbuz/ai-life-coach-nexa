<?php
session_start();
include '../projeeek/db.php';

// Sadece admin erişebilsin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title && $content) {
        $stmt = $conn->prepare("INSERT INTO notifications (user_id, title, content, is_read) VALUES (NULL, ?, ?, 0)");
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();

        $success = "✅ Notification successfully sent to all users.";
    } else {
        $error = "⚠️ Please fill in both the title and the content.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Notification</title>
    <link rel="stylesheet" href="../css_styles/sidebar1.css">
    <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
    <style>
        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        h2 {
            font-size: 26px;
            margin-bottom: 20px;
        }

        form {
            max-width: 600px;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        input, textarea {
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 15px;
            width: 100%;
        }

        button {
            padding: 10px 16px;
            background-color: #2196f3;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            font-size: 15px;
        }

        button:hover {
            background-color: #1976d2;
        }

        .message {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .success {
            color: green;
        }

        .error {
            color: #d32f2f;
        }
    </style>
</head>
<body>

<?php include '../sidebar1.php'; ?>

<div class="main-content">
    <h2>📢 Send Notification to All Users</h2>

    <?php if ($success): ?>
        <div class="message success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="message error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Notification title..." required>
        <textarea name="content" rows="5" placeholder="Notification content..." required></textarea>
        <button type="submit">Send Notification</button>
    </form>
</div>

</body>
</html>
