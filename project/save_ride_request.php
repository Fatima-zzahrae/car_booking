<?php 
session_start();
if (!isset($_SESSION["auth"]) && ($_SESSION["auth"]!=1)){
 header('location:passenger_login.php');
 exit('Not authorised');
}
?>

<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passengerId = $_POST['passengerId'];
    $departure = $_POST['from'];
    $arrival = $_POST['to'];
    $distance = $_POST['distance'];
    $cost = $_POST['cost'];
    $status = 'pending';
    $createdAt = date('Y-m-d H:i:s');

    // Insert the ride request into the database
    $query = "INSERT INTO rides (passenger_id, pickup_location, dropoff_location, distance, cost, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    // Bind parameters to the statement
    $stmt->bind_param("issddss", $passengerId, $departure, $arrival, $distance, $cost, $status, $createdAt);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Ride request saved successfully";
    } else {
        echo "Error saving ride request";
    }

    $stmt->close();
}

$conn->close();
?>