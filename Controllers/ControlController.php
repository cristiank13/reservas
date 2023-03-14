<?php

/*
 * Esto Controladore recibe todas las peticiones 
 * y las redirecciona a los Controllers correspondientes
 * Tambien contiene el autoload que carga las clases en los demas controladores
*/

//Se determina el metodo por el cual se debe invocar el controlador
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'GET':
        $parametros = $_GET;
        break;
    case 'POST':
        $parametros = $_POST;
        break;
    case 'PUT':
        $parametros = $_POST;
        break;
    default:
        echo 'Metodo no permitido';
        return;
        break;
}


if (!empty($parametros["metodo"]) && !empty($parametros["clase"])) {

    local_autoload('GeneralController');
    local_autoload($parametros["clase"]);
    

    $clase  = new $parametros["clase"]();
    $metodo = $parametros["metodo"];
    unset($parametros["clase"], $parametros["metodo"]);

    echo $clase->$metodo($parametros);
} else {
    echo 'Falta el metodo o la clase';
}

function local_autoload($class_name)
{
    $filename = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    
    if (file_exists($filename)) {
        require_once $filename;
        return true;
    } else {
        $filename = str_replace('\\', DIRECTORY_SEPARATOR, __DIR__ . "/../" . $class_name) . '.php';
        if (file_exists($filename)) {
            require_once $filename;
            return true;
        }
    }
    return false;
}
