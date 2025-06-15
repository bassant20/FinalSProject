<?php
require_once 'PaymentStrategy.php';
require_once 'Database.php';
require_once 'CreditCardReceipt.php';
require_once 'PayPalReceipt.php';
require_once 'BankTransferReceipt.php';

class Donation {
    private $donationID;
    private Donor $donor;
    private Event $event;
    private $amount;
    private $description;
    private PaymentStrategy $paymentStrategy;
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function setAmount(float $amount) {
        $this->amount = $amount;
    }

    public function setPaymentStrategy(PaymentStrategy $paymentStrategy) {
        $this->paymentStrategy = $paymentStrategy;
    }

    public function setDonor(Donor $donor) {
        $this->donor = $donor;
    }

    public function setEvent(Event $event) {
        $this->event = $event;
    }

    public function processPayment() {
        if (!isset($this->paymentStrategy)) {
            throw new Exception("Payment strategy not set");
        }
        if (!isset($this->amount)) {
            throw new Exception("Amount not set");
        }
        return $this->paymentStrategy->Pay($this->amount);
    }

    public function generateReceipt() {
        if (!isset($this->donor) || !isset($this->event) || !isset($this->amount) || !isset($this->paymentStrategy)) {
            throw new Exception("Missing required information for receipt generation");
        }

        // Create appropriate receipt generator based on payment method
        if ($this->paymentStrategy instanceof CreditCard) {
            $receiptGenerator = new CreditCardReceipt($this, $this->donor, $this->event, $this->amount, $this->paymentStrategy);
        } elseif ($this->paymentStrategy instanceof PayPal) {
            $receiptGenerator = new PayPalReceipt($this, $this->donor, $this->event, $this->amount, $this->paymentStrategy);
        } elseif ($this->paymentStrategy instanceof BankTransfer) {
            $receiptGenerator = new BankTransferReceipt($this, $this->donor, $this->event, $this->amount, $this->paymentStrategy);
        } else {
            throw new Exception("Unsupported payment method for receipt generation");
        }

        return $receiptGenerator->generateReceipt();
    }

    public function getAllEvents() {
        $sql = "SELECT * FROM event";
        return $this->conn->query($sql);
    }

    public function getAllDonations() {
        $sql = "SELECT d.*, e.name as event_name, CONCAT(dr.firstname, ' ', dr.lastname) as donor_name 
                FROM donoation d 
                LEFT JOIN event e ON d.event_id = e.id 
                LEFT JOIN donors dr ON d.donor_id = dr.id";
        return $this->conn->query($sql);
    }

    public function getDonationById($id) {
        $sql = "SELECT d.*, e.name as event_name, e.date, e.location, CONCAT(dr.firstname, ' ', dr.lastname) as donor_name 
                FROM donoation d 
                LEFT JOIN event e ON d.event_id = e.id 
                LEFT JOIN donors dr ON d.donor_id = dr.id 
                WHERE d.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getDonationId() {
        return $this->donationID;
    }

    public function setDonationId($id) {
        $this->donationID = $id;
    }

    public function addToDonation($donor_id, $event_id, $amount, $payment_method) {
        // Start transaction
        $this->conn->begin_transaction();

        try {
            // Insert the new donation
            $sql = "INSERT INTO donoation (donor_id, event_id, amount, payment_method, status, created_at) 
                    VALUES (?, ?, ?, ?, 'completed', NOW())";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("iids", $donor_id, $event_id, $amount, $payment_method);
            $stmt->execute();

            // Get the inserted donation ID
            $this->donationID = $this->conn->insert_id;

            // Update the current_amount
            $sql = "UPDATE donoation 
                    SET current_amount = current_amount + ? 
                    WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("di", $amount, $this->donationID);
            $stmt->execute();

            // Commit transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollback();
            throw $e;
        }
    }

    public function getEventDonations($event_id) {
        $sql = "SELECT d.*, CONCAT(dr.firstname, ' ', dr.lastname) as donor_name 
                FROM donoation d 
                LEFT JOIN donors dr ON d.donor_id = dr.id 
                WHERE d.event_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDonorDonations($donor_id) {
        $sql = "SELECT d.*, e.name as event_name 
                FROM donoation d 
                LEFT JOIN event e ON d.event_id = e.id 
                WHERE d.donor_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $donor_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }
}
?>