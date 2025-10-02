<?php
require_once "../model/admin-clientApiModel.php";

$tipo = $_GET['tipo'];
$model = new ClientApiModel();

switch ($tipo) {
    case 'listar_clientes_select':
    $datos = $model->listarClientesSelect();
    echo json_encode([
        'status' => true,
        'contenido' => $datos
    ]);
    break;

    case 'registrar':
        $ruc = $_POST['ruc'];
        $razon_social = $_POST['razon_social'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];
        $fecha_registro = date('Y-m-d H:i:s');

        $respuesta = $model->registrarCliente($ruc, $razon_social, $telefono, $correo, $fecha_registro, $estado);
        if ($respuesta > 0) {
            echo json_encode(['status' => true, 'mensaje' => 'Cliente API registrado correctamente.']);
        } else {
            echo json_encode(['status' => false, 'mensaje' => 'Error al registrar el cliente API.']);
        }
        break;

    case 'actualizar':
        $id = $_POST['data'];
        $ruc = $_POST['ruc'];
        $razon_social = $_POST['razon_social'];
        $telefono = $_POST['telefono'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];

        $respuesta = $model->actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado);
        if ($respuesta) {
            echo json_encode(['status' => true, 'mensaje' => 'Cliente API actualizado correctamente.']);
        } else {
            echo json_encode(['status' => false, 'mensaje' => 'Error al actualizar el cliente API.']);
        }
        break;

    case 'listar_clientes_api_ordenados_tabla':
        $pagina = $_POST['pagina'];
        $cantidad_mostrar = $_POST['cantidad_mostrar'];
        $busqueda_tabla_ruc = $_POST['busqueda_tabla_ruc'];
        $busqueda_tabla_razon_social = $_POST['busqueda_tabla_razon_social'];
        $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'];

        $datos = $model->buscarClientesConFiltros($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado);
        $total = $model->contarClientesConFiltros($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado);

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
?>
