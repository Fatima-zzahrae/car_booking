<?php 
    $conn = mysqli_connect("localhost","dbuser","dbpass","car_booking");
    if ($conn -> connect_errno) {
        exit("Connection error: " . $conn -> connect_errno);   
    }
?>