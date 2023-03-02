<?php

use Models\Reserva;

class ReservaController
{
    public function __construct() {
        spl_autoload_register('local_autoload');
    }

    public function guardar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible guardar",
            "success" => "0",
            "data"    => ""
        ];

        if (!empty($parametros['idreserva'])){
            $respuesta["message"] = "Este registro ya existe no se puede guardar";
            return json_encode($parametros);
        } 

        $Reserva = new Reserva();

        return json_encode($parametros);
    }

}