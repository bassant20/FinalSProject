<?php

class paypal implements PaymentStrategy{
    private $address;
    public function __construct($address){
        $this->address=$address;
    }
    public function Pay(float $amount){
        
    }
}

?>