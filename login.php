<?php
session_start();

$dsn = "mysql:host=mysql-huseynpashayev.alwaysdata.net;
        dbname=huseynpashayev_project;
        charset=utf8mb4";

$db_user = "443305";
$db_pass = "Mtt-6454610";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$email = trim($_POST["email"]);
$password = $_POST["password"];

$sql = "SELECT id, username, email, password_hash 
        FROM users 
        WHERE email = :email";

$stmt = $pdo->prepare($sql);
$stmt->execute([":email" => $email]);
$user = $stmt->fetch();

if (!$user) {
    die("Invalid email or password. <a href='login.html'>Try again</a>");
}

if (!password_verify($password, $user["password_hash"])) {
    die("Invalid email or password. <a href='login.html'>Try again</a>");
}

$_SESSION["user_id"] = $user["id"];
$_SESSION["username"] = $user["username"];
$_SESSION["email"] = $user["email"];

header("Location: landing.php");
exit;

