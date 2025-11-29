<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON body if sent
    $raw = file_get_contents("php://input");
    $input = json_decode($raw, true);

    // Fallback to normal form-data
    if (!$input) {
        $input = $_POST;
    }

    // Collect values safely
    $program_id  = intval($input['program_id'] ?? 0);
    $name        = trim($input['name'] ?? '');
    $semester    = trim($input['semester'] ?? '');
    $is_archived = isset($input['is_archived']) ? intval($input['is_archived']) : 0;

    if ($program_id && $name && $semester) {
        $stmt = $conn->prepare("
            INSERT INTO sections (program_id, name, semester, is_archived)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("issi", $program_id, $name, $semester, $is_archived);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Section added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
    }
    exit;
}
