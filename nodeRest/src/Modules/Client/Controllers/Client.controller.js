let _Response = require('../../../Interfaces/response.interface');
const app = require('express');
CLIENT_ROUTE = app();

CLIENT_ROUTE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta de registro de cliente');
});

CLIENT_ROUTE.post('/signin', async (req,res)=>
{
	return res.status(200).json(_Response);
});

module.exports = CLIENT_ROUTE;
