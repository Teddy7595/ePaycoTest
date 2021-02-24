let _Response = require('../../../Interfaces/response.interface');
const http = require('http');
const app = require('express');
const WALLET_ROUTE = app();
const options =
{
	host: '127.0.0.1',
	port: 8000,
	path: '/'
};
const req = http.request(options);
req.end();

WALLET_ROUTE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta crud de wallet '); 
}); 

WALLET_ROUTE.post('/signin',(req,res)=>
{
	req.on(null,(info)=>
	{
		_Response.data = info;
		return res.status(200).json(_Response);
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
