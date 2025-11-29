<?php
require_once 'db.php';

$result = $conn->query('DESCRIBE appointments');
if ($result) {
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . ' ' . $row['Type'] . ' ' . ($row['Null'] == 'NO' ? 'NOT NULL' : 'NULL') . ' ' . ($row['Key'] ? $row['Key'] : '') . ' ' . ($row['Default'] ? 'DEFAULT ' . $row['Default'] : '') . PHP_EOL;
    }
} else {
    echo 'Error: ' . $conn->error;
}
?>
