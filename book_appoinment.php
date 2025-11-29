<?php
require_once "db.php";

$nurse_status = "Offline";
$sql_nurse = "SELECT u.name, last_active, last_logout
              FROM users u
              JOIN roles r ON u.role_id = r.id
              WHERE r.name = 'nurse'
              ORDER BY last_active DESC
              LIMIT 1";
$result_nurse = $conn->query($sql_nurse);

if ($result_nurse && $result_nurse->num_rows > 0) {
    $nurse = $result_nurse->fetch_assoc();

    if ($nurse['last_logout'] == NULL || $nurse['last_logout'] == '' || $nurse['last_logout'] == "0000-00-00 00:00:00") {
        $nurse_status = "Online";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = intval($_POST['student_id']);
    $appointment_time = $_POST['appointment_time'];
    $reason = trim($_POST['reason']);

    
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM appointments WHERE appointment_time = ?");
    $stmt->bind_param("s", $appointment_time);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $status = ($result['cnt'] > 0) ? "Conflict" : "Pending";

    $stmt = $conn->prepare("INSERT INTO appointments (student_id, appointment_time, reason, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $student_id, $appointment_time, $reason, $status);
    $stmt->execute();
    $stmt->close();

    echo "success";
}
if ($conn) $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f8f8f8; padding: 20px; }
    .status { margin-top: 20px; background: #f5f5f5; padding: 10px; border-radius: 6px; }
  </style>
</head>
<body>
  <h2>Book Appointment</h2>
  <form method="POST">
    <input type="number" name="student_id" placeholder="Student ID" required><br><br>
    <input type="datetime-local" name="appointment_time" required><br><br>
    <input type="text" name="reason" placeholder="Reason" required><br><br>
    <button type="submit">Book</button>
  </form>

  <div class="status">
    Nurse status: <strong><?php echo $nurse_status; ?></strong>
  </div>
</body>
</html>
