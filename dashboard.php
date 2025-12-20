<?php
require_once "includeDB.inc.php";
require_login();

header("Content-Type: application/json");

$user_id = $_SESSION["user_id"];

$sql = "
SELECT 
    de.id,
    de.drive_date,
    de.drive_time,
    de.distance_km,
    de.notes,
    w.label AS weather,
    t.label AS traffic,
    r.label AS road
FROM driving_experience de
JOIN weather w ON de.weather_id = w.id
JOIN traffic_level t ON de.traffic_id = t.id
JOIN road_type r ON de.road_type_id = r.id
WHERE de.user_id = :uid
ORDER BY de.drive_date DESC, de.drive_time DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([":uid" => $user_id]);
$experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql_surf = "
SELECT sc.label
FROM experience_surface es
JOIN surface_condition sc ON sc.id = es.surface_id
WHERE es.experience_id = :eid
";
$stmt_surf = $pdo->prepare($sql_surf);

foreach ($experiences as &$exp) {
    $stmt_surf->execute([":eid" => $exp["id"]]);
    $exp["surfaces"] = $stmt_surf->fetchAll(PDO::FETCH_COLUMN) ?: [];
}
unset($exp);

$total_km = array_sum(array_column($experiences, "distance_km"));
$weatherCounts = [];
$trafficCounts = [];
$roadCounts = [];

foreach ($experiences as $exp) {
    $weatherCounts[$exp["weather"]] = ($weatherCounts[$exp["weather"]] ?? 0) + 1;
    $trafficCounts[$exp["traffic"]] = ($trafficCounts[$exp["traffic"]] ?? 0) + 1;
    $roadCounts[$exp["road"]]       = ($roadCounts[$exp["road"]] ?? 0) + 1;
}


echo json_encode([
    "experiences"   => $experiences,
    "total_km"      => $total_km,
    "weatherCounts" => $weatherCounts,
    "trafficCounts" => $trafficCounts,
    "roadCounts"    => $roadCounts
]);

