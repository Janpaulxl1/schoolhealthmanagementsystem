<?php
session_start();
require_once "db.php";

// Set header for JSON response
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Use correct parameter name
    $appointment_id = intval($_POST['id'] ?? 0);
    $new_time_raw = $_POST['new_time'] ?? '';

    if (!$appointment_id || !$new_time_raw) {
        $response['message'] = "Invalid appointment ID or new time.";
        echo json_encode($response);
        exit;
    }

    // Validate and format new time
    $dt = date_create($new_time_raw);
    if (!$dt) {
        $response['message'] = "Invalid datetime format.";
        echo json_encode($response);
        exit;
    }

    $new_time = $dt->format("Y-m-d H:i:s"); // for DB
    $display_time = $dt->format("M d, Y - h:i A"); // for front-end

    // Get student_id for this appointment
    $stmt = $conn->prepare("SELECT student_id, reason FROM appointments WHERE id = ?");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $stmt->bind_result($student_id, $reason);
    $stmt->fetch();
    $stmt->close();

    if (!$student_id) {
        $response['message'] = "Student not found.";
        echo json_encode($response);
        exit;
    }

    // Update appointment
    $update = $conn->prepare("UPDATE appointments SET appointment_time = ?, status = 'Rescheduled' WHERE id = ?");
    $update->bind_param("si", $new_time, $appointment_id);
    $update->execute();
    $update->close();

    // Create notification for student
    $message = "Your appointment has been successfully rescheduled to $display_time. Please accept or decline.";
    $notif = $conn->prepare("
        INSERT INTO student_notifications (student_id, message, appointment_id, reschedule_status, created_at, is_read) 
        VALUES (?, ?, ?, 'pending', NOW(), 0)
    ");
    $notif->bind_param("isi", $student_id, $message, $appointment_id);
    $notif->execute();
    $notif->close();

    // Return response for UI update
    $response = [
        "success" => true,
        "status" => "Rescheduled",
        "new_time" => $display_time,
        "name" => "Student ID $student_id", // Optional; replace with actual name if needed
        "reason" => $reason ?? ''
    ];
}

$conn->close();
echo json_encode($response);
