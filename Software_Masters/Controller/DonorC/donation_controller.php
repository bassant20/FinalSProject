<?php
session_start();
require_once '../../Model/Donation.php';
require_once '../../Model/CreditCard.php';
require_once '../../Model/PayPal.php';
require_once '../../Model/BankTransfer.php';

class DonationController {
    private $donation;

    public function __construct() {
        $this->donation = new Donation();
    }

    public function processDonation() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $amount = floatval($_POST['amount']);
            $paymentMethod = $_POST['paymentMethod'];
            $event_id = $_POST['event_id'];

            // Set the payment strategy based on the selected method
            switch ($paymentMethod) {
                case 'creditCard':
                    $cardNumber = $_POST['cardNumber'];
                    $cardHolder = $_POST['cardHolder'];
                    $cvv = $_POST['cvv'];
                    $expiryDate = new DateTime($_POST['expiryDate']);
                    
                    $paymentStrategy = new CreditCard($cardNumber, $cardHolder, $cvv, $expiryDate);
                    break;

                case 'paypal':
                    $email = $_POST['paypalEmail'];
                    $password = $_POST['paypalPassword'];
                    
                    $paymentStrategy = new PayPal($email, $password);
                    break;

                case 'bankTransfer':
                    $accountNumber = $_POST['accountNumber'];
                    $bankName = $_POST['bankName'];
                    $accountHolderName = $_POST['accountHolderName'];
                    
                    $paymentStrategy = new BankTransfer($accountNumber, $bankName, $accountHolderName);
                    break;

                default:
                    throw new Exception("Invalid payment method");
            }

            try {
                // Set the amount
                $this->donation->setAmount($amount);
                
                // Set the payment strategy
                $this->donation->setPaymentStrategy($paymentStrategy);
                
                // Process the payment
                $result = $this->donation->processPayment();
                
                // Store donation in database
                $this->donation->addToDonation($_SESSION['id'], $event_id, $amount, $paymentMethod);
                
                // Redirect to success page
                header("Location: ../../View/DonorV/donation_success.php");
                exit();
            } catch (Exception $e) {
                // Handle payment error
                header("Location: ../../View/DonorV/donation.php?error=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }
}

// Create controller instance and process the donation
$controller = new DonationController();
$controller->processDonation();
?> 