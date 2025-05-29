<?php
session_start();
include '../projeeek/db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$stmt = $conn->prepare("SELECT full_name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_name = $user['full_name'] ?? 'User';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mood'])) {
  $_SESSION['mood'] = $_POST['mood'];
}
$mood = $_SESSION['mood'] ?? 'neutral';
$emoji = ['happy' => '😊', 'neutral' => '😐', 'sad' => '😞'][$mood] ?? '🌞';

$goal_suggestions = [
  "Even a short walk will benefit you.",
  "Take 5 minutes to stretch your body.",
  "Drink a glass of water and breathe deeply.",
  "Stand up and move around every 30 minutes.",
  "Go outside and get some sunlight.",
  "Make one healthy meal choice today.",
  "Try to reach 6,000 steps today.",
  "Write down a small goal for today.",
  "Give yourself a 2-minute break from screens.",
  "Say one kind thing to yourself today."
];
$random_tip = $goal_suggestions[array_rand($goal_suggestions)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../css_styles/sidebar.css">
  <link rel="stylesheet" href="../css_styles/dashboard.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php include '../sidebar.php'; ?>

<!-- ✅ Ask Ocean Button (onclick kaldırıldı, id eklendi) -->
<div class="ask-ocean-button" onclick="toggleChat()">
  <img src="../images/ocean-icon.png" alt="Ask Ocean">
  <span>Ask <strong>OCEAN</strong></span>
</div>


<div class="main-content">
  <h2>Welcome back, <?= htmlspecialchars($user_name) ?>! <?= $emoji ?></h2>

  <div class="dashboard-grid">
    <div class="mood-box card">
      <h4>How do you feel today?</h4>
      <form method="post">
        <button name="mood" value="happy">😊</button>
        <button name="mood" value="neutral">😐</button>
        <button name="mood" value="sad">😞</button>
      </form>
    </div>

    <div class="quote-slider card" id="quoteBox">You’re stronger than you think.</div>

    <div class="week-tracker card">
      <h4>This Week's Activity</h4>
      <div class="week-days">
        <span class="day active">M</span>
        <span class="day active">T</span>
        <span class="day">W</span>
        <span class="day">T</span>
        <span class="day">F</span>
        <span class="day">S</span>
        <span class="day">S</span>
      </div>
    </div>
  </div>

  <div class="dashboard-grid">
    <div class="goals-box card">
      <h4>📌 Today's Goals</h4>
      <ul>
        <li><input type="checkbox"> Drink 8 glasses of water</li>
        <li><input type="checkbox"> Take a 10 min walk</li>
        <li><input type="checkbox"> Do 5 min breathing</li>
      </ul>
    </div>

    <div class="daily-tip card">
      <h4>🎯 Do something for yourself today!</h4>
      <p><?= $random_tip ?></p>
      <button onclick="location.href='goal_tracking.php'">Go to Goals</button>
    </div>

    <div class="ocean-suggestion card">
      <h4>🤖 Today's Suggestion</h4>
      <p>Stretch for 5 minutes after waking up.</p>
      <button onclick="location.href='exercise.php'">Start Exercise</button>
    </div>
  </div>

  <div class="step-section card">
    <label>Enter your steps today:</label>
    <input type="number" id="stepInput" placeholder="e.g. 5000">
    <button onclick="addSteps()">Add</button>
    <canvas id="stepChart" width="400" height="200"></canvas>
  </div>
</div>

<!-- ✅ Chat Panel -->
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

<!-- ✅ JavaScript'ler en alta taşındı -->
<script src="../js/chat.js"></script>
<script>
  const quotes = [
    "You’re stronger than you think.",
    "Keep going – you're almost there!",
    "Every step counts.",
    "Discipline > Motivation."
  ];
  let currentQuote = 0;
  setInterval(() => {
    currentQuote = (currentQuote + 1) % quotes.length;
    document.getElementById("quoteBox").innerText = quotes[currentQuote];
  }, 5000);

  let stepData = [3000, 4200, 5000, 0, 0, 0, 0];
  const ctx = document.getElementById('stepChart').getContext('2d');
  const stepChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
      datasets: [{
        label: 'Steps',
        data: stepData,
        backgroundColor: '#27ae60'
      }]
    }
  });

  function addSteps() {
    const today = new Date().getDay();
    const steps = parseInt(document.getElementById('stepInput').value);
    if (!isNaN(steps)) {
      stepData[today === 0 ? 6 : today - 1] = steps;
      stepChart.update();
    }
  }
</script>

</body>
</html>