<?php

namespace Controllers;

class GeneralController
{
    public function __construct()
    {
        //header("Location: ../404.html");
        //exit;
        spl_autoload_register('local_autoload');
    }
}
