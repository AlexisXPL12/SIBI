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
        // Usar prepared statements para prevenir SQL injection
        $stmt = $this->conexion->prepare("SELECT * FROM tokens WHERE token = ? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        $sql = $result->fetch_object();
        $stmt->close();
        return $sql;
    }
    
    // Buscar cliente por ID
    public function buscarClienteById($id)
    {
        // Usar prepared statements para prevenir SQL injection
        $stmt = $this->conexion->prepare("SELECT * FROM client_api WHERE id = ? LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $sql = $result->fetch_object();
        $stmt->close();
        return $sql;
    }
    
    // Buscar bienes por denominación
    public function buscarBienByDenominacion($data)
    {
        $arrRespuesta = array();
        // Usar prepared statements para prevenir SQL injection
        $searchTerm = "%{$data}%";
        $stmt = $this->conexion->prepare("SELECT * FROM bienes WHERE nombre_bien LIKE ?");
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($objeto = $result->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        
        $stmt->close();
        return $arrRespuesta;
    }
}
