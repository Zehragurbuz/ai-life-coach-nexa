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
  <title>Add Activity - Admin</title>
  <link rel="stylesheet" href="../css_styles/sidebar1.css">
  <link rel="stylesheet" href="../css_styles/add_activity.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Chart.js -->
</head>
<body>

<?php include '../sidebar1.php'; ?>

<!-- Ask Ocean Butonu -->
<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
  <h2>Add New Activity</h2>

  <form class="activity-form" action="save_activity.php" method="POST">
    <div class="form-group">
      <label>Activity Title</label>
      <input type="text" name="title" required>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea name="description" rows="4" required></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Level</label>
        <select name="level" required>
          <option value="Beginner">Beginner</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
        </select>
      </div>

      <div class="form-group">
        <label>Duration (min)</label>
        <input type="number" name="duration" min="5" required>
      </div>

      <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" required>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="save-btn">Add Activity</button>
      <button type="button" class="suggest-btn" onclick="generateActivitySuggestion()">💡 OCEAN’dan Öneri Al</button>
    </div>
  </form>

  <!-- Aktivite Grafiği -->
  <h3 style="margin-top: 40px;">Activities Added (Last 7 Days)</h3>
  <canvas id="activityChart" width="600" height="300"></canvas>
</div>

<!-- Ask Ocean Chat Paneli -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Yeni aktivite oluşturma hakkında yardım ister misin?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Bir soru yaz...">
    <button onclick="sendMessage()">➔</button>
  </div>
</div>

<!-- JS -->
<script src="../js/chat.js"></script>
<script>
  function toggleChat() {
    document.getElementById("chatPanel").classList.toggle("open");
  }

  // Chart.js ile veri çekme ve gösterme
  fetch('../pages/activity_growth_data.php?ts=' + new Date().getTime())
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.day);
      const counts = data.map(item => item.count);

      const ctx = document.getElementById('activityChart').getContext('2d');
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Activities',
            data: counts,
            backgroundColor: 'rgba(28,91,138,0.6)',
            borderRadius: 6
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                precision: 0
              }
            }
          },
          plugins: {
            legend: {
              display: false
            }
          }
        }
      });
    });
</script>

</body>
</html>
