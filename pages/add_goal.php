<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Hedef Ekle - Admin</title>

  <!-- ✅ Stil dosyaları -->
  <link rel="stylesheet" href="../css_styles/sidebar1.css">
  <link rel="stylesheet" href="../css_styles/add_goal.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- ✅ Admin Sidebar -->
<?php include '../sidebar1.php'; ?>

<!-- ✅ AI Butonu -->
<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<!-- ✅ Ana içerik -->
<div class="main-content">
  <h2>🎯 Yeni Hedef Ekle</h2>

  <form class="goal-form" action="save_goal.php" method="POST">
    <div class="form-group">
      <label>Hedef Başlığı</label>
      <input type="text" name="title" required>
    </div>

    <div class="form-group">
      <label>Açıklama</label>
      <textarea name="description" rows="4" required></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Kategori</label>
        <select name="category" required>
          <option value="Fitness">Fitness</option>
          <option value="Nutrition">Nutrition</option>
          <option value="Mindfulness">Mindfulness</option>
          <option value="Custom">   </option>
        </select>
      </div>

      <div class="form-group">
        <label>Hedef Tarihi</label>
        <input type="date" name="target_date" required>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="save-btn">Ekle</button>
      <button type="button" class="suggest-btn" onclick="generateGoalSuggestion()">💡 OCEAN’dan Öneri Al</button>
    </div>
  </form>

  <!-- 🔺 Grafik Alanı -->
  <h3>Goals Added (Last 7 Days)</h3>
  <canvas id="goalGrowthChart" width="600" height="300"></canvas>
</div>

<!-- ✅ Sohbet Kutusu -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Hedef tanımlamakla ilgili yardıma ihtiyacınız var mı?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Bir soru yaz...">
    <button onclick="sendMessage()">➔</button>
  </div>
</div>

<!-- ✅ JavaScript dosyası -->
<script src="../js/chat.js"></script>
<script>
  function toggleChat() {
    document.getElementById("chatPanel").classList.toggle("open");
  }

  // 🌐 Goal Grafiği
  fetch('../pages/goal_growth_data.php?ts=' + new Date().getTime())
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.day);
      const counts = data.map(item => item.count);

      const ctx = document.getElementById('goalGrowthChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Goals Added',
            data: counts,
            borderColor: '#27ae60',
            backgroundColor: 'rgba(39, 174, 96, 0.2)',
            tension: 0.3,
            fill: true
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: { precision: 0 }
            }
          },
          plugins: {
            legend: { display: true }
          }
        }
      });
    });
</script>

</body>
</html>
