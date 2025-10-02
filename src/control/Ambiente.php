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
$objCategoria = new CategoriaModel();
$objAdmin = new AdminModel();
$objUsuario = new UsuarioModel();

//variables de sesion
$id_sesion = $_REQUEST['sesion'];
$token = $_REQUEST['token'];

if ($tipo == "listar_dependencias") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if (empty($id_sesion) || empty($token) || !$objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            throw new Exception("Sesión no válida");
        }

        $arr_Dependencias = $objAmbiente->listarDependencias();

        $arr_contenido = [];
        if (!empty($arr_Dependencias)) {
            foreach ($arr_Dependencias as $dependencia) {
                $arr_contenido[] = [
                    'id_dependencia' => $dependencia->id_dependencia,
                    'nombre_dependencia' => $dependencia->nombre_dependencia
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

if ($tipo == "listar_ambientes_ordenados_tabla_e") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $ies = $_POST['ies'] ?? 1;
        $pagina = $_POST['pagina'] ?? 1;
        $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
        $busqueda_codigo = $_POST['busqueda_codigo'] ?? '';
        $busqueda_detalle = $_POST['busqueda_detalle'] ?? '';
        $busqueda_encargado = $_POST['busqueda_encargado'] ?? '';
        
        $arr_Respuesta = array('status' => false, 'contenido' => '');
        
        // Usar el método para obtener ambientes con filtros
        $arr_Ambientes = $objAmbiente->buscarAmbientesConDetalles_tabla_filtro(
            $busqueda_codigo, 
            $busqueda_detalle, 
            $busqueda_encargado,
            $ies
        );
        
        $arr_contenido = [];
        
        if (!empty($arr_Ambientes)) {
            for ($i = 0; $i < count($arr_Ambientes); $i++) {
                $arr_contenido[$i] = (object) [];
                $arr_contenido[$i]->id = $arr_Ambientes[$i]->id;
                $arr_contenido[$i]->codigo = $arr_Ambientes[$i]->codigo;
                $arr_contenido[$i]->detalle = $arr_Ambientes[$i]->detalle;
                $arr_contenido[$i]->encargado = $arr_Ambientes[$i]->encargado;
                $arr_contenido[$i]->otros_detalle = $arr_Ambientes[$i]->otros_detalle;
                
                // Incluir información de bienes obtenida del JOIN
                $arr_contenido[$i]->total_bienes = $arr_Ambientes[$i]->total_bienes ?? 0;
                $arr_contenido[$i]->valor_total_bienes = $arr_Ambientes[$i]->valor_total_bienes ?? 0;
                
                // Badge para mostrar cantidad de bienes
                $badgeClass = ($arr_Ambientes[$i]->total_bienes > 0) ? 'badge-success' : 'badge-secondary';
                $textoB = ($arr_Ambientes[$i]->total_bienes > 0) ? $arr_Ambientes[$i]->total_bienes . ' bienes' : 'Sin bienes';
                
                // Formatear valor total
                $valorFormateado = 'S/. ' . number_format($arr_Ambientes[$i]->valor_total_bienes ?? 0, 2);
                
                // Opciones para la tabla (botones de acción)
                $opciones = '<div class="btn-group" role="group">';
                $opciones .= '<button type="button" title="Ver Detalle" class="btn btn-info btn-sm waves-effect waves-light" data-toggle="modal" data-target=".modal_detalle' . $arr_Ambientes[$i]->id . '"><i class="fa fa-eye"></i></button>';
                $opciones .= '<button type="button" title="Editar" class="btn btn-warning btn-sm waves-effect waves-light" onclick="editarAmbiente(' . $arr_Ambientes[$i]->id . ')"><i class="fa fa-edit"></i></button>';
                $opciones .= '<button type="button" title="Ver Bienes" class="btn btn-success btn-sm waves-effect waves-light" onclick="verBienesAmbiente(' . $arr_Ambientes[$i]->id . ')"><i class="fa fa-list"></i></button>';
                
                // Solo mostrar botón de eliminar si no tiene bienes asignados
                if (($arr_Ambientes[$i]->total_bienes ?? 0) == 0) {
                    $opciones .= '<button type="button" title="Eliminar" class="btn btn-danger btn-sm waves-effect waves-light" onclick="eliminarAmbiente(' . $arr_Ambientes[$i]->id . ')"><i class="fa fa-trash"></i></button>';
                }
                $opciones .= '</div>';
                
                $arr_contenido[$i]->options = $opciones;
                $arr_contenido[$i]->bienes_badge = '<span class="badge ' . $badgeClass . '">' . $textoB . '</span>';
                $arr_contenido[$i]->valor_formateado = $valorFormateado;
            }
            $arr_Respuesta['total'] = count($arr_Ambientes);
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "listar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $arr_Respuesta = array('status' => false, 'contenido' => '');
        $arr_Ambiente = $objAmbiente->listarDependencias(); 
        $arr_contenido = [];
        if (!empty($arr_Ambiente)) {
            for ($i = 0; $i < count($arr_Ambiente); $i++) {
                $arr_contenido[$i] = (object) [];
                $arr_contenido[$i]->id_dependencia = $arr_Ambiente[$i]->id_dependencia; 
                $arr_contenido[$i]->nombre_dependencia = $arr_Ambiente[$i]->nombre_dependencia; 
            }
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        }
    }
    echo json_encode($arr_Respuesta);
}

if ($tipo == "listar_dependencias_ordenadas_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            $pagina = $_POST['pagina'] ?? 1;
            $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
            $busqueda_tabla_codigo = $_POST['busqueda_tabla_codigo'] ?? '';
            $busqueda_tabla_dependencia = $_POST['busqueda_tabla_dependencia'] ?? '';

            $busqueda_filtro = $objAmbiente->buscarDependenciasOrderByNombre_tabla_filtro($busqueda_tabla_codigo, $busqueda_tabla_dependencia);
            $arr_Dependencias = $objAmbiente->buscarDependenciasOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_tabla_codigo, $busqueda_tabla_dependencia);

            $arr_contenido = [];
            if (!empty($arr_Dependencias)) {
                for ($i = 0; $i < count($arr_Dependencias); $i++) {
                    $arr_contenido[$i] = (object) [];
                    $arr_contenido[$i]->id = $arr_Dependencias[$i]->id_dependencia;
                    $arr_contenido[$i]->encargado = $arr_Dependencias[$i]->responsable;
                    $arr_contenido[$i]->codigo = $arr_Dependencias[$i]->codigo_dependencia;
                    $arr_contenido[$i]->detalle = $arr_Dependencias[$i]->nombre_dependencia;
                    $arr_contenido[$i]->otros_detalle = $arr_Dependencias[$i]->descripcion;
                    $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Dependencias[$i]->id_dependencia . '"><i class="fa fa-edit"></i></button>';
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
            $encargado = $_POST['encargado'];
            $codigo = $_POST['codigo'];
            $detalle = $_POST['detalle'];
            $otros_detalle = $_POST['otros_detalle'];

            if (empty($encargado) || empty($codigo) || empty($detalle) || empty($otros_detalle)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos vacíos');
            } else {
                $arr_Dependencia = $objAmbiente->buscarDependenciaByCodigo($codigo);
                if ($arr_Dependencia) {
                    $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, el código ya se encuentra registrado');
                } else {
                    $id_dependencia = $objAmbiente->registrarDependencia($encargado, $codigo, $detalle, $otros_detalle);
                    if ($id_dependencia > 0) {
                        $arr_Respuesta = array('status' => true, 'mensaje' => 'Registro Exitoso');
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar dependencia');
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
            $id = $_POST['data'];
            $encargado = $_POST['encargado'];
            $codigo = $_POST['codigo'];
            $detalle = $_POST['detalle'];
            $otros_detalle = $_POST['otros_detalle'];

            if (empty($id) || empty($encargado) || empty($codigo) || empty($detalle) || empty($otros_detalle)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos vacíos');
            } else {
                $arr_Dependencia = $objAmbiente->buscarDependenciaByCodigo($codigo);
                if ($arr_Dependencia) {
                    if ($arr_Dependencia->id_dependencia == $id) {
                        $consulta = $objAmbiente->actualizarDependencia($id, $encargado, $codigo, $detalle, $otros_detalle);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                        }
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El código ya está registrado');
                    }
                } else {
                    $consulta = $objAmbiente->actualizarDependencia($id, $encargado, $codigo, $detalle, $otros_detalle);
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
if ($tipo == "datos_registro") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        //repuesta
        $arr_Instirucion = $objInstitucion->buscarInstitucionOrdenado();
        $arr_Respuesta['instituciones'] = $arr_Instirucion;
        $arr_Respuesta['status'] = true;
        $arr_Respuesta['msg'] = "Datos encontrados";
    }
    echo json_encode($arr_Respuesta);
}
if ($tipo == "listar_todos_ambientes") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');
    
    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $arr_Respuesta = array('status' => false, 'contenido' => []);
        $arr_Ambientes = $objAmbiente->listarTodosLosAmbientes(); // Asegúrate de que este método exista en $objAmbiente
        
        $arr_contenido = [];
        if (!empty($arr_Ambientes)) {
            foreach ($arr_Ambientes as $ambiente) {
                // Obtener información de la institución asociada
                $institucion = isset($ambiente->id_ies) ? $objInstitucion->buscarInstitucionById($ambiente->id_ies) : null;
                
                $arr_contenido[] = [
                    'id' => $ambiente->id,
                    'codigo' => $ambiente->codigo,
                    'detalle' => $ambiente->detalle,
                    'encargado' => $ambiente->encargado,
                    'otros_detalle' => $ambiente->otros_detalle,
                    'institucion' => $institucion ? [
                        'id' => $institucion->id,
                        'nombre' => $institucion->nombre,
                        'cod_modular' => $institucion->cod_modular,
                        'ruc' => $institucion->ruc
                    ] : null
                ];
            }
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        }
    }
    
    echo json_encode($arr_Respuesta);
}