<?php
$_SESSION=array();
session_destroy();
session_reset();
header('location:driver_login.php');
?>