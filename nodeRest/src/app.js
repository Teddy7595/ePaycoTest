let express = require('express');

let mongoose = require("mongoose");
// importación de bodyparser para el uso de envio de formularios en post
let bodyParser = require("body-parser");
// ------------
// cors
let https = require('https');
let cors = require("cors");
// inicializar las variables necesarias
// se inicializa express
let app = express();
//establezco el puerto donde se alojará el servicio
const PORT_REST = 8001;

//establezco el policia CORS
/*const allowedOrigins = ['http://localhost:8001'];
app.use(cors(
	{
  		origin: function (origin, callback)
  		{
    		// allow requests with no origin
    		// (like mobile apps or curl requests)
    		if (!origin) return callback(null, true);
    		if (allowedOrigins.indexOf(origin) === -1) 
    		{
    		  var msg = 'The CORS policy for this site does not allow access from the specified Origin.';
    		  return callback(new Error(msg), false);
    		}
    		return callback(null, true);
  		}
	}
));*/ //lo desactivo para fines prácticos

app.use(bodyParser.urlencoded({extended: true}));
app.use(bodyParser.json());

mongoose.connection.openUri('mongodb://localhost:27017/PruebaPayco',{useNewUrlParser: true, useUnifiedTopology: true } ,(err, res) =>
{
  // si hay un error entonces
  if (err) throw err;
  console.logog('Base de datos Mongo: En linea');
  console.clear();
});

//importo los modulos del backend
const client = require('./Modules/Client/client.module.js').CLIENT_MODULE;
const wallet = require('./Modules/Wallet/wallet.module.js').WALLET_MODULE;

app.use('/client', client);
app.use('/wallet', wallet);

app.listen(PORT_REST, ()=>
{
	console.log('SERVICIO EN LINEA');
  

})
