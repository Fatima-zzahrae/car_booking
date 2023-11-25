<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
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

      .wrapper{
        height: 750px;
        margin-bottom: 50px;
      }
    </style>
</head>
<body>
<?php
    require('db_connect.php');

    if (isset($_POST['submit'])) {
        $name = filter_input(INPUT_POST,'name');
        $surname = filter_input(INPUT_POST,'surname');
        $dob    = filter_input(INPUT_POST,'dob');
        $email = filter_input(INPUT_POST,'email');
        $phone = filter_input(INPUT_POST,'phone');
        $password = filter_input(INPUT_POST,'password');
        $query    = "INSERT into `passengers` (name, surname, dob, email, phone, password)
                     VALUES ( ?, ?, ? , ?, ?, ?)";
         $stmt = mysqli_stmt_init($conn);
         $prepareStmt = mysqli_stmt_prepare($stmt,$query);
         if ($prepareStmt) {
             mysqli_stmt_bind_param($stmt,"ssssss", $name, $surname, $dob, $email, $phone, $password);
             mysqli_stmt_execute($stmt);
             header("Location: passenger_login.php");
             exit; 
         }else{
             die("Click here to <a href='passenger_signup.php'>sign up</a> again.");
         }
    } 
    else {
?>

<div class="homebut">
     <a class="back" href="index.html" style="font-size:13px"><button class="backbut"><i style="font-size:13px" class="fa">&#xf0d9;</i> Homepage</button></a>   
</div>

<div class="wrapper">
    <div class="logo">
        <img src="pics/user.png" alt="user">
    </div>

<div class="registration">
    <form class="p-3 mt-3" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">

    <div class="form-field d-flex align-items-center">
        <input type="text" name="name" placeholder="Name" required="">
    </div>

    <div class="form-field d-flex align-items-center">
        <input type="text" name="surname" placeholder="Surname" required="">
    </div>

    <div class="form-field d-flex align-items-center">
        <input type="date" name="dob" placeholder="Birthdate" required="">
    </div>

    <div class="form-field d-flex align-items-center">
        <input type="email" name="email" placeholder="Email" required="">
    </div>

    <div class="form-field d-flex align-items-center">
        <input type="tel" name="phone" placeholder="Phone Number" required="">
    </div>

    <div class="form-field d-flex align-items-center">
        <input type="password" name="password" placeholder="Password" required="">
    </div>

    <input type="submit" class="btn mt-3" name="submit" value="Sign up">
            <br/><br/><br/>
            <div class="text-center fs-6">
                <p>Already have an account? <a href="passenger_login.php"><u>Log in</u></a></p>
            </div>
    </form>
</div>
<?php
    }
?>
<script src="js/bootstrap.js"></script>
</body>
</html>