<?php
require_once 'ReceiptGenerator.php';

class PayPalReceipt extends ReceiptGenerator {
    protected function generatePaymentInfo() {
        return "Payment Information:\n" .
               "Payment Method: PayPal\n" .
               "Amount: $" . number_format($this->amount, 2) . "\n" .
               "PayPal Email: " . $this->paymentMethod->email . "\n" .
               "Transaction Status: Completed\n\n";
    }

    protected function generateFooter() {
        return "==========================================\n" .
               "Thank you for your donation!\n" .
               "This receipt serves as proof of your donation.\n" .
               "Please keep this receipt for your records.\n" .
               "A copy of this receipt has been sent to your PayPal email.\n" .
               "==========================================\n";
    }
} 