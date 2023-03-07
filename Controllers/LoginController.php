<?php

use Models\Usuario;
use Controllers\GeneralController;

class LoginController extends GeneralController
{
    public function validarAcceso ($atributos) {
        $respuesta = [
            "message" => "Los datos de ingreso no son validos",
            "success" => 0,
            "data"    => []
        ];

        $resultado = Usuario::sqlConsultar($atributos);

        if (!empty($resultado)) {
            session_start();
            $_SESSION["idusuario"] = $resultado[0]['idusuario'];
            $_SESSION["login"] = $resultado[0]['login'];
            $_SESSION["genero"] = $resultado[0]['genero'];

            $respuesta['message'] = "Bienvenido";
            $respuesta['success'] = 1;
            $respuesta['data'] = [
                'idusuario' => $resultado[0]['idusuario'],
                'login' => $resultado[0]['login']
            ];
        }

        return json_encode($respuesta);
    }

    public function validarSesionActiva($atributos) {
        session_start();

        $respuesta = [
            "message" => "No tienen sesion",
            "success" => 0,
            "data"    => []
        ];

        if (empty($_SESSION["idusuario"]) || empty($_SESSION["login"])) {
            session_destroy();
        } else {
            $respuesta["message"] = "Sesión activa";
            $respuesta["success"] = 1;
            $respuesta['data'] = [
                'idusuario' => $_SESSION['idusuario'],
                'login' => $_SESSION['login'],
                'genero' => $_SESSION['genero']
            ];
        }

        return json_encode($respuesta);
    }

    public function cerrarSesion($atributos) {
        session_start();
        session_destroy();

        $respuesta = [
            "message" => "No tienen sesion",
            "success" => 1,
            "data"    => []
        ];

        return json_encode($respuesta);
    }

}