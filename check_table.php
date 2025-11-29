<?php
require_once 'db.php';

$result = $conn->query('SHOW TABLES LIKE "appointment_logs"');
if ($result->num_rows > 0) {
    echo 'appointment_logs table exists';
} else {
    echo 'appointment_logs table does not exist';
}
?>
