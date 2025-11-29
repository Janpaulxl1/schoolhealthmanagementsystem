<?php
session_start();
require_once 'db.php';

$action = $_POST['action'] ?? 'update';

$student_id = $_POST['student_id'] ?? '';
$student_name = $_POST['student_name'] ?? '';
$course = $_POST['course'] ?? '';
$reason = $_POST['reason'] ?? '';
$action_taken = $_POST['action_taken'] ?? '';
$medicine_name = $_POST['medicine_name'] ?? '';
$dosage = $_POST['dosage'] ?? '';
$quantity = $_POST['quantity'] ?? '';
$visit_date = $_POST['visit_date'] ?? '';
// $location = $_POST['location'] ?? '';

// Convert visit_date to MySQL datetime format if set
if (!empty($visit_date)) {
    $visit_date = date('Y-m-d H:i:s', strtotime($visit_date));
}

// Get the student id (int) from student_id (varchar)
$stmt_get_id = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
$stmt_get_id->bind_param("s", $student_id);
$stmt_get_id->execute();
$result = $stmt_get_id->get_result();
if ($result->num_rows == 0) {
    die("❌ Student not found with ID: $student_id");
}
$student_row = $result->fetch_assoc();
$student_id_int = $student_row['id'];

if ($action === 'add') {
    $stmt = $conn->prepare("INSERT INTO student_visits (student_id, student_name, course, reason, action_taken, med_id, dosage, quantity, visit_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("issssssss", $student_id_int, $student_name, $course, $reason, $action_taken, $medicine_name, $dosage, $quantity, $visit_date);
    $stmt->execute();
} else {
    $id = $_POST['id'] ?? 0;
    $stmt = $conn->prepare("UPDATE student_visits SET student_id=?, student_name=?, course=?, reason=?, action_taken=?, med_id=?, dosage=?, quantity=?, visit_date=? WHERE id=?");
    $stmt->bind_param("issssssssi", $student_id_int, $student_name, $course, $reason, $action_taken, $medicine_name, $dosage, $quantity, $visit_date, $id);
    $stmt->execute();
}

header("Location: student_visitlogs.php");
exit;
?>
