<?php
include '../projeeek/db.php';

$seven_days_ago = date('Y-m-d', strtotime('-6 days'));
$today = date('Y-m-d');

// Sorgu: Son 7 gün, gün gün gruplama
$sql = "SELECT DATE(date) AS activity_day, COUNT(*) AS total
        FROM activities
        WHERE date BETWEEN '$seven_days_ago' AND '$today'
        GROUP BY activity_day
        ORDER BY activity_day";

$result = $conn->query($sql);

$data = [];
// Tüm 7 günü baştan oluştur ki boş günler de gözüksün
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $day = date('Y-m-d', strtotime("-$i days"));
    $dates[$day] = 0;
}

while ($row = $result->fetch_assoc()) {
    $dates[$row['activity_day']] = (int)$row['total'];
}

// JSON çıktısı
foreach ($dates as $day => $count) {
    $data[] = ['day' => $day, 'count' => $count];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
