<?php
include '../projeeek/db.php';

// Bu ay içindeki kullanıcı kayıtlarını gün gün çek
$sql = "SELECT DATE(created_at) AS day, COUNT(*) AS count 
        FROM users 
        WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
          AND YEAR(created_at) = YEAR(CURRENT_DATE())
        GROUP BY day 
        ORDER BY day";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        "day" => $row['day'],
        "count" => (int)$row['count']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
