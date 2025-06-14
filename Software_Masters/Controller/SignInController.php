<?php
// Start the session

require_once '../Model/Admin.php';
// require_once '../View/DonorView.php';

?>
<?php

if(isset($_POST["submit"])){
    $obj1=new Admin();
    $obj1->signIn($_POST["email"], $_POST["psw"]);
    
}

?>
