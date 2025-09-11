<?php
require_once "../library/conexion.php";

class BienModel
{

    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function obtenerBienes()
    {
        $query = "SELECT b.*, ai.detalle as ambiente_detalle FROM bienes b JOIN ambientes_institucion ai ON b.id_ambiente = ai.id";
        $result = $this->conexion->query($query);

        $bienes = [];
        while ($row = $result->fetch_assoc()) {
            $bienes[] = $row;
        }

        return $bienes;
    }
    public function registrarBien($codigo_patrimonial, $nombre_bien, $descripcion, $marca, $modelo, $serie, $color, $dimensiones, $id_categoria, $id_dependencia, $ubicacion_especifica, $fecha_adquisicion, $fecha_ingreso, $numero_factura, $numero_orden_compra, $estado_bien, $condicion_bien, $observaciones, $es_inventariable, $usuario_registro)
    {
        $sql = $this->conexion->query("INSERT INTO bienes (codigo_patrimonial, nombre_bien, descripcion, marca, modelo, serie, color, dimensiones, id_categoria, id_dependencia, ubicacion_especifica, fecha_adquisicion, fecha_ingreso, numero_factura, numero_orden_compra, estado_bien, condicion_bien, observaciones, es_inventariable, usuario_registro)
        VALUES ('$codigo_patrimonial', '$nombre_bien', '$descripcion', '$marca', '$modelo', '$serie', '$color', '$dimensiones', $id_categoria, $id_dependencia, '$ubicacion_especifica', '$fecha_adquisicion', '$fecha_ingreso', '$numero_factura', '$numero_orden_compra', '$estado_bien', '$condicion_bien', '$observaciones', $es_inventariable, '$usuario_registro')");

        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarBien($id_bien, $codigo_patrimonial, $nombre_bien, $descripcion, $marca, $modelo, $serie, $color, $dimensiones, $id_categoria, $id_dependencia, $ubicacion_especifica, $fecha_adquisicion, $fecha_ingreso, $numero_factura, $numero_orden_compra, $estado_bien, $condicion_bien, $observaciones, $es_inventariable, $usuario_registro)
    {
        $sql = $this->conexion->query("UPDATE bienes SET
            codigo_patrimonial='$codigo_patrimonial',
            nombre_bien='$nombre_bien',
            descripcion='$descripcion',
            marca='$marca',
            modelo='$modelo',
            serie='$serie',
            color='$color',
            dimensiones='$dimensiones',
            id_categoria=$id_categoria,
            id_dependencia=$id_dependencia,
            ubicacion_especifica='$ubicacion_especifica',
            fecha_adquisicion='$fecha_adquisicion',
            fecha_ingreso='$fecha_ingreso',
            numero_factura='$numero_factura',
            numero_orden_compra='$numero_orden_compra',
            estado_bien='$estado_bien',
            condicion_bien='$condicion_bien',
            observaciones='$observaciones',
            es_inventariable=$es_inventariable,
            usuario_registro='$usuario_registro'
            WHERE id_bien=$id_bien");

        return $sql;
    }

    public function buscarBienById($id_bien)
    {
        $sql = $this->conexion->query("SELECT * FROM bienes WHERE id_bien=$id_bien");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarBienByCodigoPatrimonial($codigo_patrimonial)
    {
        $sql = $this->conexion->query("SELECT * FROM bienes WHERE codigo_patrimonial='$codigo_patrimonial'");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarBienesOrderByNombre_tabla_filtro($busqueda_codigo_patrimonial, $busqueda_nombre_bien)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_patrimonial)) {
            $condicion .= " AND codigo_patrimonial LIKE '%$busqueda_codigo_patrimonial%'";
        }
        if (!empty($busqueda_nombre_bien)) {
            $condicion .= " AND nombre_bien LIKE '%$busqueda_nombre_bien%'";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM bienes WHERE $condicion ORDER BY nombre_bien");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarBienesOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_patrimonial, $busqueda_nombre_bien)
    {
        $condicion = "1=1";
        if (!empty($busqueda_codigo_patrimonial)) {
            $condicion .= " AND codigo_patrimonial LIKE '%$busqueda_codigo_patrimonial%'";
        }
        if (!empty($busqueda_nombre_bien)) {
            $condicion .= " AND nombre_bien LIKE '%$busqueda_nombre_bien%'";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("SELECT * FROM bienes WHERE $condicion ORDER BY nombre_bien LIMIT $iniciar, $cantidad_mostrar");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
    public function listarTodosLosBienes()
{
    $arrRespuesta = array();
    $query = "
        SELECT 
            b.id AS bien_id,
            b.cod_patrimonial,
            b.denominacion,
            b.marca,
            b.modelo,
            b.tipo,
            b.color,
            b.serie,
            b.dimensiones,
            b.valor,
            b.situacion,
            b.estado_conservacion,
            b.observaciones,
            b.fecha_registro,
            b.estado AS estado_bien,
            
            ai.id AS ambiente_id,
            ai.codigo AS ambiente_codigo,
            ai.detalle AS ambiente_detalle,
            ai.otros_detalle,
            ai.encargado AS ambiente_encargado,
            
            -- AGREGAR INFORMACIÓN DE LA INSTITUCIÓN
            i.id AS institucion_id,
            i.nombre AS institucion_nombre,
            i.cod_modular AS institucion_cod_modular,
            i.ruc AS institucion_ruc,
            
            u.id AS usuario_id,
            u.nombres_apellidos AS nombre_usuario,
            u.dni AS usuario_dni 
        FROM bienes b 
        LEFT JOIN ambientes_institucion ai ON b.id_ambiente = ai.id 
        LEFT JOIN institucion i ON ai.id_ies = i.id  -- JOIN CON INSTITUCIÓN
        LEFT JOIN usuarios u ON b.usuario_registro = u.id 
        WHERE b.estado = 1  -- Solo bienes activos
        ORDER BY b.fecha_registro ASC;
    ";
    
    $respuesta = $this->conexion->query($query);
    while ($objeto = $respuesta->fetch_object()) {
        array_push($arrRespuesta, $objeto);
    }
    return $arrRespuesta;
}
}
