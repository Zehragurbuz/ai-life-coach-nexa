<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ai_koc"; // GÜNCELLENDİ

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>
