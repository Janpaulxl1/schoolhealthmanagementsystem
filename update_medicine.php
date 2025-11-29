<?php
header('Content-Type: application/json');
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $medicine_name = $_POST['medicine_name'];
    $dosage = $_POST['dosage'];
    $quantity = (int)$_POST['quantity'];
    $action_taken = $_POST['action_taken'];

    if (!$id || !is_numeric($id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        exit;
    }

    if ($conn) {
        $sql = "UPDATE appointment_medications SET medicine_name = ?, dosage = ?, quantity = ?, action_taken = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $medicine_name, $dosage, $quantity, $action_taken, $id);
        try {
            $success = $stmt->execute();
            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database update failed: ' . $conn->error]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    }
    $conn->close();
}
?>
