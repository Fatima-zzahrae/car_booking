<?php
include('db_connect.php');
session_start();

if (!isset($_SESSION["auth"]) && ($_SESSION["auth"] != 1)) {
    header('location:passenger_login.php');
    exit('Not authorised');
}

$passengerId = $_SESSION["user_id"];

$query = "SELECT * FROM rides WHERE passenger_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $passengerId);
$stmt->execute();
$result = $stmt->get_result();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_ride'])) {
    $rideId = $_POST['ride_id'];
    $passengerId = $_SESSION['user_id'];

    // Check if the ride is pending
    $checkQuery = "SELECT * FROM rides WHERE ride_id = ? AND passenger_id = ? AND status = 'pending'";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("ii", $rideId, $passengerId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        // Cancel the "pending" ride
        $updateQuery = "UPDATE rides SET status = 'cancelled' WHERE ride_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("i", $rideId);

        if ($updateStmt->execute()) {
            // Ride successfully cancelled
        } else {
            echo "Error updating ride status: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        // The ride is not pending or doesn't exist
    }

    $checkStmt->close();
    header("Location: ride_history.php");
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride History</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table class="table table-hover table-bordered" style="position: relative; border-collapse: separate;">
        <thead class="table-dark" style="position: sticky; top: 0;" >
        <tr id="head" style="text-align:center;">
            <th>Ride ID</th>
            <th>Pickup Location</th>
            <th>Dropoff Location</th>
            <th>Distance (km)</th>
            <th>Cost ($)</th>
            <th>Status</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
        </thead>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tbody>
            <tr>
                <td><?php echo $row['ride_id']; ?></td>
                <td><?php echo $row['pickup_location']; ?></td>
                <td><?php echo $row['dropoff_location']; ?></td>
                <td><?php echo $row['distance']; ?></td>
                <td><?php echo $row['cost']; ?></td>
                <td><b><?php echo $row['status']; ?></b></td>
                <td><?php echo $row['created_at']; ?></td>
                <?php if ($row['status'] === 'pending') { ?>
            <td>
                <form method="post">
                    <input type="hidden" name="ride_id" value="<?php echo $row['ride_id']; ?>">
                    <button type="submit" name="cancel_ride" style="width:100px;">Cancel</button>
                </form>
            </td>
        <?php } else { ?>
            <td></td> <!-- Empty cell for non-pending rides -->
        <?php } ?>
            </tr>
        </tbody>
        <?php } ?>
    </table>
    <br>
    <center>
    <a class="btn btn-dark text-decoration-none link-light" href="passenger_dashboard.php" id="backbut2" style="width:120px;" role="button">Back</a>
    <center>
    <br><br>
    <script src="js/bootstrap.js"></script>
</body>
</html>
