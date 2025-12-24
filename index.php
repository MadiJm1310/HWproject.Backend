<?php
session_start();
require 'includes/db.php';

$pdo = Database::getConnection();

// Fetch dropdown data from database
$weathers = $pdo->query("SELECT id, weather FROM weather")->fetchAll(PDO::FETCH_ASSOC);
$roads = $pdo->query("SELECT id, road_condition FROM road_condition")->fetchAll(PDO::FETCH_ASSOC);
$parkings = $pdo->query("SELECT id, method FROM parking_type")->fetchAll(PDO::FETCH_ASSOC);
$emergencies = $pdo->query("SELECT id, type FROM emergency_type")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driving Experience Form</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>

<?php if (!empty($_SESSION['error'])): ?>
    <p style="color:red"><?= htmlspecialchars($_SESSION['error']) ?></p>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <p style="color:green"><?= htmlspecialchars($_SESSION['success']) ?></p>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<form method="POST" action="experience_save.php">
    <h2>Driving Experience Form</h2>

    <label>Date</label>
    <input type="date" name="date" required value="<?= date('Y-m-d') ?>">

    <label>Start Time</label>
    <input type="time" name="start_time" required>

    <label>End Time</label>
    <input type="time" name="end_time" required>

    <label>Distance (km)</label>
    <input type="number" name="distance" step="0.1" required>

    <select name="weather_id" required>
        <option value="">Select Weather Condition</option>
        <?php foreach ($weathers as $w): ?>
            <option value="<?= $w['id'] ?>"><?= htmlspecialchars($w['weather']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="road_condition_id" required>
        <option value="">Select Road Condition</option>
        <?php foreach ($roads as $r): ?>
            <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['road_condition']) ?></option>
        <?php endforeach; ?>
    </select>

    <select name="parking_type_id" required>
        <option value="">Select Parking Type</option>
        <?php foreach ($parkings as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['method']) ?></option>
        <?php endforeach; ?>
    </select>

    <label>Emergency Types (multiple)</label>
    <select name="emergency_ids[]" multiple required>
        <?php foreach ($emergencies as $e): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['type']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Submit</button>
    <button type="button" onclick="window.location.href='summary.php'">Show Data</button>
</form>

<footer>
    <p>Created by Madina Mammadova. All rights reserved.</p>
</footer>

<script>
    // Client-side time validation 
    document.querySelector('form').addEventListener('submit', function (e) {
        const start = document.querySelector('[name="start_time"]').value;
        const end = document.querySelector('[name="end_time"]').value;
        if (end <= start) {
            alert("End time must be after start time.");
            e.preventDefault();
        }
    });
</script>

</body>
</html>
