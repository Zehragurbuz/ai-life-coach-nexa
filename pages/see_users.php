<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

include '../projeeek/db.php';

$result = $conn->query("SELECT id, full_name, email, gender, birthdate, is_admin FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>See Users - Admin</title>
  <link rel="stylesheet" href="../css_styles/sidebar1.css">
  <link rel="stylesheet" href="../css_styles/see_users.css">
  <link rel="stylesheet" href="../css_styles/ask-ocean-button.css"> <!-- ✅ Ocean butonu stili -->
</head>
<body>

<?php include '../sidebar1.php'; ?>

<!-- ✅ Ask Ocean Butonu (görsel olarak tüm panellerle aynı) -->
<button class="ask-ocean-button" onclick="toggleChat()" title="Ask Ocean"></button>

<div class="main-content">
  <h2>All Registered Users</h2>

  <table class="user-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Gender</th>
        <th>Birthdate</th>
        <th>Admin?</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $user['id'] ?></td>
          <td><?= htmlspecialchars($user['full_name']) ?></td>
          <td><?= htmlspecialchars($user['email']) ?></td>
          <td><?= ucfirst($user['gender']) ?></td>
          <td><?= $user['birthdate'] ?></td>
          <td><?= $user['is_admin'] ? '✔️' : '—' ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- ✅ Chat Kutusu -->
<div class="chat-container" id="chatPanel">
  <div class="chat-header">
    <h3>Ask OCEAN</h3>
    <button onclick="toggleChat()">✕</button>
  </div>
  <div class="chat-messages" id="chatMessages">
    <div class="message ai">Kullanıcı yönetimi hakkında yardım ister misiniz?</div>
  </div>
  <div class="chat-input">
    <input type="text" id="chatInput" placeholder="Bir şey yaz...">
    <button onclick="sendMessage()">➤</button>
  </div>
</div>

<!-- ✅ JavaScript -->
<script src="../js/chat.js"></script>

</body>
</html>
