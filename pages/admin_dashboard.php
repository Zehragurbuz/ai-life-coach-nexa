<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

include '../projeeek/db.php';

$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'] ?? 0;
$goal_count = $conn->query("SELECT COUNT(*) AS total FROM goals")->fetch_assoc()['total'] ?? 0;
$activity_count = $conn->query("SELECT COUNT(*) AS total FROM activities")->fetch_assoc()['total'] ?? 0;
$nutrition_count = $conn->query("SELECT COUNT(*) AS total FROM nutrition")->fetch_assoc()['total'] ?? 0; // ✅ yeni satır
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - NEXA</title>
  <link rel="stylesheet" href="../css_styles/sidebar1.css">
  <link rel="stylesheet" href="../css_styles/admin_dashboard.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../sidebar1.php'; ?>

<!-- Ask Ocean Butonu -->
<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
  <div class="admin-welcome">
    <h2><span class="emoji">👑</span> Welcome back, <span class="admin-name">Admin</span>!</h2>
    <p class="subtext">You are in control. Monitor and manage with ease.</p>
  </div>

<!-- Kartlar -->
<div class="admin-cards">
  <a href="see_users.php" class="card-link">
    <div class="card">
      <h4>👥 Total Users</h4>
      <p><?= $user_count ?></p>
    </div>
  </a>

  <a href="add_goal.php" class="card-link">
    <div class="card">
      <h4>🎯 Total Goals</h4>
      <p><?= $goal_count ?></p>
    </div>
  </a>

  <a href="add_activity.php" class="card-link">
    <div class="card">
      <h4>🏃‍♂️ Total Activities</h4>
      <p><?= $activity_count ?></p>
    </div>
  </a>

  <a href="add_nutrition.php" class="card-link">
    <div class="card">
      <h4>🍎 Total Nutritions</h4>
      <p><?= $nutrition_count ?></p>
    </div>
  </a>
</div>


  <!-- Günlük Kullanıcı Artışı -->
  <h3>User Growth Over Time (This Month)</h3>
  <canvas id="userGrowthChart" width="600" height="300"></canvas>
</div>

<!-- Chat Panel -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Admin paneli ile ilgili yardıma ihtiyacın var mı?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Bir soru yaz...">
    <button onclick="sendMessage()">➔</button>
  </div>
</div>

<script src="../js/chat.js"></script>
<script>
  function toggleChat() {
    document.getElementById("chatPanel").classList.toggle("open");
  }

  fetch('../pages/user_growth_data.php?ts=' + new Date().getTime())
    .then(response => response.json())
    .then(data => {
      const labels = data.map(item => item.day);
      const counts = data.map(item => item.count);

      const ctx = document.getElementById('userGrowthChart').getContext('2d');
      new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'New Users Per Day',
            data: counts,
            borderColor: '#1c5b8a',
            backgroundColor: 'rgba(28,91,138,0.1)',
            tension: 0.3,
            fill: true,
            pointRadius: 5
          }]
        },
        options: {
          responsive: true,
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
              display: true
            }
          }
        }
      });
    });
</script>

</body>
</html>
