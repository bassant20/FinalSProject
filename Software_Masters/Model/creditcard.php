<?php

class CreditCard implements PaymentStrategy {
    private $cardNo;
    private $holderName;
    private $cvv;
    private DateTime $expiryDate;

    public function __construct($cardNo, $holderName, $cvv, $expiryDate) {
        $this->cardNo = $cardNo;
        $this->holderName = $holderName;
        $this->cvv = $cvv;
        $this->expiryDate = $expiryDate;
    }

    public function Pay(float $amount) {
        // Validate card details
        if (strlen($this->cardNo) != 16) {
            throw new Exception("Invalid card number");
        }
        if (strlen($this->cvv) < 3 || strlen($this->cvv) > 4) {
            throw new Exception("Invalid CVV");
        }
        if ($this->expiryDate < new DateTime()) {
            throw new Exception("Card has expired");
        }

        // Simulate payment processing
        $maskedCard = substr($this->cardNo, 0, 4) . '********' . substr($this->cardNo, -4);
        return "Payment of $" . number_format($amount, 2) . " processed successfully using Credit Card ending in " . substr($this->cardNo, -4);
    }

    public function getCardNumber() {
        return $this->cardNo;
    }

    public function getHolderName() {
        return $this->holderName;
    }

    public function getCVV() {
        return $this->cvv;
    }

    public function getExpiryDate() {
        return $this->expiryDate;
    }
}

?>