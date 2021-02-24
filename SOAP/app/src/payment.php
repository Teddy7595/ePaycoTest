<?php

namespace App\Src;

//Class to treat payments and confirmation
class Payment  
{
    public function __construct($var = null) 
    {
        
    }

    //function to create a session to payment
    public function makePaySession($values)
    {
        return $this->makeTokenPayment();
    }

    //function to make token 6digits
    private function makeTokenPayment($long_string = 6)
    {
        ($long_string < 4)? $long_string = 4: $long_string;
        return bin2hex(openssl_random_pseudo_bytes(($long_string - ($long_string % 2)) / 2));
    }

    //function to send payment notificatiob
    private function sendEmailNotify($value)
    {

    }
}
