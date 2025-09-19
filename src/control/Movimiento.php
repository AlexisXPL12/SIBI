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

if ($tipo == "listar_movimientos_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            $pagina = $_POST['pagina'] ?? 1;
            $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
            $busqueda_tipo_movimiento = $_POST['busqueda_tipo_movimiento'] ?? '';
            $busqueda_estado_movimiento = $_POST['busqueda_estado_movimiento'] ?? '';
            $busqueda_bien = $_POST['busqueda_bien'] ?? '';
            $busqueda_dependencia = $_POST['busqueda_dependencia'] ?? '';

            $busqueda_filtro = $objMovimiento->buscarMovimientosOrderByFecha_tabla_filtro($busqueda_tipo_movimiento, $busqueda_estado_movimiento, $busqueda_bien, $busqueda_dependencia);
            $arr_Movimientos = $objMovimiento->buscarMovimientosOrderByFecha_tabla($pagina, $cantidad_mostrar, $busqueda_tipo_movimiento, $busqueda_estado_movimiento, $busqueda_bien, $busqueda_dependencia);

            $arr_contenido = [];
            if (!empty($arr_Movimientos)) {
                for ($i = 0; $i < count($arr_Movimientos); $i++) {
                    $arr_contenido[$i] = (object) [];
                    $arr_contenido[$i]->id = $arr_Movimientos[$i]->id_movimiento;
                    $arr_contenido[$i]->id_bien = $arr_Movimientos[$i]->id_bien;
                    $arr_contenido[$i]->codigo_patrimonial = $arr_Movimientos[$i]->codigo_patrimonial;
                    $arr_contenido[$i]->nombre_bien = $arr_Movimientos[$i]->nombre_bien;
                    $arr_contenido[$i]->tipo_movimiento = $arr_Movimientos[$i]->tipo_movimiento;
                    $arr_contenido[$i]->dependencia_origen = $arr_Movimientos[$i]->dependencia_origen;
                    $arr_contenido[$i]->dependencia_destino = $arr_Movimientos[$i]->dependencia_destino;
                    $arr_contenido[$i]->motivo = $arr_Movimientos[$i]->motivo;
                    $arr_contenido[$i]->observaciones = $arr_Movimientos[$i]->observaciones;
                    $arr_contenido[$i]->documento_referencia = $arr_Movimientos[$i]->documento_referencia;
                    $arr_contenido[$i]->usuario_solicita = $arr_Movimientos[$i]->usuario_solicita;
                    $arr_contenido[$i]->fecha_solicitud = $arr_Movimientos[$i]->fecha_solicitud;
                    $arr_contenido[$i]->fecha_ejecucion = $arr_Movimientos[$i]->fecha_ejecucion;
                    $arr_contenido[$i]->estado_movimiento = $arr_Movimientos[$i]->estado_movimiento;

                    $opciones = '<div class="btn-group" role="group">';
                    $opciones .= '<button type="button" title="Editar" class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Movimientos[$i]->id_movimiento . '"><i class="fa fa-edit"></i></button>';

                    if ($arr_Movimientos[$i]->estado_movimiento == 'PENDIENTE') {
                        $opciones .= '<button type="button" title="Ejecutar" class="btn btn-success btn-sm waves-effect waves-light" onclick="ejecutarMovimiento(' . $arr_Movimientos[$i]->id_movimiento . ')"><i class="fa fa-check"></i></button>';
                        $opciones .= '<button type="button" title="Cancelar" class="btn btn-danger btn-sm waves-effect waves-light" onclick="cancelarMovimiento(' . $arr_Movimientos[$i]->id_movimiento . ')"><i class="fa fa-times"></i></button>';
                    }

                    $opciones .= '</div>';

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

if ($tipo == "datos_registro") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $arr_Respuesta['bienes'] = $objMovimiento->obtenerBienesDisponibles();
        $arr_Respuesta['dependencias'] = $objMovimiento->obtenerDependencias();
        $arr_Respuesta['status'] = true;
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "registrar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_bien = $_POST['id_bien'];
            $tipo_movimiento = $_POST['tipo_movimiento'];
            $id_dependencia_origen = $_POST['id_dependencia_origen'];
            $id_dependencia_destino = $_POST['id_dependencia_destino'];
            $motivo = $_POST['motivo'];
            $observaciones = $_POST['observaciones'];
            $documento_referencia = $_POST['documento_referencia'];
            $usuario_solicita = $_POST['usuario_solicita'];

            if (empty($id_bien) || empty($tipo_movimiento) || empty($motivo)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                // Validar que el bien exista
                $bien = $objBien->buscarBienById($id_bien);
                if (!$bien) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'El bien seleccionado no existe');
                } else {
                    // Validar que la dependencia origen exista
                    if (!empty($id_dependencia_origen)) {
                        $dependencia_origen = $objDependencia->buscarDependenciaById($id_dependencia_origen);
                        if (!$dependencia_origen) {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'La dependencia de origen no existe');
                            echo json_encode($arr_Respuesta);
                            exit;
                        }
                    }

                    // Validar que la dependencia destino exista
                    if (!empty($id_dependencia_destino)) {
                        $dependencia_destino = $objDependencia->buscarDependenciaById($id_dependencia_destino);
                        if (!$dependencia_destino) {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'La dependencia de destino no existe');
                            echo json_encode($arr_Respuesta);
                            exit;
                        }
                    }

                    $id_movimiento = $objMovimiento->registrarMovimiento($id_bien, $tipo_movimiento, $id_dependencia_origen, $id_dependencia_destino, $motivo, $observaciones, $documento_referencia, $usuario_solicita);
                    if ($id_movimiento > 0) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Registro Exitoso');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar movimiento');
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
            $id_movimiento = $_POST['data'];
            $id_bien = $_POST['id_bien'];
            $tipo_movimiento = $_POST['tipo_movimiento'];
            $id_dependencia_origen = $_POST['id_dependencia_origen'];
            $id_dependencia_destino = $_POST['id_dependencia_destino'];
            $motivo = $_POST['motivo'];
            $observaciones = $_POST['observaciones'];
            $documento_referencia = $_POST['documento_referencia'];
            $usuario_solicita = $_POST['usuario_solicita'];

            if (empty($id_movimiento) || empty($id_bien) || empty($tipo_movimiento) || empty($motivo)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                // Validar que el movimiento exista
                $movimiento = $objMovimiento->buscarMovimientoById($id_movimiento);
                if (!$movimiento) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'El movimiento no existe');
                } else {
                    // Validar que el bien exista
                    $bien = $objBien->buscarBienById($id_bien);
                    if (!$bien) {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El bien seleccionado no existe');
                    } else {
                        // Validar que la dependencia origen exista
                        if (!empty($id_dependencia_origen)) {
                            $dependencia_origen = $objDependencia->buscarDependenciaById($id_dependencia_origen);
                            if (!$dependencia_origen) {
                                $arr_Respuesta = array('status' => false, 'mensaje' => 'La dependencia de origen no existe');
                                echo json_encode($arr_Respuesta);
                                exit;
                            }
                        }

                        // Validar que la dependencia destino exista
                        if (!empty($id_dependencia_destino)) {
                            $dependencia_destino = $objDependencia->buscarDependenciaById($id_dependencia_destino);
                            if (!$dependencia_destino) {
                                $arr_Respuesta = array('status' => false, 'mensaje' => 'La dependencia de destino no existe');
                                echo json_encode($arr_Respuesta);
                                exit;
                            }
                        }

                        $consulta = $objMovimiento->actualizarMovimiento($id_movimiento, $id_bien, $tipo_movimiento, $id_dependencia_origen, $id_dependencia_destino, $motivo, $observaciones, $documento_referencia, $usuario_solicita);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                        }
                    }
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "ejecutar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_movimiento = $_POST['id_movimiento'];
            $usuario_autoriza = $_POST['usuario_autoriza'];

            if (empty($id_movimiento)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, ID de movimiento vacío');
            } else {
                try {
                    $resultado = $objMovimiento->ejecutarMovimiento($id_movimiento, $usuario_autoriza);
                    if ($resultado) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Movimiento ejecutado correctamente');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al ejecutar movimiento');
                    }
                } catch (Exception $e) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => $e->getMessage());
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "cancelar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_movimiento = $_POST['id_movimiento'];
            $usuario_autoriza = $_POST['usuario_autoriza'];

            if (empty($id_movimiento)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, ID de movimiento vacío');
            } else {
                $consulta = $objMovimiento->cancelarMovimiento($id_movimiento, $usuario_autoriza);
                if ($consulta) {
                    $arr_Respuesta = array('status' => true, 'mensaje' => 'Movimiento cancelado correctamente');
                } else {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al cancelar movimiento');
                }
            }
        }
    }
    echo json_encode($arr_Respuesta);
}