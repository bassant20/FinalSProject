<?php

require_once __DIR__ . '/../Model/Donation.php';


class DonationController {
    public function index() {
        $donation = new Donation();
        $donations = $donation->getAllDonations();
        require __DIR__ . '/../View/DonorV/index.php'; // pass $donations to the view
    }

    public function show($id) {
        $donation = new Donation();
        $donationInfo = $donation->getDonationById($id);
        require_once __DIR__ . '/../View/DonorV/show.php';
    }

    public function donate($id, $amount) {
        $donation = new Donation();
        $donation->addToDonation($id, $amount);
        header("Location: index.php?action=show&id=$id");
    }
}


?>