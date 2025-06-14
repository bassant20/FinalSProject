<?php
require_once 'ReceiptGenerator.php';

class BankTransferReceipt extends ReceiptGenerator {
    protected function generatePaymentInfo() {
        return "Payment Information:\n" .
               "Payment Method: Bank Transfer\n" .
               "Amount: $" . number_format($this->amount, 2) . "\n" .
               "Bank Name: " . $this->paymentMethod->bankName . "\n" .
               "Account Number: ****" . substr($this->paymentMethod->accountNumber, -4) . "\n" .
               "Account Holder: " . $this->paymentMethod->accountHolderName . "\n" .
               "Transaction Status: Completed\n\n";
    }

    protected function generateFooter() {
        return "==========================================\n" .
               "Thank you for your donation!\n" .
               "This receipt serves as proof of your donation.\n" .
               "Please keep this receipt for your records.\n" .
               "The transfer will be reflected in your bank statement.\n" .
               "==========================================\n";
    }
} 