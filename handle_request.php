<?php
require_once "db.php";
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'accept') {
        $status = 'Confirmed';
        $message = 'Your appointment has been successfully confirmed!.';
    } elseif ($action === 'reject') {
        $status = 'Rejected';
        $message = 'Your appointment has been rejected by the nurse.';
    } else {
        echo json_encode($response);
        exit;
    }

    // Update appointment status
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        // Fetch student_id + details
        $stmt2 = $conn->prepare("
            SELECT a.appointment_time, a.reason, a.student_id, CONCAT(s.first_name, ' ', s.last_name) AS student_name
            FROM appointments a
            JOIN students s ON a.student_id = s.id
            WHERE a.id = ?
        ");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            // Insert notification for student
            $stmt3 = $conn->prepare("
                INSERT INTO student_notifications (student_id, appointment_id, message, reschedule_status, is_read, created_at)
                VALUES (?, ?, ?, 'none', 0, NOW())
            ");
            $stmt3->bind_param("iis", $row['student_id'], $id, $message);
            $stmt3->execute();
            $stmt3->close();

            // If accepting, add to calendar events
            if ($action === 'accept') {
                $event_title = "Appointment: " . $row['student_name'] . " - " . $row['reason'];
                $event_start = date('Y-m-d H:i:s', strtotime($row['appointment_time']));
                $event_end = date('Y-m-d H:i:s', strtotime($row['appointment_time'] . ' +1 hour')); // Assume 1 hour duration
                $stmt4 = $conn->prepare("INSERT INTO events (appointment_id, title, start, end, note) VALUES (?, ?, ?, ?, ?)");
                $note = "Confirmed appointment";
                $stmt4->bind_param("issss", $id, $event_title, $event_start, $event_end, $note);
                $stmt4->execute();
                $stmt4->close();
            }

            // Response for nurse frontend
            $response = [
                'success' => true,
                'status' => $status,
                'date' => date('Y-m-d', strtotime($row['appointment_time'])),
                'time' => date('h:i A', strtotime($row['appointment_time'])),
                'name' => $row['student_name'],
                'reason' => $row['reason']
            ];
        }

        $stmt2->close();
    }

    $stmt->close();
}

if ($conn) $conn->close();

echo json_encode($response);
?>
