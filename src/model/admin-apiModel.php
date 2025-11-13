<?php
require_once "../library/conexion.php";

class ApiModel
{
    private $conexion;
    
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }
    
    // Buscar token en la base de datos
    public function buscarToken($token)
    {
        // Escapar el token para prevenir SQL injection pero permitir tokens complejos
        $token_escaped = $this->conexion->real_escape_string($token);
        $sql = $this->conexion->query("SELECT * FROM tokens WHERE token = '{$token_escaped}' LIMIT 1");
        
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_object();
        }
        return false;
    }
    
    // Buscar cliente por ID
    public function buscarClienteById($id)
    {
        // Asegurar que el ID es un número entero
        $id = intval($id);
        $sql = $this->conexion->query("SELECT * FROM client_api WHERE id = {$id} LIMIT 1");
        
        if ($sql && $sql->num_rows > 0) {
            return $sql->fetch_object();
        }
        return false;
    }
    
    // Buscar bienes por denominación
    public function buscarBienByDenominacion($data)
    {
        $arrRespuesta = array();
        // Escapar el término de búsqueda
        $data_escaped = $this->conexion->real_escape_string($data);
        $sql = $this->conexion->query("SELECT * FROM bienes WHERE nombre_bien LIKE '%{$data_escaped}%'");
        
        if ($sql) {
            while ($objeto = $sql->fetch_object()) {
                array_push($arrRespuesta, $objeto);
            }
        }
        
        return $arrRespuesta;
    }
}
