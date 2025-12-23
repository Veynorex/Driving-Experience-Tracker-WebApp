<?php
session_start();

$dsn = "mysql:host=mysql-huseynpashayev.alwaysdata.net;
        dbname=huseynpashayev_project;
        charset=utf8mb4";

$username = "443305";
$password = "Mtt-6454610";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $pass     = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    if ($pass !== $confirm) {
        die("Error: Passwords do not match.");
    }

    $check = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e");
    $check->execute([
        ":u" => $username,
        ":e" => $email
    ]);

    if ($check->fetch()) {
        die("Error: Username or Email already exists.");
    }

    $hashed = password_hash($pass, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (username, email, password_hash)
              VALUES (:username, :email, :password_hash)";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
        ":username"      => $username,
        ":email"         => $email,
        ":password_hash" => $hashed
    ]);

    echo "Registration successful! <a href='../public/login.html'>Login here</a>";
    exit;
}
?>

