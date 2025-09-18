<?php
require_once "../library/conexion.php";

class MovimientoModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrarMovimiento($id_bien, $tipo_movimiento, $id_dependencia_origen, $id_dependencia_destino, $motivo, $observaciones, $documento_referencia, $usuario_solicita)
    {
        $sql = $this->conexion->query("INSERT INTO movimientos (id_bien, tipo_movimiento, id_dependencia_origen, id_dependencia_destino, motivo, observaciones, documento_referencia, usuario_solicita)
        VALUES ($id_bien, '$tipo_movimiento', $id_dependencia_origen, $id_dependencia_destino, '$motivo', '$observaciones', '$documento_referencia', '$usuario_solicita')");

        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }

    public function actualizarMovimiento($id_movimiento, $id_bien, $tipo_movimiento, $id_dependencia_origen, $id_dependencia_destino, $motivo, $observaciones, $documento_referencia, $usuario_solicita)
    {
        $sql = $this->conexion->query("UPDATE movimientos SET
            id_bien=$id_bien,
            tipo_movimiento='$tipo_movimiento',
            id_dependencia_origen=$id_dependencia_origen,
            id_dependencia_destino=$id_dependencia_destino,
            motivo='$motivo',
            observaciones='$observaciones',
            documento_referencia='$documento_referencia',
            usuario_solicita='$usuario_solicita'
            WHERE id_movimiento=$id_movimiento");

        return $sql;
    }

    public function buscarMovimientoById($id_movimiento)
    {
        $sql = $this->conexion->query("SELECT * FROM movimientos WHERE id_movimiento=$id_movimiento");
        $sql = $sql->fetch_object();
        return $sql;
    }

    public function buscarMovimientosOrderByFecha_tabla_filtro($busqueda_tipo_movimiento, $busqueda_estado_movimiento, $busqueda_bien, $busqueda_dependencia)
    {
        $condicion = "1=1";
        if (!empty($busqueda_tipo_movimiento)) {
            $condicion .= " AND m.tipo_movimiento LIKE '%$busqueda_tipo_movimiento%'";
        }
        if (!empty($busqueda_estado_movimiento)) {
            $condicion .= " AND m.estado_movimiento LIKE '%$busqueda_estado_movimiento%'";
        }
        if (!empty($busqueda_bien)) {
            $condicion .= " AND m.id_bien = $busqueda_bien";
        }
        if (!empty($busqueda_dependencia)) {
            $condicion .= " AND (m.id_dependencia_origen = $busqueda_dependencia OR m.id_dependencia_destino = $busqueda_dependencia)";
        }

        $arrRespuesta = array();
        $respuesta = $this->conexion->query("
            SELECT m.*, b.nombre_bien, b.codigo_patrimonial,
                   do.nombre_dependencia as dependencia_origen,
                   dd.nombre_dependencia as dependencia_destino
            FROM movimientos m
            LEFT JOIN bienes b ON m.id_bien = b.id_bien
            LEFT JOIN dependencias do ON m.id_dependencia_origen = do.id_dependencia
            LEFT JOIN dependencias dd ON m.id_dependencia_destino = dd.id_dependencia
            WHERE $condicion
            ORDER BY m.fecha_solicitud DESC
        ");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function buscarMovimientosOrderByFecha_tabla($pagina, $cantidad_mostrar, $busqueda_tipo_movimiento, $busqueda_estado_movimiento, $busqueda_bien, $busqueda_dependencia)
    {
        $condicion = "1=1";
        if (!empty($busqueda_tipo_movimiento)) {
            $condicion .= " AND m.tipo_movimiento LIKE '%$busqueda_tipo_movimiento%'";
        }
        if (!empty($busqueda_estado_movimiento)) {
            $condicion .= " AND m.estado_movimiento LIKE '%$busqueda_estado_movimiento%'";
        }
        if (!empty($busqueda_bien)) {
            $condicion .= " AND m.id_bien = $busqueda_bien";
        }
        if (!empty($busqueda_dependencia)) {
            $condicion .= " AND (m.id_dependencia_origen = $busqueda_dependencia OR m.id_dependencia_destino = $busqueda_dependencia)";
        }

        $iniciar = ($pagina - 1) * $cantidad_mostrar;
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("
            SELECT m.*, b.nombre_bien, b.codigo_patrimonial,
                   do.nombre_dependencia as dependencia_origen,
                   dd.nombre_dependencia as dependencia_destino
            FROM movimientos m
            LEFT JOIN bienes b ON m.id_bien = b.id_bien
            LEFT JOIN dependencias do ON m.id_dependencia_origen = do.id_dependencia
            LEFT JOIN dependencias dd ON m.id_dependencia_destino = dd.id_dependencia
            WHERE $condicion
            ORDER BY m.fecha_solicitud DESC
            LIMIT $iniciar, $cantidad_mostrar
        ");
        if ($respuesta === false) {
            throw new Exception("Error en la consulta SQL: " . $this->conexion->error);
        }
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function ejecutarMovimiento($id_movimiento, $usuario_autoriza)
    {
        $this->conexion->begin_transaction();

        try {
            // Obtener información del movimiento
            $movimiento = $this->buscarMovimientoById($id_movimiento);
            if (!$movimiento) {
                throw new Exception("Movimiento no encontrado");
            }

            // Actualizar el movimiento
            $sql = $this->conexion->query("UPDATE movimientos SET
                estado_movimiento='EJECUTADO',
                usuario_autoriza='$usuario_autoriza',
                fecha_ejecucion=NOW()
                WHERE id_movimiento=$id_movimiento");

            if (!$sql) {
                throw new Exception("Error al actualizar el movimiento");
            }

            // Actualizar el bien según el tipo de movimiento
            if ($movimiento->tipo_movimiento == 'TRASLADO') {
                $sql = $this->conexion->query("UPDATE bienes SET
                    id_dependencia = {$movimiento->id_dependencia_destino}
                    WHERE id_bien = {$movimiento->id_bien}");
                if (!$sql) {
                    throw new Exception("Error al actualizar el bien");
                }
            } elseif ($movimiento->tipo_movimiento == 'BAJA') {
                $sql = $this->conexion->query("UPDATE bienes SET
                    estado_bien = 'BAJA'
                    WHERE id_bien = {$movimiento->id_bien}");
                if (!$sql) {
                    throw new Exception("Error al actualizar el bien");
                }
            } elseif ($movimiento->tipo_movimiento == 'PRESTAMO') {
                $sql = $this->conexion->query("UPDATE bienes SET
                    estado_bien = 'PRESTADO'
                    WHERE id_bien = {$movimiento->id_bien}");
                if (!$sql) {
                    throw new Exception("Error al actualizar el bien");
                }
            } elseif ($movimiento->tipo_movimiento == 'DEVOLUCION') {
                $sql = $this->conexion->query("UPDATE bienes SET
                    estado_bien = 'ACTIVO'
                    WHERE id_bien = {$movimiento->id_bien}");
                if (!$sql) {
                    throw new Exception("Error al actualizar el bien");
                }
            }

            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollback();
            throw $e;
        }
    }

    public function cancelarMovimiento($id_movimiento, $usuario_autoriza)
    {
        $sql = $this->conexion->query("UPDATE movimientos SET
            estado_movimiento='CANCELADO',
            usuario_autoriza='$usuario_autoriza'
            WHERE id_movimiento=$id_movimiento");

        return $sql;
    }

    public function obtenerBienesDisponibles()
    {
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("
            SELECT id_bien, codigo_patrimonial, nombre_bien
            FROM bienes
            WHERE estado_bien = 'ACTIVO'
            ORDER BY nombre_bien
        ");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }

    public function obtenerDependencias()
    {
        $arrRespuesta = array();
        $respuesta = $this->conexion->query("
            SELECT id_dependencia, codigo_dependencia, nombre_dependencia
            FROM dependencias
            WHERE estado = 'ACTIVO'
            ORDER BY nombre_dependencia
        ");
        while ($objeto = $respuesta->fetch_object()) {
            array_push($arrRespuesta, $objeto);
        }
        return $arrRespuesta;
    }
}
?>
