<?php
require_once "db.php";

$rows = [];
$sql = "SELECT 
            a.id, 
            DATE(a.appointment_time) AS date, 
            DATE_FORMAT(a.appointment_time, '%h:%i %p') AS time,
            CONCAT(s.first_name,' ',s.last_name) AS student_name, 
            a.reason, 
            a.status
        FROM appointments a
        JOIN students s ON a.student_id = s.id
        WHERE a.status IN ('Pending','Confirmed')
        ORDER BY a.appointment_time DESC 
        LIMIT 20";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($rows);
?>  