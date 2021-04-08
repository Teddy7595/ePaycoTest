<?php

namespace App\Src;

use App\Models\Client;
use App\Models\Session;
use Illuminate\Support\Facades\Crypt;

//Clase para el tratamiento y confirmación de pagos
class Payment  
{
    public function __construct($var = null) 
    {
        
    }

    //funcion que crea una sesion de pago
    public function makePaySession($values)
    {
        $_exist_SELLER = $this->findUserbyEmail($values->Id_SELLER);
        $_exist_CLIENT = $this->findUserbyEmail($values->Id_CLIENT);

        $_result                = false;
        $_token_session         = '######';
        $_encrypt_transaction   = '######';

        //si existe el vendedor y el cliente...
        if($_exist_CLIENT && $_exist_SELLER)
        {
            //si el balance del cliente es suficiente para la operacion...
            if($_exist_CLIENT[0]->amount >= $values->amount)
            {
                $_token_session     = $this->makeTokenPayment();
                $_encrypt_transaction   = Crypt::encrypt($values->toArray());

                $_result = $this->tokenSessionPayment($_token_session, $_encrypt_transaction); 
            }
        }

        return $this->response_payment($_result);
    }

    //funcion que retorna el pago despues de haber sido creado...
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

    //funcion que busca un usuario en base al email...
    private function findUserbyEmail($email)
    {
        $_result = Client::where('email', $email)->get();

        ($_result->isEmpty())? $_result = null : $_result;

        return $_result;
    }

    //funcion que crea una sesion de token en base de datos...
    private function tokenSessionPayment($token_session, $encrypt_payment)
    {
        Session::Create(['id'=>$token_session, 'token'=>$encrypt_payment]);

        return ['session_id'=> $token_session, 'transaction'=> $encrypt_payment];
    }

    //funcion que crea un toen de 6digitos
    private function makeTokenPayment($long_string = 6)
    {
        ($long_string < 6)? $long_string = 6: $long_string;
        return bin2hex(openssl_random_pseudo_bytes(($long_string - ($long_string % 2)) / 2));
    }

    //funcion para confirmar el pago
    public function confirmPayment($_id)
    {
        //recibe y borra la sesión de pago pendiente en base de datos
        $_transaction = Session::find($_id);

        //si existe una transaccion...
        if($_transaction)
        {
            $_aux = $_transaction;
            $_transaction->delete();
            //reciclo la variable para retornar la respuesta de la operación de confirmación retornando el balance del cliente...
            $_transaction = $this->changeAccountBalance(decrypt($_aux->token));

        }else
        {
            $_transaction = null;
        }

        return $this->response_confirm($_transaction);
    }

    //funcion para respuesta de confirmación de pago
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

    //funcion para completar la transacción
    private function changeAccountBalance($_transaction)
    {
        //busco a ambas partes, usuario y cliente
        $_customer = $this->findUserbyEmail($_transaction['Id_CLIENT']);
        $_seller   = $this->findUserbyEmail($_transaction['Id_SELLER']);

        //procedo a operar el balance monetario entre ambos..
        $_customer[0]->amount = $_customer[0]->amount - $_transaction['amount']; 
        $_seller[0]->amount   = $_seller[0]->amount + $_transaction['amount']; 
        //luego actualizo dicho balance de ambas partes...
        Client::where('email',$_customer[0]->email)->update(['amount' => $_customer[0]->amount]);
        Client::where('email',$_seller[0]->email)->update(['amount' => $_seller[0]->amount]);

        return $_customer;
    }
}
