<?php

class PayPal implements PaymentStrategy {
    private $email;
    private $password;
    private $amount;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function setAmount(float $amount) {
        $this->amount = $amount;
    }

    public function Pay(float $amount) {
        // Validate PayPal credentials
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid PayPal email address");
        }
        if (strlen($this->password) < 8) {
            throw new Exception("Invalid PayPal password");
        }

        // Simulate payment processing
        return "Payment of $" . number_format($amount, 2) . " processed successfully using PayPal account: " . $this->email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getAmount() {
        return $this->amount;
    }
}

?>