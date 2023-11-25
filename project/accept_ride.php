<?php
// Include the database connection
include('db_connect.php');
session_start();

if (!isset($_SESSION["auth"]) && ($_SESSION["auth"] != 1)) {
    header('location:driver_login.php');
    exit('Not authorised');
}

$driverId = $_SESSION["user_id"];

$acceptedRideQuery = "SELECT * FROM rides WHERE driver_id = ? AND status = 'accepted'";
$acceptedRideStmt = $conn->prepare($acceptedRideQuery);
$acceptedRideStmt->bind_param("i", $driverId);
$acceptedRideStmt->execute();
$acceptedRideResult = $acceptedRideStmt->get_result();

if (mysqli_num_rows($acceptedRideResult) > 0){
    $query = "SELECT ride_id FROM rides WHERE driver_id = ? AND status = 'accepted' ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $driverId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $rideId = $row['ride_id'];

        $query = "UPDATE rides SET status = 'accepted', driver_id = ? WHERE ride_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $driverId, $rideId);

    if ($stmt->execute()) {
        // Fetch ride details from the database
        $fetchQuery = "SELECT * FROM rides WHERE ride_id = ?";
        $fetchStmt = $conn->prepare($fetchQuery);
        $fetchStmt->bind_param("i", $rideId);
        $fetchStmt->execute();
        $rideDetails = $fetchStmt->get_result()->fetch_assoc();

        // Extract ride information
        $pickupLocation = $rideDetails['pickup_location'];
        $dropoffLocation = $rideDetails['dropoff_location'];
        $distance = $rideDetails['distance'];
        $cost = $rideDetails['cost'];

        $fetchStmt->close();
    }
    
    $stmt->close();
    }
}




$arrivedRideQuery = "SELECT * FROM rides WHERE driver_id = ? AND status = 'arrived'";
$arrivedRideStmt = $conn->prepare($arrivedRideQuery);
$arrivedRideStmt->bind_param("i", $driverId);
$arrivedRideStmt->execute();
$arrivedRideResult = $arrivedRideStmt->get_result();

if (mysqli_num_rows($arrivedRideResult) > 0){
    $query = "SELECT ride_id FROM rides WHERE driver_id = ? AND status = 'arrived' ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $driverId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $rideId = $row['ride_id'];

        $query = "UPDATE rides SET status = 'arrived', driver_id = ? WHERE ride_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $driverId, $rideId);

    if ($stmt->execute()) {
        // Fetch ride details from the database
        $fetchQuery = "SELECT * FROM rides WHERE ride_id = ?";
        $fetchStmt = $conn->prepare($fetchQuery);
        $fetchStmt->bind_param("i", $rideId);
        $fetchStmt->execute();
        $rideDetails = $fetchStmt->get_result()->fetch_assoc();

        // Extract ride information
        $pickupLocation = $rideDetails['pickup_location'];
        $dropoffLocation = $rideDetails['dropoff_location'];
        $distance = $rideDetails['distance'];
        $cost = $rideDetails['cost'];

        $fetchStmt->close();
    }
    
    $stmt->close();
    }
}






$pickedupRideQuery = "SELECT * FROM rides WHERE driver_id = ? AND status = 'picked up'";
$pickedupRideStmt = $conn->prepare($pickedupRideQuery);
$pickedupRideStmt->bind_param("i", $driverId);
$pickedupRideStmt->execute();
$pickedupRideResult = $pickedupRideStmt->get_result();

if (mysqli_num_rows($pickedupRideResult) > 0){
    $query = "SELECT ride_id FROM rides WHERE driver_id = ? AND status = 'picked up' ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $driverId);
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $rideId = $row['ride_id'];

        $query = "UPDATE rides SET status = 'picked up', driver_id = ? WHERE ride_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $driverId, $rideId);

    if ($stmt->execute()) {
        // Fetch ride details from the database
        $fetchQuery = "SELECT * FROM rides WHERE ride_id = ?";
        $fetchStmt = $conn->prepare($fetchQuery);
        $fetchStmt->bind_param("i", $rideId);
        $fetchStmt->execute();
        $rideDetails = $fetchStmt->get_result()->fetch_assoc();

        // Extract ride information
        $pickupLocation = $rideDetails['pickup_location'];
        $dropoffLocation = $rideDetails['dropoff_location'];
        $distance = $rideDetails['distance'];
        $cost = $rideDetails['cost'];

        $fetchStmt->close();
    }
    
    $stmt->close();
    }
}




if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['rideId'])) {
    $rideId = $_GET['rideId'];
    // Get the driver's ID from the session
    $driverId = $_SESSION['user_id'];

    // Update the status and set the driver ID of the ride in the database
    $query = "UPDATE rides SET status = 'accepted', driver_id = ? WHERE ride_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $driverId, $rideId);

    if ($stmt->execute()) {
        // Fetch ride details from the database
        $fetchQuery = "SELECT * FROM rides WHERE ride_id = ?";
        $fetchStmt = $conn->prepare($fetchQuery);
        $fetchStmt->bind_param("i", $rideId);
        $fetchStmt->execute();
        $rideDetails = $fetchStmt->get_result()->fetch_assoc();

        // Extract ride information
        $pickupLocation = $rideDetails['pickup_location'];
        $dropoffLocation = $rideDetails['dropoff_location'];
        $distance = $rideDetails['distance'];
        $cost = $rideDetails['cost'];

        $fetchStmt->close();
    } else {
        echo "Error accepting ride request: " . $stmt->error;
        $stmt->close();
        mysqli_close($conn);
        exit();
    }

    $stmt->close();
} 


mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accepted Ride Details</title>
    <!-- Include Google Maps JavaScript API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBT6_1gj-zGPI85qsCu5nADAKSYA6eJ40&libraries=places&callback=initMap"></script>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <style>
        h1{
            background-color: #000000; 
            color: #fff;
            padding: 15px 0px;
            margin-bottom: 0px;
        }

        #arrivedButton, #pickedupButton, #droppedoffButton{
            padding: 5px;
            margin: 10px;
        }
        </style>
</head>
<body>

    <center><h1>Ride information</h1></center>
    <div id="map" style="height: 430px;"></div>
    <div style="padding: 20px;">
    <p><b>Pickup Location:</b> <?php echo $pickupLocation; ?></p>
    <p><b>Dropoff Location:</b> <?php echo $dropoffLocation; ?></p>
    <p><b>Distance:</b> <?php echo $distance; ?> km</p>
    <p><b>Cost:</b> <?php echo $cost; ?> $</p>
    </div>

    <center>
    <button id="arrivedButton" onclick="this.disabled='true';">I arrived at the pickup location</button>
    <button id="pickedupButton">I picked up the passenger</button>
    <button id="droppedoffButton">I dropped off the passenger</button>
    <center>

    <br><br>
    <center>
   <a class="btn btn-dark text-decoration-none link-light" href="driver_logout.php" id="backbut2" role="button" style="width: 100px; margin-bottom:50px">Logout</a>
    <center>

    <script>
        
        // Function to handle the button click
    document.getElementById('arrivedButton').addEventListener('click', function() {
        // Send an AJAX request to update the ride status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'arrived_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful response
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Status updated successfully
                    alert('Ride status updated to "arrived".');
                    arrivedButton.disabled = true;
                } else {
                    // Handle any errors that occur during the update
                    alert('Error updating ride status: ' + response.error);
                }
            } else {

                alert('Error updating ride status: ' + xhr.status);
            }
        };

        // Send the rideId in the request
        var rideId = <?php echo $rideId; ?>;
        xhr.send('rideId=' + rideId);
    });





    // Function to handle the button click
    document.getElementById('pickedupButton').addEventListener('click', function() {
        // Send an AJAX request to update the ride status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'pickedup_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful response
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Status updated successfully
                    alert('Ride status updated to "picked up".');
                    pickedupButton.disabled = true;
                } else {
                    // Handle any errors that occur during the update
                    alert('Error updating ride status: ' + response.error);
                }
            } else {
                alert('Error updating ride status: ' + xhr.status);
            }
        };

        // Send the rideId in the request
        var rideId = <?php echo $rideId; ?>;
        xhr.send('rideId=' + rideId);
    });





    // Function to handle the button click
    document.getElementById('droppedoffButton').addEventListener('click', function() {
        // Send an AJAX request to update the ride status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'droppedoff_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Successful response
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Status updated successfully
                    alert('Ride status updated to "dropped off".');
                    droppedoffButton.disabled = true;
                } else {
                    // Handle any errors that occur during the update
                    alert('Error updating ride status: ' + response.error);
                }
            } else {
                alert('Error updating ride status: ' + xhr.status);
            }
        };

        // Send the rideId in the request
        var rideId = <?php echo $rideId; ?>;
        xhr.send('rideId=' + rideId);
    });



    

        // Initialize Google Map
        function initMap() {
            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer();
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -34.397, lng: 150.644},
                zoom: 14
            });
            directionsRenderer.setMap(map);

            // Calculate route and display on map
            var request = {
                origin: '<?php echo $pickupLocation; ?>',
                destination: '<?php echo $dropoffLocation; ?>',
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function(response, status) {
                if (status == 'OK') {
                    directionsRenderer.setDirections(response);
                }
            });
        }

        // Initialize the map when the page loads
        google.maps.event.addDomListener(window, 'load', initMap);
    </script>
    
    <script src="js/bootstrap.js"></script>
</body>
</html>
