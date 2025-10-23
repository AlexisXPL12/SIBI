<?php
require_once "../library/conexion.php";

class ApiBienModel
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
}