<?php
require_once "db.php"; 

// ✅ Ensure responder_status exists
$conn->query("ALTER TABLE students
    ADD COLUMN IF NOT EXISTS is_responder TINYINT(1) DEFAULT 0,
    ADD COLUMN IF NOT EXISTS responder_status ENUM('Active','On Duty','Off Duty') DEFAULT 'Off Duty'
");

// Fetch Sections
$sections = [];
$res = $conn->query("SELECT id, name, semester FROM sections ORDER BY name ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $sections[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id       = $_POST['studentID'];
    $first_name       = $_POST['firstName'];
    $middle_name      = $_POST['middleName'];
    $last_name        = $_POST['lastName'];
    $birthday         = $_POST['birthday'];
    $gender           = $_POST['gender'];
    $email            = $_POST['email'];
    $phone            = $_POST['phone'];
    $home_address     = $_POST['homeAddress'];
    $guardian_name    = $_POST['guardiansName'];
    $guardian_address = $_POST['guardiansAddress'];
    $emergency_contact= $_POST['contactNo']; 
    $relationship     = $_POST['relationship'];
    $course           = $_POST['course'];
    $year_level       = $_POST['yearLevel'];
    $section_id       = $_POST['section']; 
    $password         = password_hash($_POST['createPassword'], PASSWORD_BCRYPT);

    // ✅ Emergency Responder checkbox
    $is_responder = isset($_POST['isResponder']) ? 1 : 0;
    $responder_status = "Active"; // default for responders

    // Profile picture upload
    $profile_picture = null;
    if (!empty($_FILES['profilePicture']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = uniqid("profile_") . "_" . basename($_FILES["profilePicture"]["name"]);
        $profile_picture = $targetDir . $filename;
        move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $profile_picture);
    }

    // Requirements uploads
    function uploadRequirement($fieldName, $prefix = "req_") {
        if (!empty($_FILES[$fieldName]['name'])) {
            $targetDir = "uploads/requirements/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $filename = uniqid($prefix) . "_" . basename($_FILES[$fieldName]["name"]);
            $filePath = $targetDir . $filename;
            move_uploaded_file($_FILES[$fieldName]["tmp_name"], $filePath);
            return $filePath;
        }
        return null;
    }

    $vaccine_record = uploadRequirement("vaccineRecord", "vaccine");
    $medical_history = uploadRequirement("medicalHistory", "medhist");

    // Get section name
    $secStmt = $conn->prepare("SELECT name FROM sections WHERE id = ? LIMIT 1");
    $secStmt->bind_param("i", $section_id);
    $secStmt->execute();
    $secRes = $secStmt->get_result();
    $section_name = "";
    if ($secRes && $row = $secRes->fetch_assoc()) {
        $section_name = $row['name'];
    }
    $secStmt->close();

    // Insert into students
    $stmt = $conn->prepare("INSERT INTO students
    (student_id, first_name, middle_name, last_name, birthday, gender, email, phone, home_address,
    guardian_name, guardian_address, emergency_contact, relationship, course, year_level, section, password, profile_picture, section_id,
    vaccine_record, medical_history, is_responder, responder_status)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    $stmt->bind_param(
        "ssssssssssssssssssissis",
        $student_id,
        $first_name,
        $middle_name,
        $last_name,
        $birthday,
        $gender,
        $email,
        $phone,
        $home_address,
        $guardian_name,
        $guardian_address,
        $emergency_contact,
        $relationship,
        $course,
        $year_level,
        $section_name,
        $password,
        $profile_picture,
        $section_id,
        $vaccine_record,
        $medical_history,
        $is_responder,
        $responder_status
    );

    if ($stmt->execute()) {
        // If registered as responder, add to emergency_responders table
        if ($is_responder == 1) {
            $responder_name = $first_name . " " . $last_name;
            $responder_stmt = $conn->prepare("INSERT INTO emergency_responders (name, status, phone) VALUES (?, ?, ?)");
            $responder_stmt->bind_param("sss", $responder_name, $responder_status, $phone);
            $responder_stmt->execute();
            $responder_stmt->close();
        }
        header("Location: studentfile_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #DB910B, #FF5300, #BB1D1D);
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            background: rgba(255, 255, 255, 0.5);
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 { text-align: center; margin-bottom: 20px; }
        label { display: block; margin-top: 10px; font-weight: bold; }
        input, select, button {
            width: 100%; box-sizing: border-box; padding: 10px;
            margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px;
        }
        button {
            background-color: #4CAF50; color: white; font-size: 16px;
            cursor: pointer; margin-top: 15px; border: none;
        }
        button:hover { background-color: #45a049; }
        .back-btn { background-color: #f44336; margin-bottom: 10px; }
        .back-btn:hover { background-color: #d7372b; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Registration</h1>
        <form action="registration.php" method="post" enctype="multipart/form-data">
            <label for="studentID">Student ID:</label>
            <input type="text" id="studentID" name="studentID" required>

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="middleName">Middle Name:</label>
            <input type="text" id="middleName" name="middleName">

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="birthday">Birthday:</label>
            <input type="date" id="birthday" name="birthday" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="" disabled selected>Select gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone No:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="homeAddress">Home Address:</label>
            <input type="text" id="homeAddress" name="homeAddress" required>

            <label for="guardiansName">Guardian's Name:</label>
            <input type="text" id="guardiansName" name="guardiansName" required>

            <label for="guardiansAddress">Guardian's Home Address:</label>
            <input type="text" id="guardiansAddress" name="guardiansAddress" required>

            <label for="contactNo">Emergency Contact:</label>
            <input type="tel" id="contactNo" name="contactNo" required>

            <label for="relationship">Relationship to Student:</label>
            <input type="text" id="relationship" name="relationship" required>

            <label for="course">Course:</label>
            <input type="text" id="course" name="course" required>

            <label for="yearLevel">Year Level:</label>
            <input type="text" id="yearLevel" name="yearLevel" required>

            <label for="section">Section:</label>
            <select id="section" name="section" required>
                <option value="" disabled selected>Select section</option>
                <?php foreach ($sections as $sec): ?>
                    <option value="<?= $sec['id'] ?>">
                        <?= htmlspecialchars($sec['name']) ?> - <?= htmlspecialchars($sec['semester']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="createPassword">Create Password:</label>
            <input type="password" id="createPassword" name="createPassword" required>

            <label for="confirmPassword">Confirm Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <label for="profilePicture">Profile:</label>
            <input type="file" id="profilePicture" name="profilePicture">

            <label for="vaccineRecord">Vaccine Record:</label>
            <input type="file" id="vaccineRecord" name="vaccineRecord">

            <label for="medicalHistory">Medical History:</label>
            <input type="file" id="medicalHistory" name="medicalHistory">

            <!-- ✅ Responder Role -->
            <label for="isResponder">Emergency Responder:</label>
            <input type="checkbox" id="isResponder" name="isResponder" value="1">

            <!-- Back and Register Buttons -->
            <button type="button" class="back-btn" onclick="window.history.back();">Cancel</button>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
