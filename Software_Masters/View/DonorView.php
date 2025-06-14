<?php
require_once '../Model/Donor.php';
require_once '../Controller/DonorController.php';


?>
<?php

class ProfileD{
    public function viewDonor($objDonor){
        echo $objDonor->fname;
        echo $objDonor->lname;
    }
}

?>
