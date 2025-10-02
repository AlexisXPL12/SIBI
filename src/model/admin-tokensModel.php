<?php
require_once "../library/conexion.php";
class TokenModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function generar_llave($cantidad = 32)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/}{[]@#$%&*()';
        $llave = '';
        $max = strlen($permitted_chars) - 1;
        for ($i = 0; $i < $cantidad; $i++) {
            $llave .= $permitted_chars[rand(0, $max)];
        }
        return $llave;
    }

    public function registrarToken($id_client_api, $fecha_registro, $estado)
    {
        $token = $this->generar_llave(); // Genera el token automáticamente
        $sql = $this->conexion->query("
            INSERT INTO tokens (id_client_api, token, fecha_registro, estado)
            VALUES ('$id_client_api', '$token', '$fecha_registro', '$estado')
        ");
        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarToken($id, $id_client_api, $token, $estado)
    {
        $sql = $this->conexion->query("
            UPDATE tokens
            SET id_client_api='$id_client_api', token='$token', estado='$estado'
            WHERE id='$id'
        ");
        return $sql;
    }

    public function buscarTokensConFiltros($busqueda_token, $busqueda_cliente, $busqueda_estado)
    {
        $condicion = "1=1";
        if (!empty($busqueda_token)) {
            $condicion .= " AND t.token LIKE '%$busqueda_token%'";
        }
        if (!empty($busqueda_cliente)) {
            $condicion .= " AND t.id_client_api = '$busqueda_cliente'";
        }
        if ($busqueda_estado !== '') {
            $condicion .= " AND t.estado = '$busqueda_estado'";
        }
        $arrRespuesta = array();
        $query = "
            SELECT t.*, c.razon_social
            FROM tokens t
            JOIN client_api c ON t.id_client_api = c.id
            WHERE $condicion
            ORDER BY t.fecha_registro DESC
        ";
        $respuesta = $this->conexion->query($query);
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function contarTokensConFiltros($busqueda_token, $busqueda_cliente, $busqueda_estado)
    {
        $condicion = "1=1";
        if (!empty($busqueda_token)) {
            $condicion .= " AND t.token LIKE '%$busqueda_token%'";
        }
        if (!empty($busqueda_cliente)) {
            $condicion .= " AND t.id_client_api = '$busqueda_cliente'";
        }
        if ($busqueda_estado !== '') {
            $condicion .= " AND t.estado = '$busqueda_estado'";
        }
        $query = "
            SELECT COUNT(*) as total
            FROM tokens t
            JOIN client_api c ON t.id_client_api = c.id
            WHERE $condicion
        ";
        $respuesta = $this->conexion->query($query);
        $objeto = $respuesta->fetch_object();
        return $objeto->total;
    }
}
?>
