<?php
require_once "../includes/includeDB.inc.php";
require_login();

header("Content-Type: application/json");

$user_id = $_SESSION["user_id"];

$params = [":uid" => $user_id];
$conditions = ["de.user_id = :uid"];

if (!empty($_GET["from"])) {
    $conditions[] = "de.drive_date >= :from";
    $params[":from"] = $_GET["from"];
}

if (!empty($_GET["to"])) {
    $conditions[] = "de.drive_date <= :to";
    $params[":to"] = $_GET["to"];
}

if (!empty($_GET["weather"])) {
    $conditions[] = "de.weather_id = :weather";
    $params[":weather"] = $_GET["weather"];
}

if (!empty($_GET["traffic"])) {
    $conditions[] = "de.traffic_id = :traffic";
    $params[":traffic"] = $_GET["traffic"];
}

if (!empty($_GET["road"])) {
    $conditions[] = "de.road_type_id = :road";
    $params[":road"] = $_GET["road"];
}

$whereSQL = implode(" AND ", $conditions);

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
WHERE $whereSQL
ORDER BY de.drive_date DESC, de.drive_time DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmtSurface = $pdo->prepare("
    SELECT sc.label
    FROM experience_surface es
    JOIN surface_condition sc ON sc.id = es.surface_id
    WHERE es.experience_id = :eid
");

foreach ($experiences as &$exp) {
    $stmtSurface->execute([":eid" => $exp["id"]]);
    $exp["surfaces"] = $stmtSurface->fetchAll(PDO::FETCH_COLUMN);
}
unset($exp);

$total_km = array_sum(array_column($experiences, "distance_km"));

$trafficCounts = [];
$roadCounts = [];
$weatherCounts = [];
$surfaceCounts = [];

foreach ($experiences as $exp) {
    $trafficCounts[$exp["traffic"]] = ($trafficCounts[$exp["traffic"]] ?? 0) + 1;
    $roadCounts[$exp["road"]] = ($roadCounts[$exp["road"]] ?? 0) + 1;
    $weatherCounts[$exp["weather"]] = ($weatherCounts[$exp["weather"]] ?? 0) + 1;

    foreach ($exp["surfaces"] as $s) {
        $surfaceCounts[$s] = ($surfaceCounts[$s] ?? 0) + 1;
    }
}

echo json_encode([
    "experiences"    => $experiences,
    "total_km"       => $total_km,
    "trafficCounts"  => $trafficCounts,
    "roadCounts"     => $roadCounts,
    "weatherCounts"  => $weatherCounts,
    "surfaceCounts"  => $surfaceCounts
]);
