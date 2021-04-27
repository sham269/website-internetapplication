<?php
require_once "include/config.php";
//STARTS SESSION
session_start();
//IF USER IS NOT LOGGED IN
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//PHP CODE FOCUSING ON ANIMAL ID
if (isset($_POST['aID'])) {
    //animal ID IS LINKED
    $aid = $_POST['aID'];
    //SETS THE USERNAME
    $Adoptedby = $_SESSION["Username"];
    //ALERTS USER
    echo('<script>alert('.$aid.')</script>');
    //SELECTS DATA FROM THE ADOPTIONS BASE
    $sql = "SELECT * FROM adoptions WHERE id_no = $aid";
    //PREPARES STATEMENT AND EXECUTES
        if($stmt = getConn()->prepare($sql)){
            if($stmt->execute()){
                //FETCHES THE DATA
                $row = $stmt->fetch();
                //DELETES THE DATA ACCORDING TO ID

                $insert = "DELETE FROM adoptions WHERE id_no = $aid";
                //TRY STATEMENT
                try{   
                $pdo->exec($insert);
                //IF RECORD HAS BEEN DELETED
                    echo "Record Deleted";
                    //IF THERE IS AN ISSUE
                    } catch(PDOException $ex){
                        echo 'Error';
                    }
                exit;
            }
            //UNSETS CONNECTION
            unset($stmt);
        }
        //ENDS CONNECTION
    unset($pdo);
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
    <!-- INCLUDES NAVBAR FILE-->
    <?php include("include/navbar.html");?>   

    <!--MODAL CARD-->
    <div id='my-modal' class='modal' tabindex='-1' role='dialog'>
       <div class='modal-dialog' role='document'>
           <div class='modal-content'>
               <div class='modal-header'>
                   <h5 class='modal-title'>Cancel Adoption</h5>
                   <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                       <span aria-hidden='true'>&times;</span>
                   </button>
               </div>
               <!--MODAL BOX-->
               <div class='modal-body'>
                   <p>Are you sure you would like to cancel this adoption?</p>
               </div>
               <div class='modal-footer'>
                   <button type='button' class='btn btn-danger cancelAdoption'>Confirm</button>
                   <button type='button' class='btn btn-success' data-dismiss='modal'>Close</button>
               </div>
           </div>
       </div>
    </div>
    <!-- MODAL FOR ACCEPTING ADOPTION-->
    <div id='my-modal2' class='modal' tabindex='-1' role='dialog'>
       <div class='modal-dialog' role='document'>
           <div class='modal-content'>
               <div class='modal-header'>
                   <h5 class='modal-title'>Accept Adoption</h5>
                   <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                       <span aria-hidden='true'>&times;</span>
                   </button>
               </div>
               <div class='modal-body'>
                   <p>Are you sure you want to Accept this</p>
               </div>
               <div class='modal-footer'>
                   <!--CONFIRM AND CLOSE BUTTONS FOR MODAL BOXES-->
                   <button type='button' class='btn btn-danger cancelAdoption'>Confirm</button>
                   <button type='button' class='btn btn-success' data-dismiss='modal'>Close</button>
               </div>
           </div>
       </div>
    </div>

    


    <div class="container rounded mt-5 p-3" style="background-color: #e3f2fd;">
        <div class="row justify-content-center ">
    <?php    
    //DECLARES USERNAME AS SESSION
    $Adoptedby = $_SESSION["Username"];
    //CALLS ALL RECORDS THAT IS EQUAL TO THE ADOPTED BY
        $sql = "SELECT * FROM adoptions WHERE Adopted_by = '$Adoptedby'";
        //FETCHES THE CONNECTION
        if($stmt = getConn()->prepare($sql)){
            //EXECUTES THE STATEMENT
            if($stmt->execute()){
                    while($row = $stmt->fetch()){

                        $sql1 = "SELECT * FROM animals WHERE id = ".$row['AnimalID'];
                        if($stmt1 = getConn()->prepare($sql1)){
                            if($stmt1->execute()){
                                $animal = $stmt1->fetch();

                                echo '
                        <div class="col pb-3 aboutcard" style="min-width: 250px; max-width: 285px;">
                        <div class="card">
                        <img class="card-img-top" src="'.$animal["img"].'" alt="Card image cap">
                        <div class="card-body text-center">
                          <h1 class="card-title">'.$animal["Name"].'</h1>
                          <h5 class="card-title">'.$animal["Type"].'</h5>
                          <p class="card-text">'.$row["Adoption_status"].'</p>
                         
                          <a animalID="'.$row["id_no"].'" data-toggle="modal" data-target="#my-modal" class="btn btn-danger cancelAdoptBtn w-100">Cancel Adoption</a>
                          
                        </div>
                      </div>
                      </div>';
                

                            }
                            //UNSETS STMT1
                            unset($stmt1);
                        }
                    }
            }
            //UNSETS STMT
            unset($stmt);
        }
        //UNSETS CONNECTION
    unset($pdo);
    ?>
        </div>
    </div>

    <!--AJAX CODE FOR MODAL BUTTONS-->
    <script>
        //ANIMAL ID VARIABLE
        var aID;
        $('.cancelAdoptBtn').on('click', function() {
            //SETS ATTRIBUTE
            aID = $(this).attr('animalID');
        })
        //CANCELS ADOPTION
        $('.cancelAdoption').on('click', function() {
            if (aID == "") {
                console.log('ERROR')
            } else {
                //ALLOWS TO CANCEL ADOPTION
                $.ajax({
                    type: "POST",
                    url: "manageadoptions.php",
                    data: {
                        aID: aID
                    },
                    //IF THERE IS A SUCESSFUL ATTEMPT
                    success: function(result) {
                        //1 ADOPTION 
                        if (result=="Error") {
                            alert('Only One Adoption per person')
                        } else{
                            $('#my-modal').modal('hide');
                            alert('Adoption Removed');
                            location.reload()
                        }
                        console.log(result);
                    },
                    //ANY OTHER ERROR
                    error: function(result) {
                        console.log("Error: " + result.errorMessage)
                    }
                });
            }
        })
    </script>
    
    
</body>

</html>
