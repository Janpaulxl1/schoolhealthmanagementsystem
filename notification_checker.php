<?php
require_once "db.php";

$rows = [];
$sql = "SELECT a.id, a.appointment_time, a.reason, a.status, a.is_emergency,
               CONCAT(s.first_name,' ',s.last_name) AS student_name
        FROM appointments a
        JOIN students s ON a.student_id = s.id
        WHERE a.status IN ('Pending', 'Pending Nurse Confirmation')
        ORDER BY a.appointment_time ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($rows);

$conn->close();
?>