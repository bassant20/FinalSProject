<?php
require_once 'PaymentStrategy.php';
require_once 'Database.php';

class Donation {
    private $donationID;
    private Donor $donor;
    private Event $event;
    private $amount;
    private $description;
    public PaymentStrategy $Payment;
    private $payS;
    
    // public function __construct(int $donationID, Donor $donor, Event $event, float $amount, DateTime $date) {
    //     $this->donationID = $donationID;
    //     $this->donor = $donor;
    //     $this->event = $event;
    //     $this->amount = $amount;
    //     $this->date = $date;
    // }
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllDonations() {
         $sql = "SELECT * FROM donoation";
          return $this->conn->query($sql); // should return mysqli_result
    }

    public function getDonationById($id) {
        $sql = "SELECT * FROM donoation WHERE id = $id";
        return $this->conn->query($sql)->fetch_assoc();
    }

    public function addToDonation($id, $amount) {
        $sql = "UPDATE donoation SET current_amount = current_amount + $amount WHERE id = $id";
        return $this->conn->query($sql);
    }
    public function Donation() {
        $this->Payment->Pay($this->amount);
    }
    public function generateReceipt(): void {}
}


?>