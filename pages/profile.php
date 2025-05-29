<?php
session_start();
include __DIR__ . '/../projeeek/db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$stmt = $conn->prepare("SELECT full_name, email, birthdate, weight, height, gender, goal_weight, chest, waist, hip FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc() ?? [];

// Safe fallback values
$full_name   = $user['full_name']   ?? 'User';
$height      = $user['height']      ?? '-';
$weight      = $user['weight']      ?? '-';
$goal_weight = $user['goal_weight'] ?? '-';
$chest       = $user['chest']       ?? '-';
$waist       = $user['waist']       ?? '-';
$hip         = $user['hip']         ?? '-';

function calculate_bmi($weight, $height) {
    if ($weight && $height && $height > 0) {
        $height_m = $height / 100;
        return round($weight / ($height_m * $height_m), 1);
    }
    return null;
}

function determine_shape($waist, $hip) {
    if ($waist && $hip && $hip > 0) {
        $ratio = $waist / $hip;
        if ($ratio < 0.8) return 'bodies/hourglass.png';
        elseif ($ratio < 0.85) return 'bodies/pear.png';
        elseif ($ratio < 0.9) return 'bodies/rectangle.png';
        else return 'bodies/inverted-triangle.png';
    }
    return null;
}

$bmi = calculate_bmi($weight, $height);
$progress = ($goal_weight && $weight && $weight > 0) ? round(($goal_weight / $weight) * 100) : 0;
$shape_img = determine_shape($waist, $hip);
$shape_name = $shape_img ? ucfirst(pathinfo($shape_img, PATHINFO_FILENAME)) : '-';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link rel="stylesheet" href="../css_styles/profile.css">
  <link rel="stylesheet" href="../css_styles/sidebar.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css">
</head>
<body>

<?php include '../sidebar.php'; ?>

<!-- Ask Ocean Button -->
<div class="ask-ocean-button" onclick="toggleChat()">
  <img src="../images/ocean-icon.png" alt="Ask Ocean">
  <span>Ask <strong>OCEAN</strong></span>
</div>

<div class="main-content">
  <?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
  <div class="update-success">
    ✅ Your profile has been successfully updated!
  </div>
<?php endif; ?>

  <div class="profile-container">
    <h2>Welcome, <?= htmlspecialchars($full_name) ?> 👋</h2>

    <div class="info-grid">
      <div><strong>Height:</strong> <?= $height ?> cm</div>
      <div><strong>Weight:</strong> <?= $weight ?> kg</div>
      <div><strong>BMI:</strong> <?= $bmi ?: '-' ?></div>
      <div><strong>Goal Weight:</strong> <?= $goal_weight ?> kg</div>
      <div><strong>Chest:</strong> <?= $chest ?> cm</div>
      <div><strong>Waist:</strong> <?= $waist ?> cm</div>
      <div><strong>Hip:</strong> <?= $hip ?> cm</div>
      <div><strong>Body Shape:</strong> <?= $shape_name ?></div>
    </div>

    <div class="progress">
      <label>Goal Progress:</label>
      <progress value="<?= $progress ?>" max="100"></progress> <?= $progress ?>%
    </div>

    <div class="shape-image">
      <?php if ($shape_img): ?>
        <img src="../images/<?= $shape_img ?>" alt="Body Shape">
        <p><strong>Shape:</strong> <?= $shape_name ?></p>
      <?php endif; ?>
    </div>

    <div class="actions">
      <button onclick="location.href='../pages/settings.php'">✏️ Edit My Info</button>
    </div>
  </div>
</div>

<!-- Ask Ocean Chat -->
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
