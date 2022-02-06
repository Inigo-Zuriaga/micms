<?php
namespace App\Model;
//las variables de datos de la bbdd PERSONA
class Persona {

    //Variables o atributos
    var $id;
    var $persona;
    var $clave;
    var $fecha_acceso;
    var $activo;
    var $personas;
    var $juegos;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->persona = ($data) ? $data->persona : null;
        $this->clave = ($data) ? $data->clave : null;
        $this->fecha_acceso = ($data) ? $data->fecha_acceso : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->personas = ($data) ? $data->personas : null;
        $this->juegos = ($data) ? $data->juegos : null;

    }

}