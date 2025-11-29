<?php
// Railway MySQL environment variables
$host = getenv("MYSQLHOST") ?: getenv("DB_HOST");
$port = getenv("MYSQLPORT") ?: getenv("DB_PORT");
$user = getenv("MYSQLUSER") ?: getenv("DB_USER");
$pass = getenv("MYSQLPASSWORD") ?: getenv("DB_PASS");
$db   = getenv("MYSQLDATABASE") ?: getenv("DB_NAME");

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("DB Connection Failed: " . mysqli_connect_error());
}
?>