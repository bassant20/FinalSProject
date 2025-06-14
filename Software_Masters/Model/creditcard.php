<?php

class creditcard implements PaymentStrategy{
    private $cardNo;
    private $holderName;
    private $cvv;
    private DateTime $expiryDate;
    public function __construct($cardNo,$holderName,$cvv,$expiryDate){
        $this->cardNo=$cardNo;
        $this->holderName=$holderName;
        $this->cvv=$cvv;
        $this->expiryDate=$expiryDate;
        
    }
    public function Pay(float $amount){
        
    }
}

?>