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

    		$_aux=
			[
				'data'=> $nuevo->toArray(), 
				'ok'=> true, 
				'status' => 201, 
				'message' => 'Gracias por registrarte'

			];

			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			//return response()->xml($_aux, $_aux['status']);
    		
    	} catch (\Exception $e) 
    	{
    		$_aux=
			[
				'ok'=> false, 
				'status' => 500, 
				'message' => $e->getMessage()
				
			];
			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			//return response()->xml($_aux, $_aux['status']);
    	}
    }

    //funcion de consulta de saldo
    public function status(Request $request)
    {
    	$cliente = Client::where('email', $request->toArray()['email'])->get();
    	
    	if(sizeof($cliente) > 0)
    	{
    		$_aux=
			[
				'data'=> ['saldo' => $cliente->toArray()[0]['amount']], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Tiene un saldo de...'

			];
			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			//return response()->xml($_aux, $_aux['status']);
    	}else
    	{
    		$_aux=
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrro al cliente'

			];
			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			//return response()->xml($_aux, $_aux['status'], ['encoding' => "UTF-8"]);
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

    		$_aux=
			[
				'data'=> ['saldo' => $cliente->amount], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Recarga exitosa!'

			];
			//return response()->xml($_aux, $_aux['status']);
			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');

    	}else
    	{
    		$_aux=
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrro al cliente'

			];
			//return response()->xml($_aux, $_aux['status']);
			return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
    	}
    }

    public function payment(Request $request)
    {
		$_result = $this->_Payment->makePaySession($request);

    	return response($_result, $_result['status'])->header('Content-Type', 'application/json');
		//return response()->xml($_result, $_result['status']);
    }

	public function confirm(Request $request, $id)
	{
		$_result = $this->_Payment->confirmPayment($id);
    	return response($_result, $_result['status'])->header('Content-Type', 'application/json');
		//return response()->xml($_result, $_result['status']);
	}
}
