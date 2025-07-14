<?php
// Function to calculate power, energy, and total charge
function calculateElectricityCharge($voltage, $current, $ratePerUnit, $hours = 1)
{
    // Power in Watts (W)
    $power = $voltage * $current;

    // Convert power to kW (kilowatts)
    $powerInKW = $power / 1000;

    // Energy in kWh
    $energy = $powerInKW * $hours;

    // Convert rate from sen to RM (1 RM = 100 sen)
    $rateInRM = $ratePerUnit / 100;

    // Total charge for the given number of hours
    $totalCharge = $energy * $rateInRM;

    // Calculate the total charge per hour (based on 1 hour)
    $totalChargePerHour = $powerInKW * $rateInRM;

    // Calculate the total charge per day (24 hours)
    $totalChargePerDay = $totalChargePerHour * 24;

    return [
        'power' => $powerInKW,
        'energy' => $energy,
        'totalCharge' => $totalCharge,
        'totalChargePerHour' => $totalChargePerHour,
        'totalChargePerDay' => $totalChargePerDay
    ];
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voltage = $_POST['voltage'];
    $current = $_POST['current'];
    $ratePerUnit = $_POST['rate'];

    // Store results for each hour in an array
    $results = [];
    for ($hours = 1; $hours <= 24; $hours++) {
        $result = calculateElectricityCharge($voltage, $current, $ratePerUnit, $hours);
        $results[] = $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Consumption Charge Calculator</title>
    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Electricity Consumption Charge Calculator</h1>

        <!-- Form to collect user input -->
        <form method="POST">
            <div class="form-group">
                <label for="voltage">Voltage (V):</label>
                <input type="number" class="form-control" id="voltage" name="voltage" step="any" required>
            </div>
            <div class="form-group">
                <label for="current">Current (A):</label>
                <input type="number" class="form-control" id="current" name="current" step="any" required>
            </div>
            <div class="form-group">
                <label for="rate">Rate per unit (in sen/kWh):</label>
                <input type="number" class="form-control" id="rate" name="rate" step="any" required>
            </div>
            <button type="submit" class="btn btn-primary">Calculate</button>
        </form>

        <!-- Display results for a single hour -->
        <?php if (isset($results[0])): ?>
            <div class="mt-4">
                <h3>Results (for 1 hour):</h3>
                <ul class="list-group">
                    <li class="list-group-item">Power: <?= number_format($results[0]['power'], 5) ?> kW</li>
                    <li class="list-group-item">Energy: <?= number_format($results[0]['energy'], 4) ?> kWh</li>
                    <li class="list-group-item">Total Charge (for 1 hour): RM <?= number_format($results[0]['totalCharge'], 2) ?></li>
                    <li class="list-group-item">Rate per Hour: RM <?= number_format($results[0]['totalChargePerHour'], 2) ?></li>
                    <li class="list-group-item">Rate per Day: RM <?= number_format($results[0]['totalChargePerDay'], 2) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Display results in a table for hours 1 to 24 -->
        <?php if (isset($results) && !empty($results)): ?>
            <div class="mt-4">
                <h3>Results for each hour (1 to 24 hours):</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Hour</th>
                            <th scope="col">Energy (kWh)</th>
                            <th scope="col">Total Charge (RM)</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $hour => $result): ?>
                            <tr>
                                <td><?= $hour + 1 ?> </td>
                                <td><?= number_format($result['energy'], 5) ?> kWh</td>
                                <td>RM <?= number_format($result['totalCharge'], 2) ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>