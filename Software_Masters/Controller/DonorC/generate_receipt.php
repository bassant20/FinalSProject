<?php
session_start();
require_once '../../Model/Donation.php';
require_once '../../Model/Donor.php';
require_once '../../Model/Event.php';
require_once '../../Model/CreditCard.php';
require_once '../../Model/PayPal.php';
require_once '../../Model/BankTransfer.php';

// Get the request body
$data = json_decode(file_get_contents('php://input'), true);
$donation_id = $data['donation_id'];

// Get donation details
$donation = new Donation();
$donationDetails = $donation->getDonationById($donation_id);

if (!$donationDetails) {
    http_response_code(404);
    echo "Donation not found";
    exit;
}

// Get donor details
$donor = new Donor();
$donor->getUser($donationDetails['donor_id']);

// Create event object
$event = new Event($donationDetails['event_name'], $donationDetails['date'], $donationDetails['location']);

// Set donation details
$donation->setDonor($donor);
$donation->setEvent($event);
$donation->setAmount($donationDetails['amount']);

// Create appropriate payment strategy based on payment method
switch ($donationDetails['payment_method']) {
    case 'creditCard':
        $paymentStrategy = new CreditCard(
            $donationDetails['card_number'],
            $donationDetails['card_holder'],
            $donationDetails['cvv'],
            new DateTime($donationDetails['expiry_date'])
        );
        break;
    case 'paypal':
        $paymentStrategy = new PayPal(
            $donationDetails['paypal_email'],
            $donationDetails['paypal_password']
        );
        break;
    case 'bankTransfer':
        $paymentStrategy = new BankTransfer(
            $donationDetails['account_number'],
            $donationDetails['bank_name'],
            $donationDetails['account_holder_name']
        );
        break;
    default:
        http_response_code(400);
        echo "Invalid payment method";
        exit;
}

$donation->setPaymentStrategy($paymentStrategy);

try {
    // Generate receipt
    $receipt = $donation->generateReceipt();
    echo $receipt;
} catch (Exception $e) {
    http_response_code(500);
    echo "Error generating receipt: " . $e->getMessage();
} 