let _Response = require('../../../Interfaces/response.interface');// estandarizacion de respuestas
const call = require('node-fetch');//fetch para llamar la API vecina

const app = require('express');//inicialización de express para rutas

const XML = require('xml-js');

const WALLET_ROUTE = app();//seteo de express

const URL_ROOT = 'http://127.0.0.1:8000';//ruta del API LARAVEL

const removeJsonTextAttribute = (value, parentElement)=>
{
	try {
		const parentOfParent = parentElement._parent;
		const pOpKeys = Object.keys(parentElement._parent);
		const keyNo = pOpKeys.length;
		const keyName = pOpKeys[keyNo - 1];
		const arrOfKey = parentElement._parent[keyName];
		const arrOfKeyLen = arrOfKey.length;
		if (arrOfKeyLen > 0) {
			const arr = arrOfKey;
			const arrIndex = arrOfKey.length - 1;
			arr[arrIndex] = value;
		} else {
			parentElement._parent[keyName] = value;
		}
	} catch (e) { }
};

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
	.then( response => response.text())
	.then( (data) =>
	{ 

		let aux = XML.xml2json(data, {compact: true, sanitize: true, spaces:1, ignoreDeclaration: true, textFn: removeJsonTextAttribute});
		_Response = JSON.parse(aux);

		return res.status(200).json(_Response); 
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
	.then( response => response.text())
	.then( (data) =>
	{ 
		let aux = XML.xml2json(data, {compact: true, sanitize: true, spaces:0, ignoreDeclaration: true, textFn: removeJsonTextAttribute});
		_Response = JSON.parse(aux);
		return res.status(200).json(_Response);   
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
	.then( response => response.text())
	.then( (data) =>
	{ 
		let aux = XML.xml2json(data, {compact: true, sanitize: true, spaces:0, ignoreDeclaration: true, textFn: removeJsonTextAttribute});
		_Response = JSON.parse(aux);
		return res.status(200).json(_Response);  
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/confirm/:id',(req, res)=>
{//ruta de confirmación de pago
	let id = req.params['id'];

	call(URL_ROOT+'/wallet/confirm/'+id,
	{
		method: 'POST',
		headers:
		{
			'Content-Type': 'application/json'
		},
		body: null,
		cache: 'no-cache'
	})
	.then( response => response.text())
	.then( (data) =>
	{ 
		let aux = XML.xml2json(data, {compact: true, sanitize: true, spaces:0, ignoreDeclaration: true, textFn: removeJsonTextAttribute});
		_Response = JSON.parse(aux);
		return res.status(200).json(_Response);  
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
	};

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
	.then( response => response.text())
	.then( (data) =>
	{ 
		let aux = XML.xml2json(data, {compact: true, sanitize: true, spaces:0, ignoreDeclaration: true, textFn: removeJsonTextAttribute});
		_Response = JSON.parse(aux);
		return res.status(200).json(_Response);  
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

//exportación del modulo de rutas
module.exports = WALLET_ROUTE;
