<?php

namespace App\Src;

use App\Models\Client;
use Illuminate\Support\Facades\Crypt;

//Class to treat payments and confirmation
class Payment  
{
    public function __construct($var = null) 
    {
        
    }

    //function to create a session to payment
    public function makePaySession($values)
    {
        $_exist_SELLER = $this->findUserbyEmail($values->Id_SELLER);
        $_exist_CLIENT = $this->findUserbyEmail($values->Id_CLIENT);

        $_result            = false;
        $_token_session     = '######';
        $_encrypt_payment   = '######';

        //if exists customer and seller
        if($_exist_CLIENT && $_exist_SELLER)
        {
            //if the customer's balance is sufficient for the transaction
            if($_exist_CLIENT[0]->amount >= $values->amount)
            {
                $_token_session     = $this->makeTokenPayment();
                $_encrypt_payment   = Crypt::encrypt($values->toArray());

                $_result = $this->tokenSessionPayment($_token_session, $_encrypt_payment); 
            }
        }

        return $this->response($_result);
    }

    //function return the response
    private function response($value)
    {
        $_aux = [];

        if($value)
        {
            $_aux=
            [ 
				'ok'=> true, 
				'status' => 201, 
				'message' => 'Transacción en espera codigo: '.$value['session_id'].', por favor confirma',
                'data' =>
                [
                    'session_id'  => $value['session_id'],
                    'transaction' => $value['transaction']

                ]

            ];
        }
        else
        {
            $_aux=
            [ 
				'ok'=> false, 
				'status' => 401, 
				'message' => 'Ha ocurrido un error en la transacción, por favor verifica tus datos',
                'data' => null

            ];
        }

        return $_aux;
    }

    //function to find a user
    private function findUserbyEmail($email)
    {
        $_result = Client::where('email', $email)->get();

        ($_result->isEmpty())? $_result = null : $_result;

        return $_result;
    }

    //function to make a session
    private function tokenSessionPayment($token_session, $encrypt_payment)
    {
        session([$token_session => $encrypt_payment]);

        return ['session_id'=> $token_session, 'transaction'=> $encrypt_payment];
    }

    //function to make token 6digits
    private function makeTokenPayment($long_string = 6)
    {
        ($long_string < 6)? $long_string = 6: $long_string;
        return bin2hex(openssl_random_pseudo_bytes(($long_string - ($long_string % 2)) / 2));
    }
}
