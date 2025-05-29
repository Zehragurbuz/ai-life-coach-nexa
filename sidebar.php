<?php
// Oturumu başlat ve veritabanı bağlantısı kur
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../projeeek/db.php'; // Bağlantı dosyan buradaysa yolu doğru

// Varsayılan avatar
$profile_img = 'avatar.png';

$user_id = $_SESSION['user_id'] ?? null;
$user_age = '';
$user_weight = '';
$user_height = '';

if ($user_id) {
    $stmt = $conn->prepare("SELECT gender, birthdate, weight, height FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $user = $result->fetch_assoc()) {
        if ($user['gender'] === 'female') {
            $profile_img = '1.png';
        } elseif ($user['gender'] === 'male') {
            $profile_img = '2.png';
        }

        // Yaş hesapla
        if (!empty($user['birthdate'])) {
            $birthDate = new DateTime($user['birthdate']);
            $today = new DateTime('today');
            $user_age = $birthDate->diff($today)->y;
        }

        $user_weight = $user['weight'] ?? '';
        $user_height = $user['height'] ?? '';
    }
}


?>

<div class="sidebar">
  <div class="sidebar-header">
 <div class="sidebar-top">
  <a href="login.php" title="Go to Login Page">
    <img src="../images/logo.png" alt="NEXA Logo" class="sidebar-logo" />
  </a>

  <a href="profile.php" title="View Profile">
    <img src="../images/<?php echo $profile_img; ?>" alt="Profile" class="sidebar-avatar" />
  </a>
</div>





  </div>

  <ul class="sidebar-menu">
    <li><a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">🏠 <span>Dashboard</span></a></li>
    <li><a href="exercise.php" class="<?= $currentPage == 'exercise.php' ? 'active' : '' ?>">🧘‍♂️ <span>Exercise</span></a></li>
    <li><a href="nutrition.php" class="<?= $currentPage == 'nutrition.php' ? 'active' : '' ?>">🥗 <span>Nutrition</span></a></li>
    <li><a href="goal_tracking.php" class="<?= $currentPage == 'goal_tracking.php' ? 'active' : '' ?>">🎯 <span>Goals</span></a></li>
    <li><a href="profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>">👤 <span>Profile</span></a></li>
    <li><a href="notifications.php" class="<?= $currentPage == 'notifications.php' ? 'active' : '' ?>">🔔 <span>Notifications</span></a></li>
    <li><a href="settings.php" class="<?= $currentPage == 'settings.php' ? 'active' : '' ?>">⚙️ <span>Settings</span></a></li>
    <li><a href="logout.php">🚪 <span>Logout</span></a></li>
  </ul>
</div>
