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
    public function buscarClienteById($id)
    {
        $sql = $this->conexion->query("SELECT * FROM client_api WHERE id='$id'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarBienByDenominacion($data)
    {
        $arrRespuesta = array();
        $sql = $this->conexion->query("SELECT * FROM bienes WHERE nombre_bien LIKE '%$data%'");
        while ($objeto = $sql->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
}
