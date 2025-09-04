<?php
require_once("../model/admin-usuarioModel.php");
require_once("../model/admin-sesionModel.php");
require_once("../model/adminModel.php");

$objUsuario = new UsuarioModel();
$objSesion = new SessionModel();
$objAdmin = new AdminModel();

$tipo = $_GET['tipo'];

if ($tipo == "iniciar_sesion") {
    $usuario = trim($_POST['dni']);
    $password = trim($_POST['password']);
    $arrResponse = array('status' => false, 'msg' => '');

    $arrPersona = $objUsuario->buscarUsuarioByDni($usuario);
    
    if (empty($arrPersona)) {
        $arrResponse = array('status' => false, 'msg' => 'Error, Usuario no está registrado en el sistema');
    } else {
        if (password_verify($password, $arrPersona->password)) {
            $arr_contenido = [];
            // datos de sesión
            $fecha_hora_inicio = date("Y-m-d H:i:s");
            $fecha_hora_fin = strtotime('+8 hour', strtotime($fecha_hora_inicio)); // Cambiado a 8 horas
            $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);

            $llave = $objAdmin->generar_llave(30);
            $token = password_hash($llave, PASSWORD_DEFAULT);
            $id_usuario = $arrPersona->id;

            $arrSesion = $objSesion->registrarSesion($id_usuario, $fecha_hora_inicio, $fecha_hora_fin, $llave);
            
            $arrResponse = array('status' => true, 'msg' => 'Bienvenido al sistema');

            $arr_contenido['sesion_id'] = $arrSesion;
            $arr_contenido['sesion_usuario'] = $id_usuario;
            $arr_contenido['sesion_usuario_nom'] = $arrPersona->nombres_apellidos;
            $arr_contenido['sesion_token'] = $token;
            // Removido sesion_ies ya que no usas instituciones
            $arrResponse['contenido'] = $arr_contenido;
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Error, Usuario y/o Contraseña Incorrecta');
        }
    }
    echo json_encode($arrResponse);
}

die;