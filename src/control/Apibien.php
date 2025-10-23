<?php
require_once "../model/admin-apiBienModel.php";
require_once "../model/admin-bienModel.php";
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
$objApi = new ApiBienModel();

// Obtener el token de autorización de la cabecera
$headers = apache_request_headers();
$token = $headers['Authorization'] ?? '';

if ($tipo == "verBienApiByNombre") {
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
    $arr_Cliente = $objCliente->buscarClienteById($id_cliente);

    if (!$arr_Cliente || $arr_Cliente->estado != 1) {
        $arr_Respuesta['msg'] = 'Error, cliente no activo o no encontrado.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $data = $_POST['data'] ?? '';
    if (empty($data)) {
        $arr_Respuesta['msg'] = 'Debe proporcionar un nombre de bien para buscar.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    $arr_bienes = $objBien->buscarBienByDenominacion($data);
    $arr_Respuesta = array('status' => true, 'msg' => 'Búsqueda exitosa.', 'contenido' => $arr_bienes);
    echo json_encode($arr_Respuesta);
}
?>
