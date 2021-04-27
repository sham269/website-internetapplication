<?php
require_once "include/config.php";
//STARTS SESSIOON
session_start();
//IF USER IS NOT LOGGED IN
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//FOCUSES ON ANIMAL ID
if (isset($_POST['aID'])) {
    $aid = $_POST['aID'];
    //DECLARES VARIABLE ON SESSION
    $Adoptedby = $_SESSION["Username"];

    $sql = "SELECT * FROM animals WHERE id = $aid";
        if($stmt = getConn()->prepare($sql)){
            if($stmt->execute()){
                $row = $stmt->fetch();

                $insert = "INSERT INTO adoptions (Adopted_by, AnimalID, Adoption_status) VALUES ('".$Adoptedby."', '".$aid."','PENDING')";
                
                try{   
                $pdo->exec($insert);
                    echo "Record Added";
                    } catch(PDOException $ex){
                        echo 'Error';
                    }
                exit;
            }
            unset($stmt);
        }
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
    <?php include("include/navbar.html");?>

    <div class="container rounded mt-5 p-3" style="background-color: #e3f2fd;">
        <div class="row justify-content-center ">
    <?php    
        $sql = "SELECT * FROM animals";
        if($stmt = getConn()->prepare($sql)){
            if($stmt->execute()){
                    while($row = $stmt->fetch()){
                        echo '
                        <div class="col pb-3 aboutcard" style="min-width: 250px; max-width: 285px;">
                        <div class="card">
                        <img class="card-img-top" src="'.$row["img"].'" alt="Card image cap">
                        <div class="card-body text-center">
                          <h1 class="card-title">'.$row["Name"].'</h1>
                          <h5 class="card-title">'.$row["Type"].'</h5>
                          <p class="card-text text-left">'.$row["About"].'</p>
                          <a animalID="'.$row["id"].'" data-toggle="modal" data-target="#adoptModal" class="btn btn-primary animalAdoptBtn w-100">Adopt</a>
                        </div>
                      </div>
                      </div>';
                    }
            }
            unset($stmt);
        }
    unset($pdo);
    ?>
        </div>
    </div>
    
        <!-- Modal -->
<div class="modal fade" id="adoptModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Adoption</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        Would you like to adopt this Animal?
        By adopting this animal, you agree with the ToS.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success confirmAdoption">Confirm</button>
      </div>
    </div>
  </div>
</div>
    <!--AJAX SCRIPT FOR ADOPTION BUTTON-->
    <script>
        var aID;
        $('.animalAdoptBtn').on('click', function() {
            aID = $(this).attr('animalID');
        })
        $('.confirmAdoption').on('click', function() {
            if (aID == "") {
                console.log('ERROR')
            } else {
                $.ajax({
                    type: "POST",
                    url: "adoption.php",
                    data: {
                        aID: aID
                    },
                    success: function(result) {
                        if (result=="Error") {
                            alert('This animal has already been adopted.')
                        } else{
                            $('#adoptModal').modal('hide');
                            alert('Success');
                        }
                        console.log(result);
                    },
                    error: function(result) {
                        console.log("Error: " + result.errorMessage)
                    }
                });
            }
        })
    </script>
</body>

</html>
