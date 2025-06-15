<?php
require_once 'ReceiptGenerator.php';

class CreditCardReceipt extends ReceiptGenerator {
    protected function generatePaymentInfo() {
        return "Payment Information:\n" .
               "Payment Method: Credit Card\n" .
               "Amount: $" . number_format($this->amount, 2) . "\n" .
               "Card Number: **** **** **** " . substr($this->paymentMethod->getCardNumber(), -4) . "\n" .
               "Card Holder: " . $this->paymentMethod->getHolderName() . "\n" .
               "Transaction Status: Completed\n\n";
    }

    protected function generateFooter() {
        return "==========================================\n" .
               "Thank you for your donation!\n" .
               "This receipt serves as proof of your donation.\n" .
               "Please keep this receipt for your records.\n" .
               "==========================================\n";
    }
} 