<?php

namespace Models;

use Models\Model;

require_once('Model.php');

class Usuario extends Model
{

    Const TABLA = "usuarios";
    Const ID = "idusuario";

    protected static function dataPrivate()
    {   
        return [];
    }

}

/*
$u = new Usuario();

$total = $u->getTodos();
print_r($total[1]);


/*
$u->setatributos([
    'login' => '32423',
    'clave' => '43534',
    'email' => 'dfsdf'
]);

var_dump($u->update());*/

