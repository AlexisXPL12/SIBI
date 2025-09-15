<?php
require_once "../library/conexion.php";

class CarreraModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarCarrera($codigo_carrera, $nombre_carrera, $descripcion, $duracion_semestres, $coordinador, $estado)
    {
        $sql = $this->conexion->query("INSERT INTO carreras (codigo_carrera, nombre_carrera, descripcion, duracion_semestres, coordinador, estado)
        VALUES ('$codigo_carrera', '$nombre_carrera', '$descripcion', $duracion_semestres, '$coordinador', '$estado')");

        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarCarrera($id_carrera, $codigo_carrera, $nombre_carrera, $descripcion, $duracion_semestres, $coordinador, $estado)
    {
        $sql = $this->conexion->query("UPDATE carreras SET
            codigo_carrera='$codigo_carrera',
            nombre_carrera='$nombre_carrera',
            descripcion='$descripcion',
            duracion_semestres=$duracion_semestres,
            coordinador='$coordinador',
            estado='$estado'
            WHERE id_carrera=$id_carrera");

        return $sql;
    }

    public function buscarCarreraById($id_carrera)
    {
        $sql = $this->conexion->query("SELECT * FROM carreras WHERE id_carrera=$id_carrera");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarCarreraByCodigo($codigo_carrera)
    {
        $sql = $this->conexion->query("SELECT * FROM carreras WHERE codigo_carrera='$codigo_carrera'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarCarrerasOrderByNombre_tabla_filtro($busqueda_codigo_carrera, $busqueda_nombre_carrera)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_carrera)) {
            $condicion .= " AND codigo_carrera LIKE '%$busqueda_codigo_carrera%'";
        }
        if (!empty($busqueda_nombre_carrera)) {
            $condicion .= " AND nombre_carrera LIKE '%$busqueda_nombre_carrera%'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM carreras WHERE $condicion ORDER BY nombre_carrera");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarCarrerasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_carrera, $busqueda_nombre_carrera)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_carrera)) {
            $condicion .= " AND codigo_carrera LIKE '%$busqueda_codigo_carrera%'";
        }
        if (!empty($busqueda_nombre_carrera)) {
            $condicion .= " AND nombre_carrera LIKE '%$busqueda_nombre_carrera%'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM carreras WHERE $condicion ORDER BY nombre_carrera LIMIT $iniciar, $cantidad_mostrar");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
}
?>
