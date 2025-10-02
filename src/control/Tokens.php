<?php
require_once "../model/admin-tokensModel.php";
$tipo = $_GET['tipo'];
$model = new TokenModel();

switch ($tipo) {
    case 'registrar':
        $id_client_api = $_POST['id_client_api'];
        $fecha_registro = date('Y-m-d H:i:s');
        $estado = $_POST['estado'];
        $respuesta = $model->registrarToken($id_client_api, $fecha_registro, $estado);
        if ($respuesta > 0) {
            echo json_encode(['status' => true, 'mensaje' => 'Token registrado correctamente.']);
        } else {
            echo json_encode(['status' => false, 'mensaje' => 'Error al registrar el token.']);
        }
        break;


    case 'actualizar':
        $id = $_POST['data'];
        $id_client_api = $_POST['id_client_api'];
        $token = $_POST['token'];
        $estado = $_POST['estado'];
        $respuesta = $model->actualizarToken($id, $id_client_api, $token, $estado);
        if ($respuesta) {
            echo json_encode(['status' => true, 'mensaje' => 'Token actualizado correctamente.']);
        } else {
            echo json_encode(['status' => false, 'mensaje' => 'Error al actualizar el token.']);
        }
        break;

    case 'listar_tokens_ordenados_tabla':
        $pagina = $_POST['pagina'];
        $cantidad_mostrar = $_POST['cantidad_mostrar'];
        $busqueda_tabla_token = $_POST['busqueda_tabla_token'];
        $busqueda_tabla_cliente = $_POST['busqueda_tabla_cliente'];
        $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'];
        $datos = $model->buscarTokensConFiltros($busqueda_tabla_token, $busqueda_tabla_cliente, $busqueda_tabla_estado);
        $total = $model->contarTokensConFiltros($busqueda_tabla_token, $busqueda_tabla_cliente, $busqueda_tabla_estado);
        $arrRespuesta = array();
        foreach ($datos as $item) {
            $item->options = '<button class="btn btn-info btn-sm" data-toggle="modal" data-target=".modal_editar' . $item->id . '">Editar</button>';
            array_push($arrRespuesta, $item);
        }
        echo json_encode([
            'status' => true,
            'contenido' => $arrRespuesta,
            'total' => $total
        ]);
        break;
}
