<?php

namespace Models;

require_once('Model.php');

class Reserva extends Model
{

    Const TABLA = "reservas";
    Const ID = "idreserva";

    protected static function dataPrivate()
    {   
        return [];
    }

}
