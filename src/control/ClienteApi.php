<?php
session_start();
// ===============================================
// CONTROLADOR: ClienteApliController.php
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

// ===============================================
// INSTANCIAS DE MODELOS
// ===============================================
$objSesion     = new SessionModel();
$objCarrera    = new CarreraModel();
$objMovimiento = new MovimientoModel();
$objAmbiente   = new AmbienteModel();
$objBien       = new BienModel();
$objCategoria  = new CategoriaModel();
$objAdmin      = new AdminModel();
$objUsuario    = new UsuarioModel();
$model         = new ClientApiModel();

// ===============================================
// CONTROLADOR CLIENTE API
// ===============================================
$tipo = $_GET['tipo'];

if ($tipo == "listar_clientes_select") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    $datos = $model->listarClientesSelect();
    $arr_Respuesta = array('status' => true, 'msg' => '', 'contenido' => $datos);
    echo json_encode($arr_Respuesta);

} 
elseif ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    $ruc = $_POST['ruc'];
    $razon_social = $_POST['razon_social'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    $fecha_registro = date('Y-m-d H:i:s');

    $respuesta = $model->registrarCliente($ruc, $razon_social, $telefono, $correo, $fecha_registro, $estado);

    if ($respuesta > 0) {
        $arr_Respuesta = array('status' => true, 'msg' => 'Cliente API registrado correctamente.');
    } else {
        $arr_Respuesta = array('status' => false, 'msg' => 'Error al registrar el cliente API.');
    }

    echo json_encode($arr_Respuesta);

} 
elseif ($tipo == "listar_clientes_api_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    $pagina = $_POST['pagina'];
    $cantidad_mostrar = $_POST['cantidad_mostrar'];
    $busqueda_tabla_ruc = $_POST['busqueda_tabla_ruc'];
    $busqueda_tabla_razon_social = $_POST['busqueda_tabla_razon_social'];
    $busqueda_tabla_estado = $_POST['busqueda_tabla_estado'];

    $datos = $model->buscarClientesConFiltros($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado);
    $total = $model->contarClientesConFiltros($busqueda_tabla_ruc, $busqueda_tabla_razon_social, $busqueda_tabla_estado);

    $arrContenido = array();
    foreach ($datos as $item) {
        $item->options = '<button class="btn btn-info btn-sm" onclick="abrirModalEditarClienteApi(\'' . $item->id . '\', \'' . $item->ruc . '\', \'' . addslashes($item->razon_social) . '\', \'' . $item->telefono . '\', \'' . addslashes($item->correo) . '\', \'' . $item->estado . '\')">Editar</button>';
        array_push($arrContenido, $item);
    }

    $arr_Respuesta = array('status' => true, 'msg' => '', 'contenido' => $arrContenido, 'total' => $total);
    echo json_encode($arr_Respuesta);
}
elseif ($tipo == "verBienApiByNombre") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    // Validación del token (ejemplo similar a tu lógica original)
    $token = $_POST['token']; // o $_GET, según cómo lo envíes
    $token_arr = explode("-", $token);
    $id_cliente = $token_arr[2];

    $arr_Cliente = $model->buscarClienteById($id_cliente);

    if ($arr_Cliente->estado) {
        $data = $_POST['data'];
        $arr_bienes = $model->buscarBienByDenominacion($data);
        $arr_Respuesta = array('status' => true, 'msg' => '', 'contenido' => $arr_bienes);
    } else {
        $arr_Respuesta = array('status' => false, 'msg' => 'Error, cliente no activo.');
    }

    echo json_encode($arr_Respuesta);

}
elseif ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
        if ($_POST) {
            $id = $_POST['data'];
            $ruc = $_POST['ruc'];
            $razon_social = $_POST['razon_social'];
            $telefono = $_POST['telefono'];
            $correo = $_POST['correo'];
            $estado = $_POST['estado'];

            if ($id == "" || $ruc == "" || $razon_social == "" || $telefono == "" || $correo == "" || $estado == "") {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos vacíos');
            } else {
                // Verificar si el RUC ya existe en otro registro
                $arr_Cliente = $model->buscarClienteByRuc($ruc);
                
                if ($arr_Cliente) {
                    // Si existe, verificar que sea el mismo cliente que estamos editando
                    if ($arr_Cliente->id == $id) {
                        // Es el mismo cliente, proceder con la actualización
                        $consulta = $model->actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Cliente API actualizado correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar el cliente API');
                        }
                    } else {
                        // El RUC pertenece a otro cliente
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El RUC ya está registrado en otro cliente');
                    }
                } else {
                    // El RUC no existe, proceder con la actualización
                    $consulta = $model->actualizarCliente($id, $ruc, $razon_social, $telefono, $correo, $estado);
                    if ($consulta) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Cliente API actualizado correctamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar el cliente API');
                    }
                }
            }
        }
    echo json_encode($arr_Respuesta);
}
else {

    $arr_Respuesta = array('status' => false, 'msg' => 'Tipo de operación no válida o no especificada.');
    echo json_encode($arr_Respuesta);
}
?>

