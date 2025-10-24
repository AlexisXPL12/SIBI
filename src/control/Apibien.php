<?php
require_once "../model/admin-apiBienModel.php";
require_once "../model/admin-bienModel.php";
require_once "../model/admin-clientApiModel.php";
require_once "../model/admin-sesionModel.php";

$tipo = $_GET['tipo'] ?? '';
$objSesion = new SessionModel();
$objBien = new BienModel();
$objApi = new ApiBienModel();

// Obtener el token de autorización de la cabecera
$headers = apache_request_headers();
$token = $headers['Authorization'] ?? '';

// Verificar el token
if ($tipo == "verificarToken") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Token no válido o no proporcionado.');

    if (empty($token)) {
        echo json_encode($arr_Respuesta);
        exit;
    }

    $token_arr = explode("-", $token);
    if (count($token_arr) < 3) {
        $arr_Respuesta['msg'] = 'Token inválido.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $id_cliente = $token_arr[2];
    $arr_Cliente = $objApi->buscarClienteById($id_cliente);

    if (!$arr_Cliente || $arr_Cliente->estado != 1) {
        $arr_Respuesta['msg'] = 'Error, cliente no activo o no encontrado.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $arr_Respuesta = array('status' => true, 'msg' => 'Token verificado correctamente.');
    echo json_encode($arr_Respuesta);
    exit;
}

// Buscar bienes con filtros
elseif ($tipo == "buscar_bienes") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Token no válido o no proporcionado.');

    if (empty($token)) {
        echo json_encode($arr_Respuesta);
        exit;
    }

    $token_arr = explode("-", $token);
    if (count($token_arr) < 3) {
        $arr_Respuesta['msg'] = 'Token inválido.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $id_cliente = $token_arr[2];
    $arr_Cliente = $objApi->buscarClienteById($id_cliente);

    if (!$arr_Cliente || $arr_Cliente->estado != 1) {
        $arr_Respuesta['msg'] = 'Error, cliente no activo o no encontrado.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $prefijo = $_GET['prefijo'] ?? '';
    $numero = $_GET['numero'] ?? '';
    $anio = $_GET['anio'] ?? '';
    $nombre = $_GET['nombre'] ?? '';

    if (!empty($prefijo) && !empty($numero) && !empty($anio)) {
        // Si se proporcionan prefijo, número y año, buscamos coincidencias exactas
        $codigoCompleto = "$prefijo-" . str_pad($numero, 3, '0', STR_PAD_LEFT) . "-$anio";
        $bienes = $objBien->buscarBienPorCodigoExacto($codigoCompleto);
    } else {
        // Búsqueda avanzada por partes
        $bienes = $objBien->buscarBienesPorCodigoAvanzado($prefijo, $numero, $anio, $nombre);
    }

    $arr_Respuesta = array('status' => true, 'msg' => 'Búsqueda exitosa.', 'contenido' => $bienes);
    echo json_encode($arr_Respuesta);
    exit;
}

?>




