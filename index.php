<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    include 'login.html';
    exit;
}

$id = $_POST['ID'] ?? '';
$password = $_POST['password'] ?? '';

$host = getenv("MYSQLHOST") ?: getenv("DB_HOST");
$port = getenv("MYSQLPORT") ?: getenv("DB_PORT") ?: 3306;
$user = getenv("MYSQLUSER") ?: getenv("DB_USER");
$pass = getenv("MYSQLPASSWORD") ?: getenv("DB_PASS");
$db   = getenv("MYSQLDATABASE") ?: getenv("DB_NAME");

try {
   $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $pdo->prepare("SELECT u.*, r.name AS role_name 
                           FROM users u 
                           LEFT JOIN roles r ON u.role_id = r.id
                           WHERE u.id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'];
        $_SESSION['role'] = $user['role_name'];


        if ($user['role_name'] === 'nurse') {
            $update_stmt = $pdo->prepare("UPDATE users SET last_active = NOW(), last_logout = NULL WHERE id = ?");
            $update_stmt->execute([$user['id']]);
            header("Location: nurse.php");
            exit;
        } elseif ($user['role_name'] === 'admin') {
            header("Location: admin.php");
            exit;
        }
    } else {
        echo "<script>alert('User not found or password incorrect for ID: $id');</script>";
    }

    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['username'] = $student['first_name'] . " " . $student['last_name'];

        
        if (!empty($student['is_responder']) && $student['is_responder'] == 1) {
            $_SESSION['role'] = 'responder';
           
            $responder_name = $student['first_name'] . " " . $student['last_name'];
            $update_sql = "UPDATE emergency_responders SET status = 'Active', last_active = NOW() WHERE name = ?";
            $stmt_update = $pdo->prepare($update_sql);
            $stmt_update->execute([$responder_name]);
            
            $stmt_resp = $pdo->prepare("SELECT id FROM emergency_responders WHERE name = ?");
            $stmt_resp->execute([$responder_name]);
            $resp = $stmt_resp->fetch(PDO::FETCH_ASSOC);
            if ($resp) {
                $_SESSION['responder_id'] = $resp['id'];
            }
            
            $stmt_user = $pdo->prepare("SELECT id FROM users WHERE name = ?");
            $stmt_user->execute([$responder_name]);
            $user = $stmt_user->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
            }
        } else {
            $_SESSION['role'] = 'student';
        }

        
        header("Location: student_profile.php?id=" . $student['student_id']);
        exit;
    }

   
    echo "<script>alert('Invalid ID or password.'); window.location.href = 'login.html';</script>";

} catch (PDOException $e) {
    echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "'); window.location.href = 'login.html';</script>";
}
?>
