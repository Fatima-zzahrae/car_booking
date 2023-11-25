<?php
// Include the database connection
include('db_connect.php');

// Check if a POST request with 'rideId' is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rideId'])) {
    $rideId = $_POST['rideId'];

    // Update the status of the ride to "arrived" in the database
    $updateStatusQuery = "UPDATE rides SET status = 'arrived' WHERE ride_id = ?";
    $updateStatusStmt = $conn->prepare($updateStatusQuery);
    $updateStatusStmt->bind_param("i", $rideId);

    if ($updateStatusStmt->execute()) {
        // Send a JSON response indicating success
        echo json_encode(['success' => true]);
        exit();
    } else {
        // Send a JSON response indicating an error
        echo json_encode(['success' => false, 'error' => 'Error updating ride status: ' . $updateStatusStmt->error]);
        exit();
    }

    $updateStatusStmt->close();
}

// Handle invalid or missing POST data
echo json_encode(['success' => false, 'error' => 'Invalid request.']);
?>
