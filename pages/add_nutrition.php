<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Nutrition - Admin</title>
  <link rel="stylesheet" href="../css_styles/sidebar1.css">
  <link rel="stylesheet" href="../css_styles/add_nutrition.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../sidebar1.php'; ?>

<!-- Ask Ocean Butonu -->
<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
  <h2>Add New Nutrition Entry</h2>

  <form class="nutrition-form" action="save_nutrition.php" method="POST">
    <div class="form-group">
      <label>Meal Name</label>
      <input type="text" name="meal_name" required>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="3" required></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Calories (kcal)</label>
        <input type="number" name="calories" required>
      </div>

      <div class="form-group">
        <label>Protein (g)</label>
        <input type="number" name="protein" required>
      </div>

      <div class="form-group">
        <label>Carbs (g)</label>
        <input type="number" name="carbs" required>
      </div>

      <div class="form-group">
        <label>Fat (g)</label>
        <input type="number" name="fat" required>
      </div>
    </div>

    <div class="form-group">
      <label>Date</label>
      <input type="date" name="date" required>
    </div>

    <div class="form-actions">
      <button type="submit" class="save-btn">Add Nutrition</button>
      <button type="button" class="suggest-btn" onclick="generateNutritionSuggestion()">💡 OCEAN’dan Öneri Al</button>
    </div>
  </form>

  <!-- Nutrition Chart -->
  <h3 style="margin-top: 40px">Nutrition Entries (Last 7 Days)</h3>
  <canvas id="nutritionChart" width="600" height="300"></canvas>
</div>

<!-- Ask Ocean Chat Paneli -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Beslenme planı eklerken dikkat etmen gerekenler hakkında yardım ister misin?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Bir soru yaz...">
    <button onclick="sendMessage()">➞</button>
  </div>
</div>

<!-- JavaScript -->
<script src="../js/chat.js"></script>
<script>
function toggleChat() {
  document.getElementById("chatPanel").classList.toggle("open");
}

fetch('../pages/nutrition_growth_data.php?ts=' + new Date().getTime())
  .then(res => res.json())
  .then(data => {
    const labels = data.map(d => d.day);
    const values = data.map(d => d.count);

    const ctx = document.getElementById('nutritionChart').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Entries',
          data: values,
          backgroundColor: 'rgba(255, 159, 64, 0.6)'
        }]
      },
      options: {
        scales: {
          y: { beginAtZero: true }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });
  });
</script>

</body>
</html>
