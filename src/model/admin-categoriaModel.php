<?php
require_once "../library/conexion.php";

class CategoriaModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }
    public function listarCategorias()
{
    $arrRespuesta = array();
    $respuesta = $this->conexion->query("SELECT id_categoria, nombre_categoria FROM categorias WHERE estado = 'ACTIVO' ORDER BY nombre_categoria");
    if ($respuesta === false) {
        throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
    }
    while ($objeto = $respuesta->fetch_object()) {
        array_push($arrRespuesta, $objeto);
    }
    return $arrRespuesta;
}
public function listarCategoriasActivas() {
    $query = "SELECT id_categoria, nombre_categoria FROM categorias WHERE estado = 'ACTIVO' ORDER BY nombre_categoria";
    $respuesta = $this->conexion->query($query);
    $arrRespuesta = array();
    while ($objeto = $respuesta->fetch_object()) {
        array_push($arrRespuesta, $objeto);
    }
    return $arrRespuesta;
}

    public function registrarCategoria($codigo_categoria, $nombre_categoria, $descripcion, $vida_util_anos, $estado)
    {
        $sql = $this->conexion->query("INSERT INTO categorias (codigo_categoria, nombre_categoria, descripcion, vida_util_anos, estado)
        VALUES ('$codigo_categoria', '$nombre_categoria', '$descripcion', $vida_util_anos, '$estado')");

        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarCategoria($id_categoria, $codigo_categoria, $nombre_categoria, $descripcion, $vida_util_anos, $estado)
    {
        $sql = $this->conexion->query("UPDATE categorias SET
            codigo_categoria='$codigo_categoria',
            nombre_categoria='$nombre_categoria',
            descripcion='$descripcion',
            vida_util_anos=$vida_util_anos,
            estado='$estado'
            WHERE id_categoria=$id_categoria");

        return $sql;
    }

    public function buscarCategoriaById($id_categoria)
    {
        $sql = $this->conexion->query("SELECT * FROM categorias WHERE id_categoria=$id_categoria");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarCategoriaByCodigo($codigo_categoria)
    {
        $sql = $this->conexion->query("SELECT * FROM categorias WHERE codigo_categoria='$codigo_categoria'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarCategoriasOrderByNombre_tabla_filtro($busqueda_codigo_categoria, $busqueda_nombre_categoria)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_categoria)) {
            $condicion .= " AND codigo_categoria LIKE '%$busqueda_codigo_categoria%'";
        }
        if (!empty($busqueda_nombre_categoria)) {
            $condicion .= " AND nombre_categoria LIKE '%$busqueda_nombre_categoria%'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM categorias WHERE $condicion ORDER BY nombre_categoria");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarCategoriasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_categoria, $busqueda_nombre_categoria)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_categoria)) {
            $condicion .= " AND codigo_categoria LIKE '%$busqueda_codigo_categoria%'";
        }
        if (!empty($busqueda_nombre_categoria)) {
            $condicion .= " AND nombre_categoria LIKE '%$busqueda_nombre_categoria%'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM categorias WHERE $condicion ORDER BY nombre_categoria LIMIT $iniciar, $cantidad_mostrar");
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
