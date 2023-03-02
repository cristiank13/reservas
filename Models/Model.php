<?php

namespace Models;

use PDO;
use Conexion;

require_once('../Controllers/Conexion.php');

class Model 
{
    public $clase;
    public $tabla;
    public $atributos;

    function __construct($id = null)
    {
        $this->clase = get_called_class();
        $this->tabla = $this->clase::TABLA;
        $this->atributos = $this->getatributos($id);
    }

    public function getatributos($id = null)
    {   
        $Conn = new Conexion();
        $pdo = $Conn->getPdo();

        if ((int)$id) {    
            $atributos = [];

            $campoId = $this->clase::ID;
            $consulta = $pdo->prepare("SELECT * FROM $this->tabla WHERE $campoId=$id");

            $consulta->execute();
            
            $resultados = $consulta->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($resultados as $llave) {
                foreach ($llave as $attr => $valor) {
                    $atributos[$attr] = $valor;
                }           
            }
            

        }else{

            $sql = "SHOW COLUMNS FROM $this->tabla";
            $resultado = $pdo->query($sql);

            foreach ($resultado as $fila) {
                $atributos[$fila['Field']] = null;
            }
        }

        $atributos = $this->deleteAttrPrivate($atributos);

        return $atributos;
    }

    public function setatributos(array $atributos)
    {  
        foreach($atributos as $llave => $valor){
            $this->atributos[$llave] = $valor;
        }
    }

    private function deleteAttrPrivate(array $atributos)
    {   
        $privados = $this->clase::dataPrivate();
        foreach($privados as $llave){
            unset($atributos[$llave]);
        }

        return $atributos;
        
    }

    public function save()
    {
        try{
            $atributos = $this->atributos;
            $campos = [];
            $referencia = [];
            $valores = [];

            if(!empty($atributos)){
                unset($atributos[$this->clase::ID]);

                foreach($atributos as $llave => $valor){
                    if(empty($valor)){
                        unset($atributos[$llave]);
                    } else {
                        $campos[] = $llave;
                        $referencia[] = '?';
                        $valores[] = $valor;
                    }
                }

                $Conn = new Conexion();
                $pdo = $Conn->getPdo();

                $tabla = $this->tabla;
                $sqlCampos = implode(',', $campos);
                $sqlReferencia = implode(',', $referencia);


                $sql = "INSERT INTO $tabla ($sqlCampos) VALUES ($sqlReferencia)";
                $stmt = $pdo->prepare($sql);
                $this->typeElementQuery($atributos, $stmt);
        
                $stmt->execute();
               
                return $pdo->lastInsertId();

            }

        }catch(\Exception $e){
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function update()
    {
        try{
            $atributosModificados = $this->atributos;

            if(!empty($atributosModificados)){
                $atributos = [];
                $campos = [];
                $valores = [];
                $retorno = 0;

                $id = $atributosModificados[$this->clase::ID];

                $AtributosIniciales = $this->getatributos($id);

                //retorna solo los valores modificados
                foreach($atributosModificados as $llave => $valor){
                    if($atributosModificados[$llave] !== $AtributosIniciales[$llave]){
                        $atributos[$llave] = $valor;
                        $campos[] = "$llave = ?";
                        $valores[] = $valor;
                    }
                }
   
                unset($atributos[$this->clase::ID]);

                $Conn = new Conexion();
                $pdo = $Conn->getPdo();

                $tabla = $this->tabla;
                $sqlCampos = implode(',', $campos);
                $sqlReferencia = $this->clase::ID . "=" . $id;

                $sql = "update $tabla set $sqlCampos where $sqlReferencia";
                $stmt = $pdo->prepare($sql);
                $this->typeElementQuery($atributos, $stmt);
                $resultado = $stmt->execute();

                if ($resultado && $stmt->rowCount() > 0) {
                    $retorno = $id;
                } 
                
                return $retorno;
                
            }

        }catch(\Exception $e){
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function typeElementQuery(array $atributos, $stmt)
    {
        $cont = 1;
        foreach ($atributos as $atributo => &$valor) {
            if(is_numeric($valor)){
                $tipo = PDO::PARAM_INT;
            }else{
                $tipo = PDO::PARAM_STR;
            }

            $stmt->bindParam($cont, $valor, $tipo);
            $cont++;   
        }

        return $stmt;
    }
}