<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: home.php");
    exit;
}
 
// Include config file
require_once "include/config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

$admin = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    //if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    //if password and username has no errors
    if(empty($username_err) && empty($password_err)){
        //USERNAME AND PASSWORD FIELDS FETCHED FROM DATABASE
        $sql = "SELECT id, Username, Password, Admin FROM registrations WHERE Username = :Username";
        
        if($stmt = $pdo->prepare($sql)){
            //USERNAME IS BINDED
            $stmt->bindParam(":Username", $param_username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            //STATEMENT EXECUTED
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    //DATA IS FETECHED
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["Username"];
                        $hashed_password = $row["Password"];
                        $admin = $row["Admin"];
                        //IF PASSWORD IS VERFIED
                        if(password_verify($password, $hashed_password)){
                            //SESSION STARTS UP
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["Username"] = $username;   

                            header("location: home.php");
                        }
                        //IF THERE IS AN ERROR WITH PASSWORD
                         else{
                            $login_err = "Incorrect Password";
                        }
                    }
                }
                //IF THERE IS AN ERROR WITH USERNAME 
                else{
                    $login_err = "Incorrect Username";
                }
                //IF THERE IS ANOTHER ERROR
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            //UNSETS CONNECTION
            unset($stmt);
        }
    }
    //UNSERTS PDO
    unset($pdo);
}
//INCLUDES LOGIN FILE
include('include/index.html')

?>