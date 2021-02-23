const mongoose = require('mongoose');
let Schema = mongoose.Schema;

let signinSchema = new Schema
({
	name:
    {
        type:String,
        required:[true, 'Debe asignar un nombre']
    },

    lst_name:
    {
        type:String,
        required:[true, 'Debe asignar un apellido']
    },

    mail:
    {
        type:String,
        required:[true, 'Debe asignar un email de contacto']
    },

    phone:
    {
        type:String,
        required:[true, 'Debe asignar un teléfono de contacto']
    },

    id:
    {
        type:String,
        required:[true, 'Debe asignar un teléfono de contacto']
    },
});

module.exports = mongoose.model('Signin', signinSchema);
