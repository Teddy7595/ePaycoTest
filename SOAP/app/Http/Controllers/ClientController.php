<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Client;

class ClientController extends Controller
{

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

			],202)->header('Content-type', 'json');
    		
    	} catch (Exception $e) 
    	{
    		return response(
			[
				'ok'=> false, 
				'status' => 500, 
				'message' => $e->getMessage()
				
			],500)->header('Content-type', 'json');
    	}
    }

    //funcion de consulta de saldo
    public function status(Request $request)
    {
    	$cliente = Client::where('email', $request->toArray()['email'])->get();
    	
    	if($cliente)
    	{
    		return response(
			[
				'data'=> $cliente->toArray()[0]['amount'], 
				'ok'=> true, 
				'status' => 202, 
				'message' => 'Tiene un saldo de...'

			],202)->header('Content-type', 'json');
    	}else
    	{
    		return response(
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrró al cliente'

			],404)->header('Content-type', 'json');
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
    		$cliente->amount = $request->amount;

    		try 
	    	{
	    		$cliente->save();

	    		return response(
				[
					'data'=> ['saldo' => $cliente->amount], 
					'ok'=> true, 
					'status' => 202, 
					'message' => 'Recarga exitosa!'

				],202)->header('Content-type', 'json');
	    		
	    	} catch (Exception $e) 
	    	{
	    		return response(
				[
					'ok'=> false, 
					'status' => 500, 
					'message' => $e->getMessage()
					
				],500)->header('Content-type', 'json');
	    	}

    	}else
    	{
    		return response(
			[ 
				'ok'=> false, 
				'status' => 404, 
				'message' => 'No se encontrró al cliente'

			],404)->header('Content-type', 'json');
    	}
    }
}
