<?php

class creditcard implements PaymentStrategy{
    private $BankAccountNum;
    public function __construct($BankAccountNum){
        $this->BankAccountNum=$BankAccountNum;
        
    }
    public function Pay(float $amount){
        
    }
}

?>