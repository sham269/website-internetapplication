<?php
// Initialize the session
session_start();
require_once "include/config.php";
// Check if the user is logged in, if not then redirect him to login page
$admin==1;
if(!isset($_SESSION["loggedin"]) || $_SESSION["Username"] !==$admin){
    header("location: login.php");
    exit;
}
// if(isset($_FILES['image'])){
//     $errors= "";
//     $file_name = $_FILES['image']['name'];
//     $file_size =$_FILES['image']['size'];
//     $file_tmp =$_FILES['image']['tmp_name'];
//     $file_type=$_FILES['image']['type'];
//     $file_dir = "IMG/animals/";
//     $tmp = explode('.', $file_name);
//     $file_ext = end($tmp);
//     $file_ext= strtolower($file_ext);
    
//     $extensions= array("jpeg","jpg","png");
    
//     if(in_array($file_ext,$extensions)=== false){
//        $errors="extension not allowed, please choose a JPEG or PNG file.";
//     }
    
//     if($file_size > 10485760){
//        $errors='File size must be excately 10 MB';
//     }
    
//     if(empty($errors)==true){
//        move_uploaded_file($file_tmp,$file_dir.$file_name);
//        echo '
//        <div class="alert alert-success remove_alert">
       
//        <strong>Success!</strong> 
//      </div>
//      <script>setTimeout(function(){$(".remove_alert").remove()}, 3000);</script>'
//      ;
        
     

      
//         }
  
//         else{
//        echo '
//        <div class="alert alert-danger">
//        <strong>Error:</strong> '.$errors.'
//       </div>';
//     }
//  }
//REJECT AND ACCEPT ADOPTIONS
if(isset($_POST['Adoption'])){
    $aID = $_POST['AID'];
    $act =  $_POST['ACTION'];
    $sql = "UPDATE adoptions SET Adoption_status='".$act."' WHERE AnimalID='".$aID."';";

    $stmt = getConn()->prepare($sql);

    
    $stmt->execute();
  

    echo $stmt->rowCount() . " records UPDATED successfully";
 
}
//PHP CODE TO INSERT ANIMAL DATA
if(isset($_POST['submit']))
 {    
    
    
    $image = $_FILES['image']['name'];
     $name = $_POST['namepet'];
     $pettype = $_POST['pettype'];
      $about= $_POST['About'];
      $sql = "SELECT * FROM animals";
      $target = "IMG/animals/".$image;
    
     
       if($stmt = getConn()->prepare($sql)){
           if($stmt->execute()){
               $row = $stmt->fetch();

              $insert = "INSERT INTO animals (Type, Name,About,img) VALUES ('".$pettype."', '".$name."','".$about."','".$target."')";
            
              if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                echo "Image uploaded successfully";
            }else{
                echo "Failed to upload image";
            }
              try{   
               $pdo->exec($insert);
                    echo'
                    <script>
                    alert("Added");
                    </script>
                    
                    ';
                    header('Location:admin.php');
                    
                    } catch(PDOException $ex){
                        echo '
                        <div class="alert alert-danger">
                        <strong>Error:</strong> '.$ex.'
                       </div>';  
                   }
               exit;
            }
            unset($stmt);
      }
   
    
     
 }
?>
<!--START OF HTML BODY-->
<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='CSS/style.css'>

</head>

<body>
    <!--INCLUDES NAVBAR-->
    <?php include("include/navbar.html");?>

    <div class="container rounded col-md-10 mt-5 p-3 text-center" style="background-color: #e3f2fd;">
   
        <h1 >
            Add Animal

        </h1>
        <!-- ADD ANIMAL FORM -->
        <form name= "AddAnimal" action="admin.php" method="POST" enctype="multipart/form-data" onsubmit="return Validation()">
         <input type="file" name="image" />
     
        
         <div class="form-group">
                <label>Pet Type</label>
                <input type="text" name="pettype" class="form-control">
                </div>
        <div class="form-group">
        <label>Pet Name</label>
            <input type="text" name="namepet" class="form-control">
                
        </div>      
        <div class="form-group">
                <label>About</label>
                <input type="text" name="About" class="form-control">
                
        </div>
        <input type="submit" name="submit"/>
      </form>
      <!-- VALIDATION SCRIPT -->
      <script>
            function Validation(){
                var petType = document.forms["AddAnimal"]["pettype"];
                var petName = document.forms["AddAnimal"]["namepet"];
                var petAbout = document.forms["AddAnimal"]["About"];

                if(petType.value==""){
                    window.alert("Enter Pet Type");
                    petType.focus();
                    return false;
                }
                if(petName.value==""){
                    window.alert("Enter Pet Name");
                    petName.focus();
                    return false;
                }
                if(petAbout.value==""){
                    window.alert("Enter About");
                    petAbout.focus();
                    return false;
                }
                return true;
            }

    </script>
    
    </div>
    <!-- Adoptions-->
    <div class="container rounded col-md-10 mt-5 p-3 text-center" style="background-color: #e3f2fd;">
    <h1>
        Adoptions
    </h1>
    <table class="table">
    <thead class= "thead-dark">
        <tr>
        <th scope="col"> Id</th>
        <th scope="col"> Adopted By </th>
        <th scope="col"> Adoption Status </th>
        <th scope="col"> Animal ID </th>
        </tr>
    </thead>
    <?php 
    //Selects the Data and calls database connection
        require_once "include/config.php";
        $sql = "SELECT id_no, Adopted_by, Adoption_status, AnimalID from adoptions";

        $result = getConn()-> query($sql);
        if($result -> rowCount() >0){
            while($row = $result -> fetch()){
                echo "<th scope='row'><tr><td>". $row["id_no"] . "</td><td></th>". $row["Adopted_by"]. "</td><td>".$row["Adoption_status"] . "</td><td>" .$row["AnimalID"] . "</td></tr>";

            }
            echo "</table>";
        }
        else{
            echo "0 Results";
        }
       
        
    ?>

    </table>
    </div>
    <div class="container rounded col-md-10 mt-5 p-3 text-center" style="background-color: #e3f2fd;">
    
        <h1>
            Animal Adoptions
        </h1>
        <div class="row justify-content-center ">
    <?php  
    //SELECTS FROM ADOPTION  
        $sql = "SELECT * FROM adoptions";
        //PREPARES CONNECTION
        if($stmt = getConn()->prepare($sql)){
            if($stmt->execute()){
                    while($row = $stmt->fetch()){
                        //SELECTS ANIMALS FROM THE DATABASE BASED ON ID
                        $sql1 = "SELECT * FROM animals WHERE id = ".$row['AnimalID'];
                        if($stmt1 = getConn()->prepare($sql1)){
                            if($stmt1->execute()){
                                $animal = $stmt1->fetch();
                                //ECHO CONTAINING THE MODAL CARDS AND THE ACCEPT AND REJECT BUTTON
                                echo '
                        <div class="col pb-3 aboutcard" style="min-width: 250px; max-width: 285px;">
                        <div class="card">
                        <img class="card-img-top" src="'.$animal["img"].'" alt="Card image cap">
                        <div class="card-body text-center">
                          <h1 class="card-title">'.$animal["Name"].'</h1>
                          <h5 class="card-title">'.$animal["Type"].'</h5>
                          <p class="card-text">'.$row["Adoption_status"].'</p>
                        
                          <a AID ="'.$row["AnimalID"].'" act = "ACCEPTED" Adoption_status="'.$row["Adoption_status"].'" data-toggle="modal" data-target="#my-modal" class="btn btn-success AcceptAdoptionbtn w-100" style="margin-bottom:5px">Accept Adoption</a>
                          <a AID ="'.$row["AnimalID"].'" act = "REJECTED" Adoption_status="'.$row["Adoption_status"].'" data-toggle="modal" data-target="#my-modal" class="btn btn-danger Rejectadoptionbtn w-100">Reject Adoption</a>
                        </div>
                      </div>
                      </div>';
                                

                            }
                            unset($stmt1);
                        }
                    }
            }
            unset($stmt);
        }
    unset($pdo);
    ?>
   
    
    <script>
        //ADOPTION AJAX SCRIPT 
    var Adoption;
         //ACCEPT ADOPTION AND REJECT ADOPTION BUTTON
        $('.AcceptAdoptionbtn, .Rejectadoptionbtn').on('click', function() {
            //sets Attributes
            action = $(this).attr('act');
            Adoption = $(this).attr('Adoption_status');
            AnimalID =  $(this).attr('AID');
            //if Adoption variable is null ERROR IS PRINTED
            if (Adoption == "") {

                console.log('ERROR')
            }//AJAX CODE TO UPDATE DATA
            else {
                $.ajax({
                    type: "POST",
                    url: "admin.php",
                    data: {
                        Adoption: "1",
                        AID: AnimalID,
                        ACTION:action
                        
                    },
                    //IF IT IS SUCESSFULL
                    success: function(result) {
                        alert("Sucessful");
                        location.reload();
                        
                    },
                    //IF THERE IS AN ISSUE
                    error: function(result) {
                        console.log("Error: " + result.errorMessage)
                    }
                });
            }
        })
        
    </script>
    
  
        </div>
    </div>

</body>

</html>