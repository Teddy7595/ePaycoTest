let _Response = require('../../../Interfaces/response.interface');
const call = require('node-fetch');
const app = require('express');
const WALLET_ROUTE = app();
const URL_ROOT = 'http://127.0.0.1:8000';

WALLET_ROUTE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta crud de wallet '); 
}); 

WALLET_ROUTE.post('/signin',(req,res)=>
{
	const send =
	{
		"name"      : req.body.name,
		"lst_name"  : req.body.lst_name,
		"email"     : req.body.email,
		"phone"     : req.body.phone,
		"id_card"   : req.body.id_card
	}
	console.log(send);

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
		console.log(data);
		return res.status(202).json(data); 
	})
	.catch( (err) =>
	{
		console.log(err);
		return res.status(500).json(err);
	});
});

WALLET_ROUTE.post('/recharge',(req, res)=>
{
	return res.status(200).json(_Response); 
});

WALLET_ROUTE.post('/payment',(req, res)=>
{
	return res.status(200).json(_Response); 
});

WALLET_ROUTE.post('/payment/confirm',(req, res)=>
{
	return res.status(200).json(_Response); 
});

WALLET_ROUTE.post('/amount',(req, res)=>
{
	return res.status(200).json(_Response); 
});

module.exports = WALLET_ROUTE;
