<?php
require_once __DIR__ . "/../includes/includeDB.inc.php";
require_login();

if (!isset($_GET["id"])) {
    echo "Invalid request.";
    exit();
}

$id = intval($_GET["id"]);
$user_id = $_SESSION["user_id"];

$stmt = $pdo->prepare("DELETE FROM experience_surface WHERE experience_id = ?");
$stmt->execute([$id]);

$stmt = $pdo->prepare("DELETE FROM driving_experience WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $user_id]);

echo "Experience deleted.";

