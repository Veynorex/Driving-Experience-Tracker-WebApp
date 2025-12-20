<?php
require_once "includeDB.inc.php"; 
require_login();


$user_id      = $_SESSION["user_id"];
$drive_date   = $_POST["drive_date"] ?? null;
$drive_time   = $_POST["drive_time"] ?? null;
$distance_km  = $_POST["distance_km"] ?? null;
$weather_id   = $_POST["weather_id"] ?? null;
$traffic_id   = $_POST["traffic_id"] ?? null;
$road_type_id = $_POST["road_type_id"] ?? null;
$notes        = trim($_POST["notes"] ?? "");
$surface_ids  = $_POST["surface_ids"] ?? [];

if (!$drive_date || !$drive_time || !$distance_km || !$weather_id || !$traffic_id || !$road_type_id) {
    die("Invalid input. Please go back and fill all required fields.");
}

if ($distance_km <= 0) {
    die("Distance must be positive.");
}

try {
    $sql = "INSERT INTO driving_experience 
            (user_id, drive_date, drive_time, distance_km, weather_id, traffic_id, road_type_id, notes) 
            VALUES 
            (:uid, :d_date, :d_time, :dist, :weather, :traffic, :road, :notes)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":uid"     => $user_id,
        ":d_date"  => $drive_date,
        ":d_time"  => $drive_time,
        ":dist"    => $distance_km,
        ":weather" => $weather_id,
        ":traffic" => $traffic_id,
        ":road"    => $road_type_id,
        ":notes"   => $notes
    ]);

    $experience_id = $pdo->lastInsertId();

    if (!empty($surface_ids)) {
        $sql = "INSERT INTO experience_surface (experience_id, surface_id)
                VALUES (:exp_id, :surf_id)";
        $stmt2 = $pdo->prepare($sql);

        foreach ($surface_ids as $surface_id) {
            $stmt2->execute([
                ":exp_id"  => $experience_id,
                ":surf_id" => $surface_id
            ]);
        }
    }

    header("Location: landing.php?success=1");
    exit();

} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}
?>

