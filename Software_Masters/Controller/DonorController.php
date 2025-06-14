<?php
// Start the session

require_once '../Model/Donor.php';
require_once '../View/DonorView.php';

?>
<?php

if(isset($_POST["submit"])){
    $obj1=new donor();
    $obj1->signIn($_POST["email"], $_POST["psw"]);
    $obj1->getUser($_SESSION["id"]);
    $obj2=new ProfileD();
    $obj2->viewDonor($obj1);
}

?>
