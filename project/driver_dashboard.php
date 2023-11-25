<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="style.css">

<?php 
session_start();
if (!isset($_SESSION["auth"]) && ($_SESSION["auth"]!=1)){
 header('location:driver_login.php');
 exit('Not authorised');
}

// Include the database connection
require('db_connect.php');

// Check if the driver has already accepted a ride
$driverId = $_SESSION["user_id"];
$acceptedRideQuery = "SELECT * FROM rides WHERE driver_id = ? AND status = 'accepted' OR status = 'arrived' OR status = 'picked up' ";
$acceptedRideStmt = $conn->prepare($acceptedRideQuery);
$acceptedRideStmt->bind_param("i", $driverId);
$acceptedRideStmt->execute();
$acceptedRideResult = $acceptedRideStmt->get_result();

// If driver has accepted a ride, redirect to accept_ride.php
if (mysqli_num_rows($acceptedRideResult) > 0) {
    header('location:accept_ride.php');
    exit;
}

// Retrieve pending ride requests from the passengers' database
$query = "SELECT * FROM rides WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

// Check if there are any pending ride requests
if (mysqli_num_rows($result) > 0) {
    echo "<table class='table table-hover table-bordered' style='position: relative; border-collapse: separate;'>
           <thead class='table-dark' style='position: sticky; top: 0;' >
            <tr id='head' style='text-align:center;'>
                <th>Ride ID</th>
                <th>Passenger ID</th>
                <th>Pickup Location</th>
                <th>Dropoff Location</th>
                <th>Distance (km)</th>
                <th>Cost ($)</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>";

    while ($row = mysqli_fetch_assoc($result)) {
        $rideId = $row['ride_id'];
        $passengerId = $row['passenger_id'];
        $pickupLocation = $row['pickup_location'];
        $dropoffLocation = $row['dropoff_location'];
        $distance = $row['distance'];
        $cost = $row['cost'];
        $status = $row['status'];
        $createdAt = $row['created_at'];

        echo "<tr>";
        echo "<td>$rideId</td>";
        echo "<td>$passengerId</td>";
        echo "<td>$pickupLocation</td>";
        echo "<td>$dropoffLocation</td>";
        echo "<td>$distance</td>";
        echo "<td>$cost</td>";
        echo "<td>$status</td>";
        echo "<td>$createdAt</td>";
        echo "<td><button type='submit' onclick='confirmAccept($rideId)' style='width:90px;'>Accept</button></td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br><br><br><center><a class='btn btn-dark text-decoration-none link-light' href='driver_logout.php' id='backbut2' style='width:120px;' role='button'>Logout</a><center>";
} else {
    echo "<br><br><br><center><b>No pending ride requests available.</b></center><br><br>";
    echo "<br><center><a class='btn btn-dark text-decoration-none link-light' href='driver_logout.php' id='backbut2' style='width:120px;' role='button'>Logout</a><center>";
}

mysqli_close($conn);
?>

<script>
function confirmAccept(rideId) {
    if (confirm("Are you sure you want to accept this ride request?")) {
        // If user confirms, redirect to the accept_ride.php page with the rideId
        window.location.href = "accept_ride.php?rideId=" + rideId;
    }
}
</script>


