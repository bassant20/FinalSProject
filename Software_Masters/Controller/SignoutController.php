<?php

require_once '../Model/Admin.php';

if(isset($_GET['action'])){
    if($_GET['action']=="logout"){
        $admin=new Admin();
        $admin->signOut();
        header("Location: ../View/index.php");
    }
}

?>