<?php
session_start();
require_once '../../Model/Donation.php';
require_once '../../Model/Donor.php';
require_once '../../Model/Event.php';
require_once '../../Model/CreditCard.php';
require_once '../../Model/PayPal.php';
require_once '../../Model/BankTransfer.php';
require_once '../../Model/Database.php';

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

// Get event details from the database
$conn = Database::getInstance()->getConnection();
$eventSql = "SELECT * FROM event WHERE id = ?";
$stmt = $conn->prepare($eventSql);
$stmt->bind_param("i", $donationDetails['event_id']);
$stmt->execute();
$eventResult = $stmt->get_result();
$eventData = $eventResult->fetch_assoc();

if (!$eventData) {
    http_response_code(404);
    echo "Event not found";
    exit;
}

// Get donor details
$donor = new Donor();
$donor->getUser($donationDetails['donor_id']);

// Create event object with the correct data
$event = new Event($eventData['name'], $eventData['date'], $eventData['location']);

// Set donation details
$donation->setDonor($donor);
$donation->setEvent($event);
// Use the amount from session if available, otherwise use the amount from database
$amount = isset($_SESSION['donation_amount']) ? floatval($_SESSION['donation_amount']) : floatval($donationDetails['amount']);
$donation->setAmount($amount);

// Create appropriate payment strategy based on payment method
switch ($donationDetails['payment_method']) {
    case 'creditCard':
        $paymentStrategy = new CreditCard(
            $donationDetails['card_number'] ?? '****',
            $donationDetails['card_holder'] ?? 'N/A',
            $donationDetails['cvv'] ?? '***',
            new DateTime($donationDetails['expiry_date'] ?? 'now')
        );
        break;
    case 'paypal':
        $paymentStrategy = new PayPal(
            $_SESSION['paypal_email'] ?? 'N/A',
            $donationDetails['paypal_password'] ?? 'N/A'
        );
        // Set the amount in the payment strategy
        $paymentStrategy->setAmount($amount);
        break;
    case 'bankTransfer':
        $paymentStrategy = new BankTransfer(
            $donationDetails['account_number'] ?? 'N/A',
            $donationDetails['bank_name'] ?? 'N/A',
            $donationDetails['account_holder_name'] ?? 'N/A'
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