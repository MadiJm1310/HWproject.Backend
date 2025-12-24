<?php
// experience_save.php
session_start();

require 'includes/db.php';

$pdo = Database::getConnection();

/* -------------------------
   1. Basic validation
--------------------------*/
$requiredFields = [
    'date',
    'start_time',
    'end_time',
    'distance',
    'weather_id',
    'road_condition_id',
    'parking_type_id',
    'emergency_ids'
];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error'] = 'Please fill in all required fields.';
        header('Location: index.php');
        exit;
    }
}

// Assign & sanitize
$date = $_POST['date'];
$startTime = $_POST['start_time'];
$endTime = $_POST['end_time'];
$distance = floatval($_POST['distance']);

$weatherId = (int) $_POST['weather_id'];
$roadId = (int) $_POST['road_condition_id'];
$parkingId = (int) $_POST['parking_type_id'];
$emergencyIds = $_POST['emergency_ids'];

/* -------------------------
   2. Logical validation
--------------------------*/
if ($distance <= 0) {
    $_SESSION['error'] = 'Distance must be greater than 0.';
    header('Location: index.php');
    exit;
}

if ($endTime <= $startTime) {
    $_SESSION['error'] = 'End time must be after start time.';
    header('Location: index.php');
    exit;
}

/* -------------------------
   3. Insert with transaction
--------------------------*/
try {
    $pdo->beginTransaction();

    // Insert driving experience
    $stmt = $pdo->prepare("
        INSERT INTO driving_experience
        (date, start_time, end_time, distance_km, weather_id, road_condition_id, parking_type_id)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $date,
        $startTime,
        $endTime,
        $distance,
        $weatherId,
        $roadId,
        $parkingId
    ]);

    // Get inserted experience ID
    $experienceId = $pdo->lastInsertId();

    // Insert emergencies (many-to-many)
    $stmtEmergency = $pdo->prepare("
        INSERT INTO experience_emergency (experience_id, emergency_id)
        VALUES (?, ?)
    ");

    foreach ($emergencyIds as $emergencyId) {
        $stmtEmergency->execute([
            $experienceId,
            (int) $emergencyId
        ]);
    }

    $pdo->commit();

    $_SESSION['success'] = 'Driving experience saved successfully.';
    header('Location: summary.php');
    exit;

} catch (Exception $e) {
    $pdo->rollBack();

    $_SESSION['error'] = 'An error occurred while saving the data.';
    header('Location: index.php');
    exit;
}
