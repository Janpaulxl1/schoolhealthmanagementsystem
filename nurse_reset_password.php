<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'nurse') {
    header("Location: login.html");
    exit();
}
require_once 'db.php';

// Fetch all students for dropdown
$students = [];
$sql = "SELECT id, student_id, first_name, last_name FROM students ORDER BY first_name ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CPC Clinic</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poltawski+Nowy:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poltawski Nowy', serif;
            background: linear-gradient(to bottom, #E4A73E, #DD442D, #BB1D1D);
            color: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 1rem;
            padding: 3rem 2.5rem;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 500px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: #f0f0f0;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: none;
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
        }
        .btn {
            background-color: #f1d669;
            color: #333;
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: bold;
            cursor: pointer;
            font-size: 1.1rem;
        }
        .btn:hover {
            background-color: #fde047;
        }
        .back-btn {
            background-color: #6b7280;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            margin-bottom: 1rem;
        }
        .back-btn:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-btn" onclick="window.location.href='nurse.php'">← Back to Dashboard</button>
        <h2 class="text-2xl font-bold text-center mb-6">Reset Student Password</h2>
        <form action="nurse_reset_process.php" method="POST">
            <div class="form-group">
                <label for="student_id">Search Student</label>
                <input type="text" id="student_id" name="student_id" list="students" placeholder="Type student name or ID" required autocomplete="off">
                <datalist id="students">
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo htmlspecialchars($student['student_id']); ?>" label="<?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['student_id'] . ')'); ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    </div>
</body>
</html>
