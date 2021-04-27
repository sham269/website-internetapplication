<?php 

define('DB_SERVER','190052148.cs2410-web01pvm.aston.ac.uk');
define('DB_USERNAME','u-190052148');
define('DB_PASSWORD','rQ9huWIEFA73jH5');
define('DB_NAME','u_190052148_db');

function getConn(){
    return new PDO("mysql:host=" .DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
}

//connect to database
try{
    $pdo = getConn();

    //set PDO exception
    $pdo-> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
        die("ERROR: could not connect. ". $e->getMessage());
}   
?>