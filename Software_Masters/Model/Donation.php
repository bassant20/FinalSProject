<?php
require_once 'PaymentStrategy.php';
require_once 'Database.php';

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

    public function processPayment() {
        if (!isset($this->paymentStrategy)) {
            throw new Exception("Payment strategy not set");
        }
        if (!isset($this->amount)) {
            throw new Exception("Amount not set");
        }
        return $this->paymentStrategy->Pay($this->amount);
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
        $sql = "SELECT d.*, e.name as event_name, CONCAT(dr.firstname, ' ', dr.lastname) as donor_name 
                FROM donoation d 
                LEFT JOIN event e ON d.event_id = e.id 
                LEFT JOIN donors dr ON d.donor_id = dr.id 
                WHERE d.id = $id";
        return $this->conn->query($sql)->fetch_assoc();
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

            // Update the current_amount
            $sql = "UPDATE donoation 
                    SET current_amount = current_amount + ? 
                    WHERE id = (SELECT id FROM donoation WHERE event_id = ? ORDER BY id DESC LIMIT 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("di", $amount, $event_id);
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

    public function generateReceipt(): void {
        // Implement receipt generation logic
    }
}
?>