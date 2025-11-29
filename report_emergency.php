<?php
session_start();
require_once "db.php";

$message = "";

// Auto-fill student name if logged in as student
$student_name_auto = "";
if (isset($_SESSION['student_id'])) {
    $sql = "SELECT CONCAT(first_name,' ',last_name) AS name FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION['student_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $student_name_auto = $row['name'];
    }
    $stmt->close();
}

$active_responders = [];

// Update responders to 'Off Duty' if they are 'Active' but last_active is more than 5 minutes ago or NULL
$update_inactive_sql = "UPDATE emergency_responders SET status = 'Off Duty' WHERE status = 'Active' AND (last_active IS NULL OR last_active < (NOW() - INTERVAL 5 MINUTE))";
$conn->query($update_inactive_sql);

$sql = "SELECT name FROM emergency_responders WHERE status = 'Active' ORDER BY name ASC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $active_responders[] = $row['name'];
    }
}


$nurse_status = "Offline";
$sql_nurse = "SELECT u.name, last_active, last_logout
              FROM users u
              JOIN roles r ON u.role_id = r.id
              WHERE r.name = 'nurse'
              LIMIT 1";
$result_nurse = $conn->query($sql_nurse);

if ($result_nurse && $result_nurse->num_rows > 0) {
    $nurse = $result_nurse->fetch_assoc();

    if (empty($nurse['last_logout']) || $nurse['last_logout'] === "0000-00-00 00:00:00") {
        $nurse_status = "Online";
    } else {
        $now = time();

        if (!empty($nurse['last_active']) && $nurse['last_active'] !== "0000-00-00 00:00:00") {
            $last_seen = strtotime($nurse['last_active']);
        } elseif (!empty($nurse['last_logout']) && $nurse['last_logout'] !== "0000-00-00 00:00:00") {
            $last_seen = strtotime($nurse['last_logout']);
        } else {
            $last_seen = false;
        }

        if ($last_seen !== false) {
            $diff = $now - $last_seen;

            if ($diff < 0) {
                $nurse_status = "Offline";
            } elseif ($diff <= 300 && !empty($nurse['last_active'])) {
                $nurse_status = "Online";
            } elseif ($diff < 3600) {
                $minutes = floor($diff / 60);
                if (empty($nurse['last_active'])) {
                    $nurse_status = "Offline ($minutes minutes ago)";
                } else {
                    $nurse_status = "Last seen $minutes minute(s) ago";
                }
            } elseif ($diff < 86400) {
                $hours = floor($diff / 3600);
                if (empty($nurse['last_active'])) {
                    $nurse_status = "Offline ($hours hours ago)";
                } else {
                    $nurse_status = "Last seen $hours hour(s) ago";
                }
            } else {
                $days = floor($diff / 86400);
                if (empty($nurse['last_active'])) {
                    $nurse_status = "Offline ($days days ago)";
                } else {
                    $nurse_status = "Last seen $days day(s) ago";
                }
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $floor = $_POST["floor"];
    $room = $_POST["room"];
    $student_name = $_POST["student"];
    $incident = $_POST["incident"];
    $reported_by = $_POST["reported_by"];
    $notify_guardian = isset($_POST["notify_guardian"]);

    // 🔹 Fetch student info from DB
    $sql = "SELECT id, phone, guardian_contact, first_name, last_name
            FROM students
            WHERE CONCAT(first_name,' ',last_name) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $student_id = $row["id"];
        $student_phone = $row["phone"];
        $guardian_phone = $row["guardian_contact"];

        // Format phone numbers to international format for Philippines
        if (strpos($student_phone, '+') !== 0) {
            if (strpos($student_phone, '09') === 0) {
                $student_phone = '+63' . substr($student_phone, 1);
            } elseif (strpos($student_phone, '9') === 0) {
                $student_phone = '+63' . $student_phone;
            }
        }
        if (!empty($guardian_phone) && strpos($guardian_phone, '+') !== 0) {
            if (strpos($guardian_phone, '09') === 0) {
                $guardian_phone = '+63' . substr($guardian_phone, 1);
            } elseif (strpos($guardian_phone, '9') === 0) {
                $guardian_phone = '+63' . $guardian_phone;
            }
        }

        
        $sms_message = "Emergency Alert: $incident reported at Floor $floor, Room $room.
        Student: $student_name. Reported by: $reported_by.";

        
        $send_result = send_sms_iprog($student_phone, $sms_message);

        if (!$send_result || !isset($send_result["status"]) || $send_result["status"] != 200) {
            $errorMsg = $send_result["message"] ?? "Unknown error";
            if (is_array($errorMsg)) {
                $errorMsg = json_encode($errorMsg);
            }
            $message .= "<div class='alert error'>❌ Error sending to Student. $errorMsg</div>";
        }

       
        if ($notify_guardian && !empty($guardian_phone)) {
            $guardian_result = send_sms_iprog($guardian_phone, $sms_message);
            if (!$guardian_result || !isset($guardian_result["status"]) || $guardian_result["status"] != 200) {
                $errorMsg = $guardian_result["message"] ?? "Unknown error";
                if (is_array($errorMsg)) {
                    $errorMsg = json_encode($errorMsg);
                }
                $message .= "<div class='alert error'>⚠ Error sending to Guardian. $errorMsg</div>";
            }
        }

        
        foreach ($active_responders as $responder_name) {
            $sql_resp_phone = "SELECT phone FROM emergency_responders WHERE name = ?";
            $stmt_resp = $conn->prepare($sql_resp_phone);
            $stmt_resp->bind_param("s", $responder_name);
            $stmt_resp->execute();
            $result_resp = $stmt_resp->get_result();
            if ($result_resp && $result_resp->num_rows > 0) {
                $resp_row = $result_resp->fetch_assoc();
                $resp_phone = $resp_row['phone'];
                if (!empty($resp_phone)) {
                    
                    if (strpos($resp_phone, '+') !== 0) {
                        if (strpos($resp_phone, '09') === 0) {
                            $resp_phone = '+63' . substr($resp_phone, 1);
                        } elseif (strpos($resp_phone, '9') === 0) {
                            $resp_phone = '+63' . $resp_phone;
                        }
                    }
                    $resp_result = send_sms_iprog($resp_phone, $sms_message);
                    if (!$resp_result || !isset($resp_result["status"]) || $resp_result["status"] != 200) {
                        $errorMsg = $resp_result["message"] ?? "Unknown error";
                        if (is_array($errorMsg)) {
                            $errorMsg = json_encode($errorMsg);
                        }
                        $message .= "<div class='alert error'>❌ Error sending to Responder $responder_name. $errorMsg</div>";
                    }
                }
            }
            $stmt_resp->close();
        }

        // 🔹 Insert notifications for nurse only (responders are notified via SMS)
        $sql_nurse_id = "SELECT u.id FROM users u JOIN roles r ON u.role_id = r.id WHERE r.name = 'nurse' LIMIT 1";
        $result_nurse_id = $conn->query($sql_nurse_id);
        if ($result_nurse_id && $result_nurse_id->num_rows > 0) {
            $nurse_row = $result_nurse_id->fetch_assoc();
            $nurse_id = $nurse_row['id'];

            $notif_message_nurse = "Emergency reported by $student_name: $incident at Floor $floor, Room $room.";
            $stmt_notif_nurse = $conn->prepare("INSERT INTO notifications (user_id, message, created_at, is_read) VALUES (?, ?, NOW(), 0)");
            $stmt_notif_nurse->bind_param("is", $nurse_id, $notif_message_nurse);
            $stmt_notif_nurse->execute();
            $stmt_notif_nurse->close();
        }
    } else {
        $message = "<div class='alert error'>⚠ Student not found in database.</div>";
    }
}

// 🔹 IPROG SMS Function
function send_sms_iprog($phone_number, $message) {
    $url = 'https://sms.iprogtech.com/api/v1/sms_messages';
    $api_token = '7207ca39cce111e8dcc540cf8c3066c21d9fbe84'; // hardcoded token

    $data = [
        'api_token' => $api_token,
        'phone_number' => $phone_number,
        'message' => $message,
        'sms_provider' => 2
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Emergency Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #ece3e3ff, #eee9e9ff);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: white;
    }
    .container {
      background: #cc0000;
      padding: 25px;
      border-radius: 16px;
      width: 95%;
      max-width: 380px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.5);
      position: relative;
    }
    .back-wrapper {
      display: flex;
      justify-content: flex-start;
      margin-bottom: 10px;
    }
    .back-btn {
      color: white;
      text-decoration: none;
      font-size: 18px;
      font-weight: bold;
      background: rgba(0,0,0,0.2);
      padding: 6px 12px;
      border-radius: 8px;
      transition: background 0.3s;
    }
    .back-btn:hover {
      background: rgba(0,0,0,0.4);
    }
    .container img {
      margin-bottom: 12px;
    }
    h2 {
      margin-bottom: 15px;
      font-size: 22px;
      font-weight: bold;
    }
    input {
      width: 90%;
      padding: 12px;
      margin: 10px auto;
      display: block;
      border-radius: 8px;
      border: none;
      font-size: 14px;
      box-sizing: border-box;
      text-align: center;
    }
    button {
      width: 95%;
      padding: 12px;
      margin-top: 12px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      cursor: pointer;
      font-weight: bold;
      background: #28a745;
      color: white;
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
      margin: 15px 0;
    }
    .status img {
      border-radius: 50%;
      margin-right: 8px;
    }
    .status span {
      color: green;
      margin-left: 6px;
      font-weight: bold;
    }
    .responder {
      background: #cc0000;
      padding: 10px;
      border-radius: 8px;
      margin-top: 10px;
      text-align: left;
    }
    .responder p {
      margin: 0 0 5px;
      font-weight: bold;
    }
    .badge {
      background: #28a745;
      padding: 6px 12px;
      border-radius: 20px;
      display: inline-block;
      margin: 4px 4px 0 0;
    }
    .alert {
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 6px;
      font-size: 14px;
      text-align: center;
      background: #e7f3fe;
      color: #00529B;
      border: 1px solid #00529B;
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
    <div class="back-wrapper">
      <a href="student_profile.php" class="back-btn">←</a>
    </div>

    <img src="images/logo.png" alt="School Logo" width="120">
    <h2>Emergency Form</h2>

    <?php if (!empty($message)) echo $message; ?>

    <form method="POST" style="text-align:center;">
      <input type="text" name="floor" placeholder="Floor" required>
      <input type="text" name="room" placeholder="Room" required>
      <input type="text" name="student" placeholder="Student Name" value="<?php echo htmlspecialchars($student_name_auto); ?>" required>
      <input type="text" name="incident" placeholder="Incident" required>
      <input type="text" name="reported_by" placeholder="Reported by" required>
      <label style="display:block; margin-top:10px; text-align: left; width: 90%; margin-left: auto; margin-right: auto;">
        <input type="checkbox" name="notify_guardian"> Notify Guardian
      </label>
      <button type="submit">Submit</button>
    </form>

    <div class="status">
      <img src="images/nurse.jpg" alt="Nurse" width="28">
      Nurse status: <span><?php echo $nurse_status; ?></span>
    </div>

    <div class="responder">
      <p>Active Responders:</p>
      <?php if (!empty($active_responders)): ?>
        <?php foreach ($active_responders as $responder): ?>
          <div class="badge"><?php echo htmlspecialchars($responder); ?></div>
        <?php endforeach; ?>
      <?php else: ?>
        <p style="color: white; font-size: 12px;">No active responders available.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
