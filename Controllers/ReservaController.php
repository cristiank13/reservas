<?php

use Models\Reserva;
use Controllers\GeneralController;

class ReservaController extends GeneralController
{
    public function guardar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible guardar",
            "success" => "0",
            "data"    => ""
        ];

        if (!empty($parametros['idreserva'])) {
            $respuesta["message"] = "Este registro ya existe no se puede guardar";
            return json_encode($parametros);
        }

        $Reserva = new Reserva();
        $Reserva->setatributos(
            $parametros
        );

        if ($id = $Reserva->save()) {
            $respuesta["message"] = "Proceso exitos";
            $respuesta["success"] = 1;
            $respuesta["data"] = $id;
        } else {
            $respuesta["message"] = "Se presento un error al momento de guardar";
        }

        return json_encode($respuesta);
    }

    public function actualizar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible guardar",
            "success" => "0",
            "data"    => ""
        ];

        if (empty($parametros['idreserva'])) {
            $respuesta["message"] = "No se encuentra el registro para actualizar";
            return json_encode($parametros);
        }

        $Reserva = new Reserva($parametros['idreserva']);
        $Reserva->setatributos(
            $parametros
        );

        if ($id = $Reserva->update()) {
            $respuesta["message"] = "Proceso exitoso";
            $respuesta["success"] = 1;
            $respuesta["data"] = $id;
        } else {
            $respuesta["message"] = "Se presento un error al momento de actualizar";
        }

        return json_encode($respuesta);
    }

    public function cargar($parametros)
    {
        $respuesta = [
            "message" => "No fue posible cargar",
            "success" => "0",
            "data"    => []
        ];

        $Reserva = new Reserva($parametros['idreserva']);
        if ($Reserva->atributos['idreserva']) {
            $respuesta["message"] = 'Carga exitosa';
            $respuesta["success"] = 1;
            $respuesta["data"] = $Reserva->atributos;
        }

        return json_encode($respuesta);
    }

    public function cargarTodos()
    {
        $respuesta = [
            "message" => "No fue posible cargar",
            "success" => "0",
            "data"    => []
        ];

        $Reserva = new Reserva();
        $datos = $Reserva->getTodos();

        if (!empty($datos)) {
            $respuesta["message"] = 'Carga exitosa';
            $respuesta["success"] = 1;
            $respuesta["data"] = $datos;
        }

        
        return json_encode($respuesta);
    }
}
