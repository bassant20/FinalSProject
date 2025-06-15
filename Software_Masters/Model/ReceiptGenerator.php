<?php

abstract class ReceiptGenerator {
    protected $donation;
    protected $donor;
    protected $event;
    protected $amount;
    protected $paymentMethod;
    protected $date;

    public function __construct($donation, $donor, $event, $amount, $paymentMethod) {
        $this->donation = $donation;
        $this->donor = $donor;
        $this->event = $event;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->date = date('Y-m-d H:i:s');
    }

    // Template method
    final public function generateReceipt() {
        $receipt = $this->generateHeader();
        $receipt .= $this->generateDonorInfo();
        $receipt .= $this->generateEventInfo();
        $receipt .= $this->generatePaymentInfo();
        $receipt .= $this->generateFooter();
        return $receipt;
    }

    // Common methods
    protected function generateHeader() {
        return "==========================================\n" .
               "              DONATION RECEIPT            \n" .
               "==========================================\n" .
               "Date: " . $this->date . "\n" .
               "Receipt #: " . $this->donation->getDonationId() . "\n" .
               "==========================================\n\n";
    }

    protected function generateDonorInfo() {
        return "Donor Information:\n" .
               "Name: " . $this->donor->getFirstName() . " " . $this->donor->getLastName() . "\n" .
               "Email: " . $this->donor->getEmail() . "\n" .
               "Phone: " . $this->donor->getPhoneNumber() . "\n\n";
    }

    protected function generateEventInfo() {
        return "Event Information:\n" .
               "Event Name: " . $this->event->eventName . "\n" .
               "Date: " . $this->event->date . "\n" .
               "Location: " . $this->event->location . "\n\n";
    }

    // Abstract methods to be implemented by concrete classes
    abstract protected function generatePaymentInfo();
    abstract protected function generateFooter();
} 