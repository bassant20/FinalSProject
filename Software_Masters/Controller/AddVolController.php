<?php
// Start the session

require_once '../Model/Admin.php';
// require_once '../View/DonorView.php';


if(isset($_POST["submit"])){
    echo $_POST["Fname"];
    $obj1=new Admin();
    $obj1->AddVol($_POST["Fname"],$_POST["Lname"], $_POST["email"],$_POST["password"], $_POST["phone"]);
    
}

?>
