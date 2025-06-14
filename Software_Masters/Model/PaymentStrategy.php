<?php 

interface PaymentStrategy{

    public function Pay(float $amount);

}

?>