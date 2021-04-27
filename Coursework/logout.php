<?php
//session set up
session_start();

//unsets variables
$_SESSION = array();


//session destroyed
session_destroy();

//user redirected back to login page
header("location:login.php");
exit;

?>