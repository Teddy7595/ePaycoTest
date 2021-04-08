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
			//cro el molde del XML para luego a través de una funcion ó librería convertir la respuesta en SOAP
    		$_aux=
			[
				'data'=> $nuevo->toArray(), 
				'ok'=> true, 
				'status' => 201, 
				'message' => 'Gracias por registrarte'

			];

			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);
    		
    	} catch (\Exception $e) 
    	{
    		$_aux=
			[
				'ok'=> false, 
				'status' => 500, 
				'message' => $e->getMessage()
				
			];
			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);
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
			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);
    	}else
    	{
    		$_aux=
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrro al cliente'

			];
			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);
    	}
    }

    //funcion de recarga de saldo
    public function recharge(Request $request)
    {
		//busc al cliente en base a los datos solicitados...
    	$cliente = Client::where('id_card', $request->toArray()['id_card'])
    					->where('phone', $request->toArray()['phone'])->get();

		//si hay cliente...
    	if(sizeof($cliente) > 0)
    	{
			//reargo el saldo
    		$cliente[0]->amount = $cliente[0]->amount + $request->amount;
			
			//actualizo el saldo del cliente
			Client::where('id_card', $request->toArray()['id_card'])
    			->where('phone', $request->toArray()['phone'])
				->update(['amount' => $cliente[0]->amount]);

			//creo el molde/objeto que tendrá el XML
    		$_aux=
			[
				'data'=> ['saldo' => $cliente[0]->amount], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Recarga exitosa!'

			];
			
			//lo retorno como SOAP
			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);

    	}else
    	{
    		$_aux=
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrro al cliente'

			];
			
			//return response($_aux, $_aux['status'])->header('Content-Type', 'application/json');
			return response()->xml($_aux, $_aux['status']);
    	}
    }

    public function payment(Request $request)
    {
		$_result = $this->_Payment->makePaySession($request);

    	//return response($_result, $_result['status'])->header('Content-Type', 'application/json');
		return response()->xml($_result, $_result['status']);
    }

	public function confirm(Request $request, $id)
	{
		$_result = $this->_Payment->confirmPayment($id);
    	//return response($_result, $_result['status'])->header('Content-Type', 'application/json');
		return response()->xml($_result, $_result['status']);
	}
}
