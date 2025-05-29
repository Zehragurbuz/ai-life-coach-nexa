<?php
session_start();
include __DIR__ . '/../projeeek/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = (int)$_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM goals WHERE user_id IS NULL OR user_id = ? ORDER BY start_date DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$goals = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Goal Tracker</title>
    <link rel="stylesheet" href="../css_styles/sidebar.css">
    <link rel="stylesheet" href="../css_styles/goal_tracking.css">
    <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
</head>
<body>

<?php include '../sidebar.php'; ?>

<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
    <h2>🎯 Goal Tracking Panel</h2>

    <div class="goal-cards">
        <?php if (count($goals) > 0): ?>
            <?php foreach ($goals as $index => $goal): ?>
                <div class="goal-card" onclick="toggleDetails(this)">
                    <h3><?= htmlspecialchars($goal['goal_type']) ?></h3>
                    <p><strong>Duration:</strong> <?= (int) $goal['target_value'] ?> minutes</p>

                    <div class="goal-details">
                        <p><strong>Description:</strong> <?= htmlspecialchars($goal['details'] ?? 'No description.') ?></p>

                        <?php if (is_null($goal['user_id'])): ?>
                            <form method="POST" action="accept_goal.php">
                                <input type="hidden" name="goal_type" value="<?= htmlspecialchars($goal['goal_type']) ?>">
                                <input type="hidden" name="target_value" value="<?= (int) $goal['target_value'] ?>">
                                <input type="hidden" name="details" value="<?= htmlspecialchars($goal['details']) ?>">
                                <button type="submit">✅ Accept This Goal</button>
                            </form>
                        <?php else: ?>
                            <div class="timer-buttons">
                                <button type="button" onclick="startTimer(event, <?= (int) $goal['target_value'] ?>, <?= $index ?>)">⏱ Start</button>
                                <button type="button" onclick="stopTimer(<?= $index ?>)">⏹ Stop</button>
                                <form method="POST" action="delete_goal.php" onsubmit="return confirm('Are you sure you want to delete this goal?')">
                                    <input type="hidden" name="goal_id" value="<?= (int)$goal['id'] ?>">
                                    <button type="submit" class="delete-btn">🗑 Delete</button>
                                </form>
                            </div>
                            <div class="timer-container">
                                <div class="timer" id="timer-<?= $index ?>"></div>
                                <div class="timer-bar" id="bar-<?= $index ?>"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No goals to display yet.</p>
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
        <div class="message ai">Do you need help with goal tracking?</div>
    </div>
    <div class="chat-input">
        <input type="text" id="chatInput" placeholder="Type a question...">
        <button onclick="sendMessage()">➤</button>
    </div>
</div>

<script src="../js/chat.js"></script>

<script>
function toggleDetails(card) {
    const details = card.querySelector('.goal-details');
    details.style.display = details.style.display === 'block' ? 'none' : 'block';
}

let timerIntervals = {};

function startTimer(event, durationMinutes, index) {
    event.stopPropagation();
    const duration = durationMinutes * 60;
    const timerDisplay = document.getElementById(`timer-${index}`);
    const progressBar = document.getElementById(`bar-${index}`);
    const goalCard = event.target.closest('.goal-card');
    let remaining = duration;

    if (timerIntervals[index]) clearInterval(timerIntervals[index]);

    const button = event.target;
    button.disabled = true;
    button.classList.add("running");
    button.textContent = "⏳ In Progress";
    goalCard.classList.add("active");

    timerIntervals[index] = setInterval(() => {
        const minutes = Math.floor(remaining / 60).toString().padStart(2, '0');
        const seconds = (remaining % 60).toString().padStart(2, '0');
        timerDisplay.textContent = `${minutes}:${seconds}`;
        remaining--;

        const progress = 100 - (remaining / duration) * 100;
        progressBar.style.width = `${Math.min(progress, 100)}%`;

        if (remaining < 0) {
            clearInterval(timerIntervals[index]);
            timerDisplay.textContent = "✔ Completed!";
            timerDisplay.classList.add("completed");
            button.textContent = "✔ Completed";
            goalCard.classList.remove("active");
        }
    }, 1000);
}

function stopTimer(index) {
    if (timerIntervals[index]) {
        clearInterval(timerIntervals[index]);
        delete timerIntervals[index];
        alert("⏸ Timer stopped.");
    }
}
</script>

</body>
</html>
