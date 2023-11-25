<?php
    require('db_connect.php');
    $errorMessage = '';
    
    // Function to generate a custom session ID
    function generateCustomSessionId() {
        // Combine a timestamp and randomness
        $unique_data = time() . mt_rand();

        // Hash the combined data to create a secure session ID
        $session_id = md5($unique_data);

        return $session_id;
    }

    if (isset($_POST['submit'])) {
        $drivingLicenseNum = filter_input(INPUT_POST, 'drivingLicenseNum'); 
        $password = filter_input(INPUT_POST, 'password');
        $query = "SELECT * FROM `drivers` WHERE drivingLicenseNum='$drivingLicenseNum' AND password='$password'";
        $result = mysqli_query($conn, $query) or die(mysql_error());
        $rows = mysqli_num_rows($result);
        if ($rows == 1) {
            // Generate a custom session ID
            $session_id = generateCustomSessionId();

            // Set the custom session ID and start the session
            session_id($session_id);
            session_start();

            $_SESSION["auth"] = 1;

            $user_data = mysqli_fetch_assoc($result);
            $user_id = $user_data['driver_id'];
            $_SESSION["user_id"] = $user_id;                   
            
            header("Location: driver_dashboard.php");                      
            exit;  
        } else {
            $errorMessage = "Login failed! Try again.";    
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
      input:-webkit-autofill,
      input:-webkit-autofill:hover, 
      input:-webkit-autofill:focus, 
      input:-webkit-autofill:active{
          -webkit-background-clip: text;
          -webkit-text-fill-color: #000;
          transition: background-color 5000s ease-in-out 0s;
          border-radius: 20px;
      }

    </style>
</head>
<body>
    <div class="homebut">
     <a class="back" href="index.html" style="font-size:13px"><button class="backbut"><i style="font-size:13px" class="fa">&#xf0d9;</i> Homepage</button></a>   
</div>
<div class="wrapper">
    <div class="logo">
        <img src="pics/user.png" alt="user">
    </div>
<div class="loginform">
    <form class="p-3 mt-3" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" name="login">
    <div class="form-field d-flex align-items-center">
        <input type="text" name="drivingLicenseNum" placeholder="Driving License Num" required="">
    </div>
    <div class="form-field d-flex align-items-center">
        <input type="password" name="password" placeholder="Password" required="">
    </div>
    <div class="error-message" style="color: red;"><?php echo $errorMessage; ?></div>
            <input type="submit" class="btn mt-3" name="submit" value="Log in">
            <br/><br/><br/>
            <div class="text-center fs-6">
                <p>Don't have an account? <a href="driver_signup.php"><u>Sign up</u></a></p>
            </div>
  </form>
  </div>
  <script src="js/bootstrap.js"></script>
</body>
</html>


