<?php

class Conexion
{
    private $host = 'localhost';
    private $dbname = 'reservas';
    private $username = 'saia';
    private $password = 'cerok_saia';
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
        } catch (PDOException $e) {
            die("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
