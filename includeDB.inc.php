<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dsn = "mysql:host=mysql-huseynpashayev.alwaysdata.net;
        dbname=huseynpashayev_project;
        charset=utf8mb4";

$username = "443305";
$password = "Mtt-6454610";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Database connection failed.");
}

function require_login(): void {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.html");
        exit();
    }
}

