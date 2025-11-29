<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo "Please submit the form to create the user.";
  exit;
}


$host = 'localhost';
$db   = 'capstone1';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';


$id = 2022001;
$username = 'nurse2022';
$email = 'nurse2022@example.com';
$plainPassword = '012345';
$role = 'nurse';


$hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    
    $stmt = $pdo->prepare("INSERT INTO users (id, username, email, password_hash, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id, $username, $email, $hashedPassword, $role]);

    echo "✅ Nurse user inserted with ID: $id";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
