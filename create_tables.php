<?php
require_once 'db.php';

$sql1 = "CREATE TABLE IF NOT EXISTS appointment_medications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    medicine_name VARCHAR(255) NOT NULL,
    dosage VARCHAR(100),
    quantity INT NOT NULL,
    action_taken TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);";

$sql2 = "CREATE TABLE IF NOT EXISTS medical_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    date DATE NOT NULL,
    reason TEXT,
    medications JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);";

if ($conn->query($sql1) === TRUE) {
    echo "appointment_medications table created successfully.\n";
} else {
    echo "Error creating appointment_medications: " . $conn->error . "\n";
}

if ($conn->query($sql2) === TRUE) {
    echo "medical_history table created successfully.\n";
} else {
    echo "Error creating medical_history: " . $conn->error . "\n";
}

$conn->close();
?>
