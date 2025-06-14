<?php
// Start the session

require_once '../Model/Event.php';
require_once '../Model/Admin.php';
require_once '../Model/Donor.php';
require_once '../Model/Volunteer.php';



if(isset($_POST["submit"])){
    //$obj1=new Event($_POST["name"],$_POST["date"],$_POST["location"]);
   
    // $obj2=new Admin();
    // $obj2->Add($obj1);
    $admin = new Admin();
    $admin->registerObserver(new Donor());
    $admin->registerObserver(new Volunteer());

    $newEvent = new Event($_POST["name"],$_POST["date"],$_POST["location"]);
    $admin->addEvent($newEvent);
}

?>
