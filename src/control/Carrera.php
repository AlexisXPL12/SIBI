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

if ($tipo == "listar_carreras_ordenadas_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            $pagina = $_POST['pagina'] ?? 1;
            $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
            $busqueda_codigo_carrera = $_POST['busqueda_codigo_carrera'] ?? '';
            $busqueda_nombre_carrera = $_POST['busqueda_nombre_carrera'] ?? '';

            $busqueda_filtro = $objCarrera->buscarCarrerasOrderByNombre_tabla_filtro($busqueda_codigo_carrera, $busqueda_nombre_carrera);
            $arr_Carreras = $objCarrera->buscarCarrerasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_carrera, $busqueda_nombre_carrera);

            $arr_contenido = [];
            if (!empty($arr_Carreras)) {
                for ($i = 0; $i < count($arr_Carreras); $i++) {
                    $arr_contenido[$i] = (object) [];
                    $arr_contenido[$i]->id = $arr_Carreras[$i]->id_carrera;
                    $arr_contenido[$i]->codigo_carrera = $arr_Carreras[$i]->codigo_carrera;
                    $arr_contenido[$i]->nombre_carrera = $arr_Carreras[$i]->nombre_carrera;
                    $arr_contenido[$i]->descripcion = $arr_Carreras[$i]->descripcion;
                    $arr_contenido[$i]->duracion_semestres = $arr_Carreras[$i]->duracion_semestres;
                    $arr_contenido[$i]->coordinador = $arr_Carreras[$i]->coordinador;
                    $arr_contenido[$i]->estado = $arr_Carreras[$i]->estado;
                    $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Carreras[$i]->id_carrera . '"><i class="fa fa-edit"></i></button>';
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
            $codigo_carrera = $_POST['codigo_carrera'];
            $nombre_carrera = $_POST['nombre_carrera'];
            $descripcion = $_POST['descripcion'];
            $duracion_semestres = $_POST['duracion_semestres'];
            $coordinador = $_POST['coordinador'];
            $estado = $_POST['estado'];

            if (empty($codigo_carrera) || empty($nombre_carrera)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Carrera = $objCarrera->buscarCarreraByCodigo($codigo_carrera);
                if ($arr_Carrera) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, el código de carrera ya se encuentra registrado');
                } else {
                    $id_carrera = $objCarrera->registrarCarrera($codigo_carrera, $nombre_carrera, $descripcion, $duracion_semestres, $coordinador, $estado);
                    if ($id_carrera > 0) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Registro Exitoso');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar carrera');
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
            $id_carrera = $_POST['data'];
            $codigo_carrera = $_POST['codigo_carrera'];
            $nombre_carrera = $_POST['nombre_carrera'];
            $descripcion = $_POST['descripcion'];
            $duracion_semestres = $_POST['duracion_semestres'];
            $coordinador = $_POST['coordinador'];
            $estado = $_POST['estado'];

            if (empty($id_carrera) || empty($codigo_carrera) || empty($nombre_carrera)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Carrera = $objCarrera->buscarCarreraByCodigo($codigo_carrera);
                if ($arr_Carrera) {
                    if ($arr_Carrera->id_carrera == $id_carrera) {
                        $consulta = $objCarrera->actualizarCarrera($id_carrera, $codigo_carrera, $nombre_carrera, $descripcion, $duracion_semestres, $coordinador, $estado);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                        }
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El código de carrera ya está registrado');
                    }
                } else {
                    $consulta = $objCarrera->actualizarCarrera($id_carrera, $codigo_carrera, $nombre_carrera, $descripcion, $duracion_semestres, $coordinador, $estado);
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