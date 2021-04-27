<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='CSS/style.css'>

</head>

<body>
    <?php include("include/navbar.html");?>

    <div class="container rounded col-md-10 mt-5 p-3 text-center" style="background-color: #e3f2fd;">
    
    <h1>

    Welcome to the Animal Sanctuary
</h1>
<img class="img-responsive" src="IMG/Animal_Background.jpg" />
    </div>

    <div class="container rounded col-md-10 mt-5 p-3" style="background-color: #e3f2fd;">
    <h1  class="text-center">

    About
    </h1>
   
     Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
</p1> 
    </div>
</body>

</html>