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
    <title>Passenger dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.css">
</head>
<body style="background-color: #000">

<div style="text-align: center;">
      <div class="card" style="width: 17rem; display: inline-block; margin: 100px 20px 80px 20px;">
      <img src="pics/pexels-andrea-piacquadio-3760814(2).jpg" class="card-img-top" alt="card1Img" >
  <div class="card-body">
    <a href="ride_dashboard.php" class="btn btn-outline-secondary"><b>Request a ride</b></a>
  </div>
      </div>
    
      <div class="card" style="width: 17rem; display: inline-block; margin: 100px 20px 80px 20px;">
      <img src="pics/pexels-artem-podrez-6823398.jpg" class="card-img-top" alt="card2Img">
  <div class="card-body">
    <a href="ride_history.php" class="btn btn-outline-secondary"><b>Ride history</b></a>
  </div>
      </div>
    </div>
    <center><a href="passenger_logout.php" class="btn btn-outline-light"><b>Logout</b></a><center>
    <script src="js/bootstrap.js"></script>
</body>
</html>

