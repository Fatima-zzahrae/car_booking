<?php 
session_start();
if (!isset($_SESSION["auth"]) && ($_SESSION["auth"]!=1)){
 header('location:passenger_login.php');
 exit('Not authorised');
}

require('db_connect.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ride dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color:white;">  
   <form id="distance-form">

   <div style="margin: 10px 0px 20px 100px;">
    <table>
<tr>
            <td><input class="border-bottom-0 border-dark border-2" type="text" id="from" placeholder="Origin"></td>
            <td><input class="border-dark border-2" type="text" id="to" placeholder="Destination"></td>
            <td></td>
            <td rowspan="2"><button type="button" class="yesbut" onclick="calculateDistance()"><i style="color:#fff;" class="fa-solid fa-check"></i> Validate</button></td>
            <td rowspan="2"><button class="nobut" onclick="clearAddressesAndMap()"><i style="color:#fff;" class="fa-solid fa-xmark"></i> Clear</button></td>
</tr>
<tr>
    <td><button style="width:330px;" type="button" onclick="useCurrentLocation()"><i class="fa-solid fa-location-crosshairs" style="color: #000000;"></i> Use my current location</button></td>
    <td></td>
    <td></td>
</tr>
</table>
</div>
        
        
<div id="map" style="height: 350px;"></div>

<table id="data-container">
    <tr>
        <td><div id="distance" style="background-color: lightyellow; padding: 5px 100px 5px 10px;"></div></td>
        <td rowspan='2'><button class="btn btn-dark" type="button" id="requestRideButton" disabled onclick="requestRide(<?php echo $_SESSION['user_id']; ?>)">Request a ride</button></td>
        <td rowspan='2'><a class="btn btn-dark text-decoration-none link-light" href="passenger_dashboard.php" id="backbut2" role="button">Back</a></td>
    </tr>
    <tr>
        <td><div id="cost" style="background-color: lightyellow; padding: 0px 100px 5px 10px;"></div></td>
    </tr>
</table>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDBT6_1gj-zGPI85qsCu5nADAKSYA6eJ40&libraries=places"></script>
    <script src="script.js"></script>
    </form>
    <script>
  // Function to enable the "Request a ride" button
  function enableRequestButton() {
    document.getElementById("requestRideButton").removeAttribute("disabled");
  }

  // Attach the enableRequestButton function to the "Validate" button click event
  document.querySelector(".yesbut").addEventListener("click", enableRequestButton);
</script>
    <script>
        initMap();
    </script>
    <script src="js/bootstrap.js"></script>
</body>