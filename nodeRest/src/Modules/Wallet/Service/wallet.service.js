let _Response = require('../../../Interfaces/response.interface');// estandarizacion de respuestas

const xml2js = require('xml2js');

class WalletService
{
    SendEmail(value) { console.log(value); }

    SanitizeJSONStatus(xml) 
    {
         xml2js.parseString(xml, (err, result) => 
        {
            if(err) {
                throw err;
            }
            const json = JSON.stringify(result, null, 2);
        
            _Response = json;

            console.log(_Response);
            return _Response;
           
        });
    }
}

module.exports = WalletService;