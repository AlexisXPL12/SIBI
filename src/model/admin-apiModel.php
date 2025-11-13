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
    public function buscarBienByDenominacion($data, $codigo_patrimonial = null)
    {
        $arrRespuesta = array();
        $data_escaped = $this->conexion->real_escape_string($data);

        if (!empty($codigo_patrimonial)) {
            // Si se proporciona código patrimonial, buscar por ese campo
            $codigo_escaped = $this->conexion->real_escape_string($codigo_patrimonial);
            $sql = $this->conexion->query("
            SELECT b.*, d.nombre_dependencia
            FROM bienes b
            LEFT JOIN dependencias d ON b.id_dependencia = d.id_dependencia
            WHERE b.codigo_patrimonial LIKE '%{$codigo_escaped}%'
        ");
        } else {
            // Si no, buscar por denominación (como antes)
            $sql = $this->conexion->query("
            SELECT b.*, d.nombre_dependencia
            FROM bienes b
            LEFT JOIN dependencias d ON b.id_dependencia = d.id_dependencia
            WHERE b.nombre_bien LIKE '%{$data_escaped}%'
        ");
        }

        if ($sql) {
            while ($objeto = $sql->fetch_object()) {
                array_push($arrRespuesta, $objeto);
            }
        }

        return $arrRespuesta;
    }
    public function buscarBienByCodigoPatrimonial($codigo_patrimonial) {
    $arrRespuesta = array();
    $codigo_escaped = $this->conexion->real_escape_string($codigo_patrimonial);
    $sql = $this->conexion->query("
        SELECT b.*, d.nombre_dependencia
        FROM bienes b
        LEFT JOIN dependencias d ON b.id_dependencia = d.id_dependencia
        WHERE b.codigo_patrimonial LIKE '%{$codigo_escaped}%'
    ");

    if ($sql) {
        while ($objeto = $sql->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
    }

    return $arrRespuesta;
}

}
