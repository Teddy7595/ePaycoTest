let _Response = require('../../../Interfaces/response.interface');// estandarizacion de respuestas

const call = require('node-fetch');//fetch para llamar la API vecina

const app = require('express');//inicialización de express para rutas

const WALLET_ROUTE = app();//seteo de express

const URL_ROOT = 'http://127.0.0.1:8000';//ruta del API LARAVEL

WALLET_ROUTE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta crud de wallet '); 
	//ruta de saludo para confirmar estructura del módulo
}); 

WALLET_ROUTE.post('/signin',(req,res)=>
{//ruta de registro
	const send =
	{
		"name"      : req.body.name,
		"lst_name"  : req.body.lst_name,
		"email"     : req.body.email,
		"phone"     : req.body.phone,
		"id_card"   : req.body.id_card
	}

	call(URL_ROOT+'/wallet/signin',
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(send),
		cache: 'no-cache'
	})
	.then( response => response.json())
	.then( (data) =>
	{ 
		_Response = data;
		return res.status(_Response.status).json(_Response); 
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/recharge',(req, res)=>
{//recarga de billetera
	const send =
	{
		"amount"    : req.body.amount,
		"phone"     : req.body.phone,
		"id_card"   : req.body.id_card
	}

	call(URL_ROOT+'/wallet/recharge',
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(send),
		cache: 'no-cache'
	})
	.then( response => response.json())
	.then( (data) =>
	{ 
		_Response = data;
		return res.status(_Response.status).json(_Response); 
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/payment',(req, res)=>
{//ruta de pago
	const send =
	{
		"Id_SELLER"     : req.body.Id_SELLER,
    	"Id_CLIENT"     : req.body.Id_CLIENT,
    	"amount"        : req.body.amount,
    	"descp"         : req.body.descp,
	}

	call(URL_ROOT+'/wallet/payment',
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(send),
		cache: 'no-cache'
	})
	.then( response => response.json())
	.then( (data) =>
	{ 
		_Response = data;
		return res.status(_Response.status).json(_Response); 
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/confirm/:id',(req, res)=>
{//ruta de confirmación de pago

	call(URL_ROOT+'/wallet/confirm/'+req.params.id,
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: null,
		cache: 'no-cache'
	})
	.then( response => response.json())
	.then( (data) =>
	{ 
		_Response = data;
		return res.status(_Response.status).json(_Response);  
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/status',(req, res)=>
{//ruta de consulta de saldos
	const send =
	{
		"email": req.body.email
	}

	call(URL_ROOT+'/wallet/status',
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: JSON.stringify(send),
		cache: 'no-cache'
	})
	.then( response => response.json())
	.then( (data) =>
	{ 
		_Response = data;
		return res.status(_Response.status).json(_Response);  
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

//exportación del modulo de rutas
module.exports = WALLET_ROUTE;
