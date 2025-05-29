<?php
include '../projeeek/db.php';

$seven_days_ago = date('Y-m-d', strtotime('-6 days'));
$today = date('Y-m-d');

$sql = "SELECT DATE(start_date) as day, COUNT(*) as count
        FROM goals
        WHERE start_date BETWEEN '$seven_days_ago' AND '$today'
        GROUP BY day
        ORDER BY day";

$result = $conn->query($sql);

$data = [];
$days = [];

for ($i = 0; $i < 7; $i++) {
    $day = date('Y-m-d', strtotime("-" . (6 - $i) . " days"));
    $days[$day] = 0;
}

while ($row = $result->fetch_assoc()) {
    $days[$row['day']] = (int)$row['count'];
}

foreach ($days as $day => $count) {
    $data[] = ['day' => $day, 'count' => $count];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
