<?php

namespace App\Src;

use App\Models\Client;
use App\Models\Session;
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

        $_result                = false;
        $_token_session         = '######';
        $_encrypt_transaction   = '######';

        //if exists customer and seller
        if($_exist_CLIENT && $_exist_SELLER)
        {
            //if the customer's balance is sufficient for the transaction
            if($_exist_CLIENT[0]->amount >= $values->amount)
            {
                $_token_session     = $this->makeTokenPayment();
                $_encrypt_transaction   = Crypt::encrypt($values->toArray());

                $_result = $this->tokenSessionPayment($_token_session, $_encrypt_transaction); 
            }
        }

        return $this->response_payment($_result);
    }

    //function return the response after payment created
    private function response_payment($value)
    {
        $_aux = [];

        if($value)
        {
            $_aux=
            [ 
				'ok'=> true, 
				'status' => 201, 
				'message' => 'Transaccion en espera; codigo: '.$value['session_id'].', por favor confirmar',
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
				'message' => 'Ha ocurrido un error en la transaccion, por favor verifica tus datos',
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
        Session::Create(['id'=>$token_session, 'token'=>$encrypt_payment]);

        return ['session_id'=> $token_session, 'transaction'=> $encrypt_payment];
    }

    //function to make token 6digits
    private function makeTokenPayment($long_string = 6)
    {
        ($long_string < 6)? $long_string = 6: $long_string;
        return bin2hex(openssl_random_pseudo_bytes(($long_string - ($long_string % 2)) / 2));
    }

    //function to confirm payment
    public function confirmPayment($_id)
    {
        //retrieve and delete de payment session
        $_transaction = Session::find($_id);

        //if exist a transaction...
        if($_transaction)
        {
            $_aux = $_transaction;
            $_transaction->delete();
            $_transaction = $this->changeAccountBalance(decrypt($_aux->token));

        }else
        {
            $_transaction = null;
        }

        return $this->response_confirm($_transaction);
    }

    //function to response to confirm payment
    private function response_confirm($value)
    {
        $_aux = [];

        if($value)
        {
            $_aux=
            [ 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Transaccion confirmada!',
                'data' =>
                [
                    'saldo'  => $value[0]->amount

                ]

            ];
        }
        else
        {
            $_aux=
            [ 
				'ok'=> false, 
				'status' => 401, 
				'message' => 'El token de pago es incorrecto, por favor verifica...',
                'data' => null

            ];
        }

        return $_aux;
    }

    //funcion to complete transaction
    private function changeAccountBalance($_transaction)
    {
        $_customer = $this->findUserbyEmail($_transaction['Id_CLIENT']);
        $_seller   = $this->findUserbyEmail($_transaction['Id_SELLER']);

        //proceed to change balance between both users
        $_customer[0]->amount = $_customer[0]->amount - $_transaction['amount']; 
        $_seller[0]->amount   = $_seller[0]->amount + $_transaction['amount']; 
        
        Client::where('email',$_customer[0]->email)->update(['amount' => $_customer[0]->amount]);
        Client::where('email',$_seller[0]->email)->update(['amount' => $_seller[0]->amount]);

        return $_customer;
    }
}
