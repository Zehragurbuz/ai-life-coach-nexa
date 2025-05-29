<?php
session_start();
include '../projeeek/db.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Beslenme verilerini en yeniye göre getir
$result = $conn->query("SELECT * FROM nutrition ORDER BY date DESC");
$meals = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nutrition</title>
  <link rel="stylesheet" href="../css_styles/sidebar.css">
  <link rel="stylesheet" href="../css_styles/nutrition.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
</head>
<body>

<?php include '../sidebar.php'; ?>

<div class="ask-ocean-button" onclick="toggleChat()"></div>
<div class="main-content">
  <h2>🍎 Your Daily Nutrition</h2>

  <!-- 🌿 Daily Suggestions + 🔥 Calorie Goal -->
  <div class="nutrition-header">
    <div class="suggestion-box">
      <h4>🌱 Daily Suggestions</h4>
      <ul>
        <li>Eat more fiber-rich foods</li>
        <li>Drink at least 2L water</li>
        <li>Use healthy fats</li>
      </ul>
    </div>

    <div class="calorie-goal-box">
      <h4>🔥 Calorie Goal</h4>
      <p>Calories: <strong>1800 / 2000</strong></p>
    </div>
  </div>

  <!-- 🍽️ Meal Cards -->
<div class="nutrition-cards no-image">
  <?php foreach ($meals as $meal): ?>
    <div class="nutrition-card">
      <div class="nutrition-content">
        <span class="meal-date"><?= date("F j, Y", strtotime($meal['date'])) ?></span>
        <h3><?= htmlspecialchars($meal['meal_name']) ?></h3>
        <p><?= htmlspecialchars($meal['description']) ?></p>
        <div class="macros">
          <span>🍽 <?= $meal['calories'] ?> kcal</span>
          <span>💪 <?= $meal['protein'] ?>g protein</span>
          <span>🥑 <?= $meal['fat'] ?>g fat</span>
          <span>🍞 <?= $meal['carbs'] ?>g carbs</span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- 🧠 Ocean Chat -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Need help with your meals?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Ask something...">
    <button onclick="sendMessage()">➤</button>
  </div>
</div>

<script src="../js/chat.js"></script>
</body>
</html>
