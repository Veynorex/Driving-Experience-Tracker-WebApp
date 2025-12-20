<?php
require_once "includeDB.inc.php";

header("Content-Type: application/json");

$options = [
    "weather" => [],
    "traffic" => [],
    "road"    => [],
    "surface" => []
];


$options["weather"] = $pdo->query("SELECT id, label FROM weather")->fetchAll(PDO::FETCH_ASSOC);
$options["traffic"] = $pdo->query("SELECT id, label FROM traffic_level")->fetchAll(PDO::FETCH_ASSOC);
$options["road"]    = $pdo->query("SELECT id, label FROM road_type")->fetchAll(PDO::FETCH_ASSOC);
$options["surface"] = $pdo->query("SELECT id, label FROM surface_condition")->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($options);

