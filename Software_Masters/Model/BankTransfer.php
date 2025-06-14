<?php

class BankTransfer implements PaymentStrategy {
    private $accountNumber;
    private $bankName;
    private $accountHolderName;

    public function __construct($accountNumber, $bankName, $accountHolderName) {
        $this->accountNumber = $accountNumber;
        $this->bankName = $bankName;
        $this->accountHolderName = $accountHolderName;
    }

    public function Pay(float $amount) {
        // Validate bank account details
        if (strlen($this->accountNumber) < 8) {
            throw new Exception("Invalid account number");
        }
        if (empty($this->bankName)) {
            throw new Exception("Bank name is required");
        }
        if (empty($this->accountHolderName)) {
            throw new Exception("Account holder name is required");
        }

        // Simulate payment processing
        $maskedAccount = substr($this->accountNumber, 0, 4) . '********' . substr($this->accountNumber, -4);
        return "Payment of $" . number_format($amount, 2) . " processed successfully via Bank Transfer from " . 
               $this->bankName . " account ending in " . substr($this->accountNumber, -4);
    }
}
?> 