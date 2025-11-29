<?php
session_start();
require_once "db.php";

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'responder' && isset($_SESSION['responder_id'])) {
        $user_id = $_SESSION['responder_id'];
    } else {
        echo json_encode([]);
        exit;
    }
}

// Update nurse last_active timestamp on page load
if (isset($_SESSION['user_id'])) {
    $update_sql = "UPDATE users SET last_active = NOW() WHERE id = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("i", $_SESSION['user_id']);
    $stmt_update->execute();
    $stmt_update->close();
}

$rows = [];
$sql = "SELECT id, message, created_at FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($rows);
?>  