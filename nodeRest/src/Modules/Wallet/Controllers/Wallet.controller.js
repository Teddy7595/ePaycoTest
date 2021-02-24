let _Response = require('../../../Interfaces/response.interface');
const app = require('express');
const WALLET_ROUTE = app();

let Service =
{
	wallet: require('../Services/wallet.service')
}

WALLET_ROUTE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta crud de wallet '); 
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
