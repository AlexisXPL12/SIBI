<?php
session_start();
require_once('../model/admin-sesionModel.php');
require_once('../model/admin-carreraModel.php');
require_once('../model/admin-movimientoModel.php');
require_once('../model/admin-ambienteModel.php');
require_once('../model/admin-bienModel.php');
require_once('../model/admin-categoriaModel.php');
require_once('../model/admin-usuarioModel.php');
require_once('../model/adminModel.php');
$tipo = $_GET['tipo'];

//instanciar la clase categoria model
$objSesion = new SessionModel();
$objCarrera = new CarreraModel();
$objMovimiento = new MovimientoModel();
$objAmbiente = new AmbienteModel();
$objBien = new BienModel();
$objAdmin = new AdminModel();
$objCategoria = new CategoriaModel();
$objUsuario = new UsuarioModel();

//variables de sesion
$id_sesion = $_REQUEST['sesion'];
$token = $_REQUEST['token'];

if ($tipo == "listar_categorias") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if (empty($id_sesion) || empty($token) || !$objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            throw new Exception("Sesión no válida");
        }

        $arr_Categorias = $objCategoria->listarCategorias();

        $arr_contenido = [];
        if (!empty($arr_Categorias)) {
            foreach ($arr_Categorias as $categoria) {
                $arr_contenido[] = [
                    'id_categoria' => $categoria->id_categoria,
                    'nombre_categoria' => $categoria->nombre_categoria
                ];
            }
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        } else {
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = [];
        }
    } catch (Exception $e) {
        $arr_Respuesta['msg'] = 'Error en el servidor: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}
if ($tipo == "listar_categorias_ordenadas_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            $pagina = $_POST['pagina'] ?? 1;
            $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
            $busqueda_codigo_categoria = $_POST['busqueda_codigo_categoria'] ?? '';
            $busqueda_nombre_categoria = $_POST['busqueda_nombre_categoria'] ?? '';

            $busqueda_filtro = $objCategoria->buscarCategoriasOrderByNombre_tabla_filtro($busqueda_codigo_categoria, $busqueda_nombre_categoria);
            $arr_Categorias = $objCategoria->buscarCategoriasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_categoria, $busqueda_nombre_categoria);

            $arr_contenido = [];
            if (!empty($arr_Categorias)) {
                for ($i = 0; $i < count($arr_Categorias); $i++) {
                    $arr_contenido[$i] = (object) [];
                    $arr_contenido[$i]->id = $arr_Categorias[$i]->id_categoria;
                    $arr_contenido[$i]->codigo_categoria = $arr_Categorias[$i]->codigo_categoria;
                    $arr_contenido[$i]->nombre_categoria = $arr_Categorias[$i]->nombre_categoria;
                    $arr_contenido[$i]->descripcion = $arr_Categorias[$i]->descripcion;
                    $arr_contenido[$i]->vida_util_anos = $arr_Categorias[$i]->vida_util_anos;
                    $arr_contenido[$i]->estado = $arr_Categorias[$i]->estado;
                    $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Categorias[$i]->id_categoria . '"><i class="fa fa-edit"></i></button>';
                    $arr_contenido[$i]->options = $opciones;
                }
                $arr_Respuesta['status'] = true;
                $arr_Respuesta['contenido'] = $arr_contenido;
                $arr_Respuesta['total'] = count($busqueda_filtro);
            } else {
                $arr_Respuesta['status'] = true;
                $arr_Respuesta['contenido'] = [];
                $arr_Respuesta['total'] = 0;
            }
        }
    } catch (Exception $e) {
        $arr_Respuesta['msg'] = 'Error en el servidor: ' . $e->getMessage();
    }

    header('Content-Type: application/json');
    echo json_encode($arr_Respuesta);
    exit;
}

if ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $codigo_categoria = $_POST['codigo_categoria'];
            $nombre_categoria = $_POST['nombre_categoria'];
            $descripcion = $_POST['descripcion'];
            $vida_util_anos = $_POST['vida_util_anos'];
            $estado = $_POST['estado'];

            if (empty($codigo_categoria) || empty($nombre_categoria)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Categoria = $objCategoria->buscarCategoriaByCodigo($codigo_categoria);
                if ($arr_Categoria) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, el código de categoría ya se encuentra registrado');
                } else {
                    $id_categoria = $objCategoria->registrarCategoria($codigo_categoria, $nombre_categoria, $descripcion, $vida_util_anos, $estado);
                    if ($id_categoria > 0) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Registro Exitoso');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar categoría');
                    }
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_categoria = $_POST['data'];
            $codigo_categoria = $_POST['codigo_categoria'];
            $nombre_categoria = $_POST['nombre_categoria'];
            $descripcion = $_POST['descripcion'];
            $vida_util_anos = $_POST['vida_util_anos'];
            $estado = $_POST['estado'];

            if (empty($id_categoria) || empty($codigo_categoria) || empty($nombre_categoria)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Categoria = $objCategoria->buscarCategoriaByCodigo($codigo_categoria);
                if ($arr_Categoria) {
                    if ($arr_Categoria->id_categoria == $id_categoria) {
                        $consulta = $objCategoria->actualizarCategoria($id_categoria, $codigo_categoria, $nombre_categoria, $descripcion, $vida_util_anos, $estado);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                        }
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El código de categoría ya está registrado');
                    }
                } else {
                    $consulta = $objCategoria->actualizarCategoria($id_categoria, $codigo_categoria, $nombre_categoria, $descripcion, $vida_util_anos, $estado);
                    if ($consulta) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                    }
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}
