<?php
$_SESSION=array();
session_destroy();
session_reset();
header('location:passenger_login.php');
?>