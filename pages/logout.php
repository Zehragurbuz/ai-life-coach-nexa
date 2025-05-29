<?php
session_start();
session_unset();  // Tüm session değişkenlerini siler
session_destroy(); // Oturumu sonlandırır

header("Location: login.php"); // Login sayfasına yönlendir
exit();
?>
