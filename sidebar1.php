<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
  <div class="sidebar-top">
    <!-- Logo -->
    <a href="login.php" title="Go to Login Page">
      <img src="../images/logo.png" alt="NEXA Logo" class="sidebar-logo" />
    </a>

    <!-- Tek Admin Avatar -->
    <a href="admin_dashboard.php" title="Admin">
      <img src="../images/admin1.png" alt="Admin" class="admin-avatar" />
    </a>
  </div>

  <ul class="sidebar-menu">
    <li class="<?= $currentPage == 'admin_dashboard.php' ? 'active' : '' ?>">
      <a href="admin_dashboard.php">📊 <span>Admin Dashboard</span></a>
    </li>
    <li class="<?= $currentPage == 'add_activity.php' ? 'active' : '' ?>">
      <a href="add_activity.php">➕ <span>Add Activity</span></a>
    </li>
    <li class="<?= $currentPage == 'add_goal.php' ? 'active' : '' ?>">
      <a href="add_goal.php">🎯 <span>Add Goal</span></a>
    </li>
    <li class="<?= $currentPage == 'add_nutrition.php' ? 'active' : '' ?>">
      <a href="add_nutrition.php">🥗 <span>Add Nutrition</span></a>
    </li>
    <li class="<?= $currentPage == 'see_users.php' ? 'active' : '' ?>">
      <a href="see_users.php">👥 <span>Users</span></a>
    </li>
      <li class="<?= $currentPage == 'send_notification.php' ? 'active' : '' ?>">
    <a href="send_notification.php">📢 <span>Send Notification</span></a>
  </li>
    <li class="<?= $currentPage == 'logout.php' ? 'active' : '' ?>">
      <a href="logout.php">🚪 <span>Logout</span></a>
    </li>
  </ul>
</div>
