<?php
include '../projeeek/db.php';

$data = [];
$today = new DateTime();
$interval = new DateInterval('P1D'); // 1 gün
$period = new DatePeriod((new DateTime())->modify('-6 days'), $interval, 7);

foreach ($period as $day) {
    $dateStr = $day->format('Y-m-d');
    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM nutrition WHERE date = ?");
    $stmt->bind_param("s", $dateStr);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $data[] = [
        'day' => $day->format('M d'),
        'count' => (int)($result['count'] ?? 0)
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
