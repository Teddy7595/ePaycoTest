<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Src\Payment;

class ClientController extends Controller
{
	private Payment $_Payment;

	public function __construct()
	{
		$this->_Payment = new Payment();
	}

	//funcion de registro cliente
    public function signin(Request $request)
    {
    	$nuevo = new Client($request->toArray());
    	$nuevo->amount = 0.0;
    	try 
    	{
    		$nuevo->save();

    		return response(
			[
				'data'=> $nuevo, 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Gracias por registrarte'

			],202)->header('Content-Type', 'application/json');
    		
    	} catch (\Exception $e) 
    	{
    		return response(
			[
				'ok'=> false, 
				'status' => 500, 
				'message' => $e->getMessage()
				
			],500)->header('Content-Type', 'application/json');
    	}
    }

    //funcion de consulta de saldo
    public function status(Request $request)
    {
    	$cliente = Client::where('email', $request->toArray()['email'])->get();
    	
    	if(sizeof($cliente) > 0)
    	{
    		return response(
			[
				'data'=> ['saldo' => $cliente->toArray()[0]['amount']], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Tiene un saldo de...'

			],202)->header('Content-Type', 'application/json');
    	}else
    	{
    		return response(
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrró al cliente'

			],404)->header('Content-Type', 'application/json');
    	}
    }

    //funcion de recarga de saldo
    public function recharge(Request $request)
    {
    	$cliente = Client::where('id_card', $request->toArray()['id_card'])
    					 ->where('phone', $request->toArray()['phone'])
    					 ->find(1);
    	if($cliente)
    	{
    		$cliente->amount = $cliente->amount + $request->amount;

    		$cliente->update();

    		return response(
			[
				'data'=> ['saldo' => $cliente->amount], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Recarga exitosa!'

			],202)->header('Content-Type', 'application/json');

    	}else
    	{
    		return response(
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrró al cliente'

			],404)->header('Content-Type', 'application/json');
    	}
    }

    public function payment(Request $request)
    {
		$_result = $this->_Payment->makePaySession($request);

    	return response($_result, $_result['status'])->header('Content-Type', 'application/json');
    }

	public function confirm(Request $request, $id)
	{
		return response(
			[
				'data'=> "dsadsadasdasdasdasdas",//session()->get($id), 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Recarga exitosa!'

			],202)->header('Content-Type', 'application/json');
	}
}
