<?php
session_start();
include '../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];

// Bildirimleri al
$stmt = $conn->prepare("SELECT * FROM notifications WHERE user_id IS NULL OR user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);

// Son admin mesajını al
$msgResult = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 1");
$adminMessage = $msgResult && $msgResult->num_rows > 0 ? $msgResult->fetch_assoc() : null;

// Tüm bildirimleri okundu yap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_all_read'])) {
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id IS NULL OR user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}

// Tek bildirim okundu yap
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_read'])) {
    $notifId = (int)$_POST['notification_id'];
    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND (user_id = ? OR user_id IS NULL)");
    $stmt->bind_param("ii", $notifId, $userId);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}

// Sil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_notification'])) {
    $notifId = (int)$_POST['notification_id'];
    $stmt = $conn->prepare("DELETE FROM notifications WHERE id = ? AND (user_id = ? OR user_id IS NULL)");
    $stmt->bind_param("ii", $notifId, $userId);
    $stmt->execute();
    header("Location: notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <link rel="stylesheet" href="../css_styles/sidebar.css">
    <link rel="stylesheet" href="../css_styles/notification.css">
    <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
</head>
<body>

<?php include '../sidebar.php'; ?>

<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
    <h2>🔔 Notifications</h2>

    <form method="POST" style="margin-bottom: 20px;">
        <button type="submit" name="mark_all_read" class="mark-read-btn">✅ Mark All as Read</button>
    </form>

    <div class="notification-cards">

        <?php if ($adminMessage): ?>
            <div class="notification-card unread">
                <div class="notification-content">
                    <h3>📬 Message from Admin</h3>
                    <p><?= htmlspecialchars($adminMessage['content']) ?></p>
                    <span class="timestamp"><?= date("F j, Y H:i", strtotime($adminMessage['created_at'])) ?></span>
                </div>
            </div>
        <?php endif; ?>

        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notification-card <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="notification-content">
                        <h3><?= htmlspecialchars($notif['title']) ?></h3>
                        <p><?= htmlspecialchars($notif['content']) ?></p>
                        <span class="timestamp"><?= date("F j, Y H:i", strtotime($notif['created_at'])) ?></span>
                    </div>

                    <div class="notification-actions">
                        <?php if (!$notif['is_read']): ?>
                            <form method="POST" class="mark-form">
                                <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                                <button type="submit" name="mark_read" class="mark-btn">✅ Mark as Read</button>
                            </form>
                        <?php endif; ?>

                        <form method="POST" class="delete-form" onsubmit="return confirm('Delete this notification?')">
                            <input type="hidden" name="notification_id" value="<?= $notif['id'] ?>">
                            <button type="submit" name="delete_notification" class="delete-btn">🗑</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No notifications yet.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Ocean Chat -->
<div class="chat-container" id="chatPanel">
    <div class="chat-header">
        <h3>Ask OCEAN</h3>
        <button onclick="toggleChat()">✕</button>
    </div>
    <div class="chat-messages" id="chatMessages">
        <div class="message ai">Need help managing your notifications?</div>
    </div>
    <div class="chat-input">
        <input type="text" id="chatInput" placeholder="Type your question...">
        <button onclick="sendMessage()">➤</button>
    </div>
</div>

<script src="../js/chat.js"></script>
</body>
</html>
