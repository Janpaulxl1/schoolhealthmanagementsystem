<?php
session_start();
require_once 'db.php'; 

$message = "";
$nurse_status = "Offline";
$nurse_offline_minutes = 0;
$sql_nurse = "SELECT u.name, last_active, last_logout FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'nurse' ORDER BY last_active DESC LIMIT 1";
$result_nurse = $conn->query($sql_nurse);
if ($result_nurse && $result_nurse->num_rows > 0) {
    $nurse = $result_nurse->fetch_assoc();

    if ($nurse['last_logout'] == NULL || $nurse['last_logout'] == '' || $nurse['last_logout'] == "0000-00-00 00:00:00") {
        $nurse_status = "Online";
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = trim($_POST['student_id'] ?? '');
    $appointment_time = $_POST['datetime'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    if (empty($student_id) || empty($appointment_time) || empty($reason)) {
        $message = "<div class='alert error'>⚠ All fields are required.</div>";
    } else {
        
        $stmt = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
        $stmt->bind_param("s", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();

        if (!$student) {
            $message = "<div class='alert error'>⚠ Student not found in records.</div>";
        } else {
            
$stmt = $conn->prepare("INSERT INTO appointments 
(student_id, appointment_time, reason, status, is_emergency) 
VALUES (?, ?, ?, 'Pending', 0)");
$stmt->bind_param("iss", $student['id'], $appointment_time, $reason);


            if ($stmt->execute()) {
                $_SESSION['appointment_time'] = $appointment_time;
                header("Location: success_appointment.php");
                exit();
            } else {
                $message = "<div class='alert error'>❌ Database error: " . $stmt->error . "</div>";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Booking</title>
  <style>
  body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background: linear-gradient(to bottom, #f7f9faff, #eaebecff);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  color: white;
}
.container {
  background: #12355F;
  padding: 25px;
  border-radius: 16px;
  width: 95%;
  max-width: 380px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.5);
  position: relative;
}
.back-btn {
  position: absolute;
  top: 15px;
  left: 15px;
  color: white;
  text-decoration: none;
  font-size: 20px;
  font-weight: bold;
}
.container img {
  margin-bottom: 12px;
}
h2 {
  margin-bottom: 15px;
  font-size: 20px;
  text-transform: uppercase;
}
.status {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f5f5;
  color: black;
  font-size: 14px;
  padding: 8px;
  border-radius: 8px;
  margin: 10px 0 20px;
}
.status span {
  color: green;
  margin-left: 6px;
  font-weight: bold;
}
input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border-radius: 8px;
  border: none;
  font-size: 14px;
  box-sizing: border-box;
}
button {
  width: 100%;
  padding: 12px;
  margin: 8px 0;
  border: none;
  border-radius: 8px;
  font-size: 15px;
  cursor: pointer;
  font-weight: bold;
  display: block;
}
button.submit {
  background: #007BFF;
  color: white;
}
button.cancel {
  background: red;
  color: white;
}
.alert {
  padding: 10px;
  margin-bottom: 15px;
  border-radius: 6px;
  font-size: 14px;
  text-align: center;
}
.alert.error {
  background: #ffdddd;
  color: #d8000c;
  border: 1px solid #d8000c;
}

  </style>
</head>
<body>
  <div class="container">
    <a href="student_profile.php" class="back-btn">←</a>
    <img src="images/logo.png" alt="School Logo" width="120">
    <h2>APPOINTMENT BOOKING:</h2>
    <div class="status">
      <img src="images/nurse.jpg" alt="Nurse" width="28" style="border-radius:50%; margin-right:5px;">
      Nurse status: <span><?php echo $nurse_status; ?></span>
    </div>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST">
      <input type="text" name="student_id" placeholder="Student ID" required>
      <input type="datetime-local" name="datetime" required>
      <input type="text" name="reason" placeholder="Reason" required>
      <div style="display:flex; justify-content:space-between; flex-wrap:wrap;">
        <button type="submit" class="submit">Submit request</button>
        <button type="reset" class="cancel">Cancel</button>
      </div>
    </form>
  </div>
</body>
</html>
