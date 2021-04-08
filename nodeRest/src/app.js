let express = require('express');
// importación de bodyparser para el uso de envio de formularios en post
let bodyParser = require("body-parser");
// inicializar las variables necesarias

// se inicializa express
let app = express();
//establezco el puerto donde se alojará el servicio
app.set('PORT', 8001)

app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());

//importo los modulos del backend
const wallet = require('./Controller/Wallet.controller.js');
app.use('/wallet', wallet);

app.listen(app.get('PORT'), ()=>
{
	
	console.log('SERVICIO EN LINEA');
  

})
