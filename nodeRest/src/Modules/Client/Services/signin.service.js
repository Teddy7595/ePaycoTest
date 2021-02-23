let response = require('../../../Interfaces/response.interface');
let signin 	 = require('../Models/signin.model');

class SigninService
{
	callSoapSignin(req)
	{
		if
		(
			!req.body.name || 
			!req.body.lst_name ||
			!req.body.phone ||
			!req.body.mail)
		{
            response.status  = 203;
            response.ok 	 = false;
            response.message = "Ha faltado algún dato, por favor corregir";
        }
        else
        {
			response.status  = 202;
            response.ok 	 = true;
            response.message = "Gracias por registrarte";
        	response.data 	 = new signin(req.body);
        }
		

		return response;
	}
}

module.exports = SigninService;
