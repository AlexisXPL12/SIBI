<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

require_once('../model/admin-apiModel.php');
require_once('../model/admin-sesionModel.php');
require_once('../model/admin-usuarioModel.php');
require_once('../model/adminModel.php');

$tipo = $_GET['tipo'] ?? '';

// Instanciar clases
$objApi = new ApiModel();
$objSesion = new SessionModel();
$objUsuario = new UsuarioModel();
$objAdmin = new AdminModel();

// Consultas de API
if ($tipo == "verBienApiByNombre") {
    // Validar que se envió el token
    if (!isset($_POST['token']) || empty($_POST['token'])) {
        $arr_Respuesta = array(
            'status' => false, 
            'msg' => 'Token no proporcionado. Por favor, incluya un token válido.'
        );
        echo json_encode($arr_Respuesta);
        exit;
    }

    $token = $_POST['token'];
    
    // Validar formato del token
    $token_arr = explode("-", $token);
    
    if (count($token_arr) < 3) {
        $arr_Respuesta = array(
            'status' => false, 
            'msg' => 'Formato de token inválido. El token debe tener el formato correcto.'
        );
        echo json_encode($arr_Respuesta);
        exit;
    }

    $id_cliente = $token_arr[2];
    
    // Verificar que el cliente existe
    $arr_Cliente = $objApi->buscarClienteById($id_cliente);
    
    if (!$arr_Cliente) {
        $arr_Respuesta = array(
            'status' => false, 
            'msg' => 'Token no válido. El cliente asociado no existe en el sistema.'
        );
        echo json_encode($arr_Respuesta);
        exit;
    }
    
    // Verificar que el cliente está activo
    if ($arr_Cliente->estado != 1) {
        $arr_Respuesta = array(
            'status' => false, 
            'msg' => 'Cliente inactivo. El acceso a la API ha sido deshabilitado para este cliente.'
        );
        echo json_encode($arr_Respuesta);
        exit;
    }
    
    // Validar que se envió el parámetro 'data'
    if (!isset($_POST['data']) || empty($_POST['data'])) {
        $arr_Respuesta = array(
            'status' => false, 
            'msg' => 'Parámetro de búsqueda no proporcionado. Por favor, incluya el término de búsqueda.'
        );
        echo json_encode($arr_Respuesta);
        exit;
    }
    
    $data = $_POST['data'];
    
    // Buscar bienes
    $arr_bienes = $objApi->buscarBienByDenominacion($data);
    
    if (empty($arr_bienes)) {
        $arr_Respuesta = array(
            'status' => true, 
            'msg' => 'No se encontraron resultados para la búsqueda.',
            'contenido' => []
        );
    } else {
        $arr_Respuesta = array(
            'status' => true, 
            'msg' => 'Búsqueda exitosa.',
            'contenido' => $arr_bienes
        );
    }
    
    echo json_encode($arr_Respuesta);
    exit;
}

// Si no es un tipo válido
$arr_Respuesta = array(
    'status' => false, 
    'msg' => 'Tipo de operación no válido.'
);
echo json_encode($arr_Respuesta);