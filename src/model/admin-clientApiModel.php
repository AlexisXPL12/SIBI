<?php
require_once "../library/conexion.php";

class ClientApiModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarCliente($ruc, $razon_social, $telefono, $correo, $fecha_registro, $estado)
    {
        $sql = $this->conexion->query("
            INSERT INTO client_api (ruc, razon_social, telefono, correo, fecha_registro, estado)
            VALUES ('$ruc', '$razon_social', '$telefono', '$correo', '$fecha_registro', '$estado')
        ");
        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado)
    {
        $sql = $this->conexion->query("
            UPDATE client_api
            SET ruc='$ruc', razon_social='$razon_social', telefono='$telefono', correo='$correo', estado='$estado'
            WHERE id='$id'
        ");
        return $sql;
    }

    public function buscarClienteById($id)
    {
        $sql = $this->conexion->query("SELECT * FROM client_api WHERE id='$id'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarClientesConFiltros($busqueda_ruc, $busqueda_razon_social, $busqueda_estado)
    {
        $condicion = "1=1";
        if (!empty($busqueda_ruc)) {
            $condicion .= " AND ruc LIKE '%$busqueda_ruc%'";
        }
        if (!empty($busqueda_razon_social)) {
            $condicion .= " AND razon_social LIKE '%$busqueda_razon_social%'";
        }
        if ($busqueda_estado !== '') {
            $condicion .= " AND estado = '$busqueda_estado'";
        }

        $arrRespuesta = array();
        $query = "SELECT * FROM client_api WHERE $condicion ORDER BY razon_social ASC";
        $respuesta = $this->conexion->query($query);
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function contarClientesConFiltros($busqueda_ruc, $busqueda_razon_social, $busqueda_estado)
    {
        $condicion = "1=1";
        if (!empty($busqueda_ruc)) {
            $condicion .= " AND ruc LIKE '%$busqueda_ruc%'";
        }
        if (!empty($busqueda_razon_social)) {
            $condicion .= " AND razon_social LIKE '%$busqueda_razon_social%'";
        }
        if ($busqueda_estado !== '') {
            $condicion .= " AND estado = '$busqueda_estado'";
        }

        $query = "SELECT COUNT(*) as total FROM client_api WHERE $condicion";
        $respuesta = $this->conexion->query($query);
        $objeto = $respuesta->fetch_object();
        return $objeto->total;
    }
}
?>

