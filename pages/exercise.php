<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

include '../projeeek/db.php';
$user_id = $_SESSION['user_id'] ?? null;

// ----------------- EGZERSİZ KAYITLARI ---------------------
$exerciseData = [];
if ($user_id) {
  $stmt = $conn->prepare("
    SELECT title, description, date 
    FROM activities 
    WHERE user_id = ? AND type = 'exercise' 
    ORDER BY date DESC
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $exerciseData = $result->fetch_all(MYSQLI_ASSOC);
}

// ----------------- GRAFİK VERİSİ (SON 7 GÜN) ---------------------
$chartData = array_fill_keys(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'], 0);
if ($user_id) {
  $stmt = $conn->prepare("
    SELECT DAYNAME(date) as day_name, COUNT(*) as count
    FROM activities
    WHERE user_id = ? AND type = 'exercise' AND date >= CURDATE() - INTERVAL 6 DAY
    GROUP BY day_name
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();

  while ($row = $result->fetch_assoc()) {
    $day = substr($row['day_name'], 0, 3); // Örn: Mon
    $chartData[$day] = (int)$row['count'];
  }
}

// ----------------- HAFTALIK TAMAMLAMA ÇUBUĞU ---------------------
$weeklyGoal = 4;
$completed = 0;
$progressPercent = 0;

if ($user_id) {
  $stmt = $conn->prepare("
    SELECT COUNT(*) as total
    FROM activities
    WHERE user_id = ? AND type = 'exercise' AND WEEK(date) = WEEK(NOW()) AND YEAR(date) = YEAR(NOW())
  ");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  $completed = (int)$row['total'];
  $progressPercent = min(100, round(($completed / $weeklyGoal) * 100));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exercise Section - NEXA</title>
  <link rel="stylesheet" href="../css_styles/sidebar.css">
  <link rel="stylesheet" href="../css_styles/exercise.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../sidebar.php'; ?>

<div class="ask-ocean-button" onclick="toggleChat()">
  <img src="../images/ocean-icon.png" alt="Ask Ocean">
  <span>Ask <strong>OCEAN</strong></span>
</div>

<div class="main-content">
  <div class="exercise-header">
    <h2>Exercise Programs</h2>
  </div>

  <!-- ✅ ÖNERİ KARTLARI -->
  <div class="exercise-cards">
    <?php
    $suggestionQuery = $conn->query("SELECT * FROM exercise_suggestions");
    while ($suggestion = $suggestionQuery->fetch_assoc()):
    ?>
      <div class="exercise-card">
        <h3><?= htmlspecialchars($suggestion['title']) ?></h3>
        <p><?= htmlspecialchars($suggestion['description']) ?></p>
        <form method="POST" action="add_exercise_form_card.php">
          <input type="hidden" name="title" value="<?= htmlspecialchars($suggestion['title']) ?>">
          <input type="hidden" name="description" value="<?= htmlspecialchars($suggestion['description']) ?>">
          <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
          <button type="submit">Yaptım</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- ✅ KULLANICIYA ÖZEL EGZERSİZ KARTLARI -->
  <div class="user-exercise-logs">
    <h3>Senin Egzersiz Kayıtların 🗂️</h3>
    <?php if (count($exerciseData) > 0): ?>
      <?php foreach ($exerciseData as $activity): ?>
        <div class="user-exercise-card">
          <strong><?= htmlspecialchars($activity['title']) ?></strong><br>
          <small><?= htmlspecialchars($activity['description']) ?></small><br>
          <span class="log-date">🗓️ <?= date('d M Y', strtotime($activity['date'])) ?></span>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Henüz kayıtlı egzersizin yok 💪</p>
    <?php endif; ?>
  </div>

  <!-- ✅ GRAFİK VE İLERLEME -->
  <div class="exercise-analytics">
    <div class="chart-box">
      <canvas id="exerciseChart"></canvas>
    </div>

    <div class="goal-box">
      <h4>Bu hafta <?= $weeklyGoal ?> egzersiz hedefin vardı —<br>
        <strong><?= $completed ?> tamamladın ✅</strong></h4>
      <div class="progress-bar">
        <div class="progress" style="width: <?= $progressPercent ?>%;"></div>
      </div>
      <p class="percentage"><?= $progressPercent ?>%</p>
    </div>
  </div>
</div>

<!-- ✅ Ask Ocean Sohbet Paneli -->
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
  const ctx = document.getElementById('exerciseChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
        label: 'Egzersiz Sayısı',
        data: [<?= implode(',', $chartData) ?>],
        borderColor: '#47b275',
        backgroundColor: 'rgba(71, 178, 117, 0.2)',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false }},
      scales: { y: { beginAtZero: true }}
    }
  });

  function toggleChat() {
    document.getElementById("chatPanel").classList.toggle("open");
  }
</script>

</body>
</html>
