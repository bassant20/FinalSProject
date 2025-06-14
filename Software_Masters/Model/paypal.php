<?php

class PayPal implements PaymentStrategy {
    private $email;
    private $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
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
}

?>