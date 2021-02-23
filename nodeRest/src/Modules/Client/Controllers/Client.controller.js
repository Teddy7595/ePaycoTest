const app = require('express');
const CLIENT_MODULE = app();
const Service = 
{
	signin: require('../Services/signin.service')
};
let _Response = require('../../../Interfaces/response.interface');

CLIENT_MODULE.get('/hello',(req, res)=>
{
	return res.status(200).json('Ruta de registro de cliente');
});

CLIENT_MODULE.post('/signin', (req,res)=>
{
	let signinService = new Service.signin();
	_Response = signinService.callSoapSignin(req);
	return res.status(_Response.status).json(_Response);
});

module.exports = CLIENT_MODULE;
