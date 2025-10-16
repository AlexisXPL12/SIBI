<?php
// ===============================================
// CONTROLADOR: TokenController.php
// ===============================================

require_once "../model/admin-clientApiModel.php";
require_once "../model/admin-sesionModel.php";
require_once "../model/admin-carreraModel.php";
require_once "../model/admin-movimientoModel.php";
require_once "../model/admin-ambienteModel.php";
require_once "../model/admin-bienModel.php";
require_once "../model/admin-categoriaModel.php";
require_once "../model/admin-usuarioModel.php";
require_once "../model/adminModel.php";
require_once "../model/admin-tokensModel.php";

$tipo = $_GET['tipo'];

// ===============================================
// INSTANCIAR MODELO
// ===============================================
$objSesion = new SessionModel();
$objCarrera = new CarreraModel();
$objMovimiento = new MovimientoModel();
$objAmbiente = new AmbienteModel();
$objBien = new BienModel();
$objCategoria = new CategoriaModel();
$objAdmin = new AdminModel();
$objUsuario = new UsuarioModel();
$model = new ClientApiModel();
$objToken = new TokenModel();

//variables de sesion
$id_sesion = $_REQUEST['sesion'];
$token = $_REQUEST['token'];

// ===============================================
// CONTROLADOR TOKEN API
// ===============================================
if ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        //print_r($_POST);
        //repuesta
        if ($_POST) {
            $cliente = $_POST['id_client_api'];

            if ($cliente == "") {
                //repuesta
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos vacíos');
            } else {
                $arr_Cliente = $model->buscarClienteById($cliente);
                if ($arr_Cliente) {
                    $token_generado = bin2hex(random_bytes(16));
                    //date("Y-m-d H:i:s");
                    $fecha_registro = date("Ymd");
                    $token_final = $token_generado . "-" . $fecha_registro . "-" . $cliente;

                    $id_token = $objToken->registrarToken($cliente, $token_final);
                    if ($id_token > 0) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Token Generado Correctamente', 'token' => $token_generado);
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al generar token');
                    }
                }
            }
        }
    } 
    echo json_encode($arr_Respuesta);   

} elseif ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    $id = $_POST['data'];
    $id_client_api = $_POST['id_client_api'];
    $token = $_POST['token'];
    $estado = $_POST['estado'];

    $respuesta = $objToken->actualizarToken($id, $id_client_api, $token, $estado);

    if ($respuesta) {
        $arr_Respuesta = array('status' => true, 'msg' => 'Token actualizado correctamente.');
    } else {
        $arr_Respuesta = array('status' => false, 'msg' => 'Error al actualizar el token.');
    }

    echo json_encode($arr_Respuesta);

} elseif ($tipo == "listar_tokens_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    $pagina = $_POST['pagina'];
    $cantidad_mostrar = $_POST['cantidad_mostrar'];
    $busqueda_tabla_token = $_POST['busqueda_tabla_token']; // corregido, antes no estaba definido
    $busqueda_tabla_cliente = $_POST['busqueda_tabla_cliente'];
    $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'];

    $datos = $objToken->buscarTokensConFiltros($busqueda_tabla_token, $busqueda_tabla_cliente, $busqueda_tabla_estado);
    $total = $objToken->contarTokensConFiltros($busqueda_tabla_token, $busqueda_tabla_cliente, $busqueda_tabla_estado);

    $arrContenido = array();
    foreach ($datos as $item) {
        $item->options = '<button class="btn btn-info btn-sm" data-toggle="modal" data-target=".modal_editar' . $item->id . '">Editar</button>';
        array_push($arrContenido, $item);
    }

    $arr_Respuesta = array('status' => true, 'msg' => '', 'contenido' => $arrContenido, 'total' => $total);
    echo json_encode($arr_Respuesta);

} else {

    $arr_Respuesta = array('status' => false, 'msg' => 'Tipo de operación no válida o no especificada.');
    echo json_encode($arr_Respuesta);
}
?>