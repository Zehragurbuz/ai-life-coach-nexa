<?php
session_start();
include __DIR__ . '/../projeeek/db.php';

$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $conn->prepare("SELECT full_name, email, birthdate, weight, height, gender, goal_weight, chest, waist, hip FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("❌ User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile Settings</title>
  <link rel="stylesheet" href="../css_styles/settings.css">
  <link rel="stylesheet" href="../css_styles/sidebar.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
</head>
<body>

<?php include '../sidebar.php'; ?>

<!-- ✅ Ask Ocean Button -->
<div class="ask-ocean-button" onclick="toggleChat()">
  <img src="../images/ocean-icon.png" alt="Ask Ocean">
  <span>Ask <strong>OCEAN</strong></span>
</div>

<div class="main-content">
  <div class="settings-container">
    <h2>⚙️ Profile Settings</h2>

    <form action="update_settings.php" method="post">
      <label>Full Name:
        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
      </label>

      <label>Email:
        <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
      </label>

      <label>Birthdate:
        <input type="date" name="birthdate" value="<?= $user['birthdate'] ?? '' ?>">
      </label>

      <label>Weight (kg):
        <input type="number" name="weight" value="<?= $user['weight'] ?? '' ?>">
      </label>

      <label>Height (cm):
        <input type="number" name="height" value="<?= $user['height'] ?? '' ?>">
      </label>

      <label>Goal Weight (kg):
        <input type="number" name="goal_weight" value="<?= $user['goal_weight'] ?? '' ?>">
      </label>

      <label>Chest (cm):
        <input type="number" name="chest" value="<?= $user['chest'] ?? '' ?>">
      </label>

      <label>Waist (cm):
        <input type="number" name="waist" value="<?= $user['waist'] ?? '' ?>">
      </label>

      <label>Hip (cm):
        <input type="number" name="hip" value="<?= $user['hip'] ?? '' ?>">
      </label>

      <button type="submit">💾 Update Changes</button>
    </form>
  </div>
</div>

<!-- ✅ Ask OCEAN Chat Panel -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Hello! How can I assist you today?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Type your question..." />
    <button onclick="sendMessage()">➤</button>
  </div>
</div>

<script src="../js/chat.js"></script>
<script>
function toggleChat() {
  document.getElementById("chatPanel").classList.toggle("open");
}
</script>

</body>
</html>
