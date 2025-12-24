<?php
session_start();
require 'includes/db.php';

$pdo = Database::getConnection();

/* -----------------------------
   1. Fetch summary table data
------------------------------*/
$sql = "
SELECT 
    d.date,
    d.start_time,
    d.end_time,
    d.distance_km,
    w.weather,
    r.road_condition,
    p.method AS parking_type,
    GROUP_CONCAT(e.type SEPARATOR ', ') AS emergencies
FROM driving_experience d
JOIN weather w ON d.weather_id = w.id
JOIN road_condition r ON d.road_condition_id = r.id
JOIN parking_type p ON d.parking_type_id = p.id
LEFT JOIN experience_emergency de ON d.id = de.experience_id
LEFT JOIN emergency_type e ON de.emergency_id = e.id
GROUP BY d.id
ORDER BY d.date DESC
";

$stmt = $pdo->query($sql);
$experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* -----------------------------
   2. Total distance
------------------------------*/
$totalKmStmt = $pdo->query("SELECT SUM(distance_km) FROM driving_experience");
$totalKm = $totalKmStmt->fetchColumn();

/* -----------------------------
   3. Charts data (aggregation)
------------------------------*/

// Distance by weather
$stmt = $pdo->query("
    SELECT w.weather AS label, SUM(d.distance_km) AS total
    FROM driving_experience d
    JOIN weather w ON d.weather_id = w.id
    GROUP BY w.id
");
$weatherStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Road conditions
$stmt = $pdo->query("
    SELECT r.road_condition AS label, COUNT(*) AS total
    FROM driving_experience d
    JOIN road_condition r ON d.road_condition_id = r.id
    GROUP BY r.id
");
$roadStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Parking types
$stmt = $pdo->query("
    SELECT p.method AS label, COUNT(*) AS total
    FROM driving_experience d
    JOIN parking_type p ON d.parking_type_id = p.id
    GROUP BY p.id
");
$parkingStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Emergency types (many-to-many)
$stmt = $pdo->query("
    SELECT e.type AS label, COUNT(*) AS total
    FROM experience_emergency de
    JOIN emergency_type e ON de.emergency_id = e.id
    GROUP BY e.id
");
$emergencyStats = $stmt->fetchAll(PDO::FETCH_ASSOC);

function chartData($data) {
    return [
        'labels' => array_column($data, 'label'),
        'values' => array_column($data, 'total')
    ];
}

$weatherChart   = chartData($weatherStats);
$roadChart      = chartData($roadStats);
$parkingChart   = chartData($parkingStats);
$emergencyChart = chartData($emergencyStats);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driving Experience Summary</title>

    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/charts.js"></script>

</head>

<body>

<h1>Driving Experience Summary</h1>

<div class="total-box">
    Total Distance: <?= number_format($totalKm ?? 0, 2) ?> km
</div>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Road Condition</th>
            <th>Weather</th>
            <th>Parking Type</th>
            <th>Emergency Types</th>
            <th>Distance (km)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($experiences)): ?>
            <tr>
                <td colspan="8">No driving experiences recorded.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($experiences as $exp): ?>
                <tr>
                    <td><?= htmlspecialchars($exp['date']) ?></td>
                    <td><?= htmlspecialchars($exp['start_time']) ?></td>
                    <td><?= htmlspecialchars($exp['end_time']) ?></td>
                    <td><?= htmlspecialchars($exp['road_condition']) ?></td>
                    <td><?= htmlspecialchars($exp['weather']) ?></td>
                    <td><?= htmlspecialchars($exp['parking_type']) ?></td>
                    <td><?= htmlspecialchars($exp['emergencies'] ?? 'None') ?></td>
                    <td><?= htmlspecialchars($exp['distance_km']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<h2>Statistics</h2>

<section class="statistics-container">

    <section class="chart-row">
        <article class="chart-container">
            <h3>Distance by Weather</h3>
            <canvas id="weatherChart" width="600" height="300"></canvas>
        </article>

        <article class="chart-container">
            <h3>Road Conditions</h3>
           <canvas id="roadChart" width="600" height="300"></canvas>

        </article>
    </section>

    <section class="chart-row">
        <article class="chart-container">
            <h3>Parking Types</h3>
            <canvas id="parkingChart" width="600" height="300"></canvas>
        </article>

        <article class="chart-container">
            <h3>Emergency Types</h3>
            <canvas id="emergencyChart" width="600" height="300"></canvas>
        </article>
    </section>

</section>


<div class="buttons">
    <button onclick="window.location.href='index.php'">Add new data</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const weatherData   = <?= json_encode($weatherChart) ?>;
    const roadData      = <?= json_encode($roadChart) ?>;
    const parkingData   = <?= json_encode($parkingChart) ?>;
    const emergencyData = <?= json_encode($emergencyChart) ?>;

    // Distance by Weather (BAR)
    new Chart(document.getElementById('weatherChart'), {
        type: 'bar',
        data: {
            labels: weatherData.labels,
            datasets: [{
                label: 'Total km',
                data: weatherData.values,
                backgroundColor: 'rgba(75, 192, 192, 0.6)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Road Conditions (PIE)
    new Chart(document.getElementById('roadChart'), {
        type: 'pie',
        data: {
            labels: roadData.labels,
            datasets: [{
                data: roadData.values,
                backgroundColor: ['#B5838D', '#6D6875', '#598392', '#014F86']
            }]
        }
    });

    // Parking Types (PIE)
    new Chart(document.getElementById('parkingChart'), {
        type: 'pie',
        data: {
            labels: parkingData.labels,
            datasets: [{
                data: parkingData.values,
                backgroundColor: ['#9C6644', '#FFAFCC', '#6A5ACD']
            }]
        }
    });

    // Emergency Types (PIE)
    new Chart(document.getElementById('emergencyChart'), {
        type: 'pie',
        data: {
            labels: emergencyData.labels,
            datasets: [{
                data: emergencyData.values,
                backgroundColor: ['#014F86', '#598392', '#6D6875']
            }]
        }
    });

});
</script>


</body>
</html>
