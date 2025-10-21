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

if ($tipo == "listar_bienes_ordenados_tabla") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    try {
        if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
            $pagina = $_POST['pagina'] ?? 1;
            $cantidad_mostrar = $_POST['cantidad_mostrar'] ?? 10;
            $busqueda_codigo_patrimonial = $_POST['busqueda_codigo_patrimonial'] ?? '';
            $busqueda_nombre_bien = $_POST['busqueda_nombre_bien'] ?? '';

            $busqueda_filtro = $objBien->buscarBienesOrderByNombre_tabla_filtro($busqueda_codigo_patrimonial, $busqueda_nombre_bien);
            $arr_Bienes = $objBien->buscarBienesOrderByNombre_tabla($pagina, $cantidad_mostrar, $busqueda_codigo_patrimonial, $busqueda_nombre_bien);

            $arr_contenido = [];
            if (!empty($arr_Bienes)) {
                for ($i = 0; $i < count($arr_Bienes); $i++) {
                    $arr_contenido[$i] = (object) [];
                    $arr_contenido[$i]->id = $arr_Bienes[$i]->id_bien;
                    $arr_contenido[$i]->codigo_patrimonial = $arr_Bienes[$i]->codigo_patrimonial;
                    $arr_contenido[$i]->nombre_bien = $arr_Bienes[$i]->nombre_bien;
                    $arr_contenido[$i]->descripcion = $arr_Bienes[$i]->descripcion;
                    $arr_contenido[$i]->marca = $arr_Bienes[$i]->marca;
                    $arr_contenido[$i]->modelo = $arr_Bienes[$i]->modelo;
                    $arr_contenido[$i]->serie = $arr_Bienes[$i]->serie;
                    $arr_contenido[$i]->color = $arr_Bienes[$i]->color;
                    $arr_contenido[$i]->dimensiones = $arr_Bienes[$i]->dimensiones;
                    $arr_contenido[$i]->id_categoria = $arr_Bienes[$i]->id_categoria;
                    $arr_contenido[$i]->id_dependencia = $arr_Bienes[$i]->id_dependencia;
                    $arr_contenido[$i]->ubicacion_especifica = $arr_Bienes[$i]->ubicacion_especifica;
                    $arr_contenido[$i]->fecha_adquisicion = $arr_Bienes[$i]->fecha_adquisicion;
                    $arr_contenido[$i]->fecha_ingreso = $arr_Bienes[$i]->fecha_ingreso;
                    $arr_contenido[$i]->estado_bien = $arr_Bienes[$i]->estado_bien;
                    $arr_contenido[$i]->condicion_bien = $arr_Bienes[$i]->condicion_bien;
                    $arr_contenido[$i]->observaciones = $arr_Bienes[$i]->observaciones;
                    $arr_contenido[$i]->es_inventariable = $arr_Bienes[$i]->es_inventariable;
                    $arr_contenido[$i]->usuario_registro = $arr_Bienes[$i]->usuario_registro;

                    $opciones = '<button type="button" title="Editar" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target=".modal_editar' . $arr_Bienes[$i]->id_bien . '"><i class="fa fa-edit"></i></button>';
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

    $id_sesion = $_POST['sesion'] ?? '';
    $token = $_POST['token'] ?? '';

    if (empty($id_sesion) || empty($token)) {
        $arr_Respuesta['msg'] = 'Sesión o token no proporcionados.';
        echo json_encode($arr_Respuesta);
        exit;
    }

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $usuarioSesion = $objSesion->obtenerUsuarioPorSesion($id_sesion);
        if (!$usuarioSesion) {
            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, usuario no encontrado en la sesión');
            echo json_encode($arr_Respuesta);
            exit;
        }

        $usuario_registro = $usuarioSesion->id;

        if ($_POST) {
            $codigo_patrimonial = trim($_POST['codigo_patrimonial'] ?? '');
            $nombre_bien = trim($_POST['nombre_bien'] ?? '');

            // Validar que los campos obligatorios no estén vacíos
            if (empty($codigo_patrimonial) || empty($nombre_bien)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
                echo json_encode($arr_Respuesta);
                exit;
            }

            $descripcion = $_POST['descripcion'] ?? '';
            $marca = $_POST['marca'] ?? '';
            $modelo = $_POST['modelo'] ?? '';
            $serie = $_POST['serie'] ?? '';
            $color = $_POST['color'] ?? '';
            $dimensiones = $_POST['dimensiones'] ?? '';
            $id_categoria = $_POST['id_categoria'] ?? '';
            $id_dependencia = $_POST['id_dependencia'] ?? '';
            $ubicacion_especifica = $_POST['ubicacion_especifica'] ?? '';
            $fecha_adquisicion = $_POST['fecha_adquisicion'] ?? '';
            $fecha_ingreso = $_POST['fecha_ingreso'] ?? '';
            $estado_bien = $_POST['estado_bien'] ?? '';
            $condicion_bien = $_POST['condicion_bien'] ?? '';
            $observaciones = $_POST['observaciones'] ?? '';
            $es_inventariable = $_POST['es_inventariable'] ?? '';
            $usuario_registro = $_POST['usuario_registro'] ?? $usuarioSesion->id;

            $arr_Bien = $objBien->buscarBienByCodigoPatrimonial($codigo_patrimonial);
            if ($arr_Bien) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Registro Fallido, el código patrimonial ya se encuentra registrado');
                echo json_encode($arr_Respuesta);
                exit;
            }

            $id_bien = $objBien->registrarBien(
                $codigo_patrimonial,
                $nombre_bien,
                $descripcion,
                $marca,
                $modelo,
                $serie,
                $color,
                $dimensiones,
                $id_categoria,
                $id_dependencia,
                $ubicacion_especifica,
                $fecha_adquisicion,
                $fecha_ingreso,
                $estado_bien,
                $condicion_bien,
                $observaciones,
                $es_inventariable,
                $usuario_registro
            );

            if ($id_bien > 0) {
                $arr_Respuesta = array('status' => true, 'mensaje' => 'Registro Exitoso');
            } else {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al registrar bien');
            }
        }
    }
    echo json_encode($arr_Respuesta);
}


if ($tipo == "actualizar") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        if ($_POST) {
            $id_bien = $_POST['data'];
            $codigo_patrimonial = $_POST['codigo_patrimonial'];
            $nombre_bien = $_POST['nombre_bien'];
            $descripcion = $_POST['descripcion'];
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $serie = $_POST['serie'];
            $color = $_POST['color'];
            $dimensiones = $_POST['dimensiones'];
            $id_categoria = $_POST['id_categoria'];
            $id_dependencia = $_POST['id_dependencia'];
            $ubicacion_especifica = $_POST['ubicacion_especifica'];
            $fecha_adquisicion = $_POST['fecha_adquisicion'];
            $fecha_ingreso = $_POST['fecha_ingreso'];
            $numero_factura = $_POST['numero_factura'];
            $numero_orden_compra = $_POST['numero_orden_compra'];
            $estado_bien = $_POST['estado_bien'];
            $condicion_bien = $_POST['condicion_bien'];
            $observaciones = $_POST['observaciones'];
            $es_inventariable = $_POST['es_inventariable'];
            $usuario_registro = $_POST['usuario_registro'];

            if (empty($id_bien) || empty($codigo_patrimonial) || empty($nombre_bien)) {
                $arr_Respuesta = array('status' => false, 'mensaje' => 'Error, campos obligatorios vacíos');
            } else {
                $arr_Bien = $objBien->buscarBienByCodigoPatrimonial($codigo_patrimonial);
                if ($arr_Bien) {
                    if ($arr_Bien->id_bien == $id_bien) {
                        $consulta = $objBien->actualizarBien($id_bien, $codigo_patrimonial, $nombre_bien, $descripcion, $marca, $modelo, $serie, $color, $dimensiones, $id_categoria, $id_dependencia, $ubicacion_especifica, $fecha_adquisicion, $fecha_ingreso, $numero_factura, $numero_orden_compra, $estado_bien, $condicion_bien, $observaciones, $es_inventariable, $usuario_registro);
                        if ($consulta) {
                            $arr_Respuesta = array('status' => true, 'mensaje' => 'Actualizado Correctamente');
                        } else {
                            $arr_Respuesta = array('status' => false, 'mensaje' => 'Error al actualizar registro');
                        }
                    } else {
                        $arr_Respuesta = array('status' => false, 'mensaje' => 'El código patrimonial ya está registrado');
                    }
                } else {
                    $consulta = $objBien->actualizarBien($id_bien, $codigo_patrimonial, $nombre_bien, $descripcion, $marca, $modelo, $serie, $color, $dimensiones, $id_categoria, $id_dependencia, $ubicacion_especifica, $fecha_adquisicion, $fecha_ingreso, $numero_factura, $numero_orden_compra, $estado_bien, $condicion_bien, $observaciones, $es_inventariable, $usuario_registro);
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
if ($tipo == "listar_todos_bienes") {
    $arr_Respuesta = array('status' => false, 'msg' => 'Error_Sesion');

    if ($objSesion->verificar_sesion_si_activa($id_sesion, $token)) {
        $arr_Respuesta = array('status' => false, 'contenido' => []);
        $arr_Bienes = $objBien->listarTodosLosBienes();

        $arr_contenido = [];
        if (!empty($arr_Bienes)) {
            foreach ($arr_Bienes as $bien) {
                $arr_contenido[] = [
                    'cod_patrimonial' => $bien->cod_patrimonial,
                    'denominacion' => $bien->denominacion,
                    'marca' => $bien->marca,
                    'modelo' => $bien->modelo,
                    'tipo' => $bien->tipo,
                    'color' => $bien->color,
                    'serie' => $bien->serie,
                    'dimensiones' => $bien->dimensiones,
                    'valor' => $bien->valor,
                    'situacion' => $bien->situacion,
                    'estado_conservacion' => $bien->estado_conservacion,
                    'observaciones' => $bien->observaciones,
                    'fecha_registro' => $bien->fecha_registro,
                    'ambiente_institucion' => [
                        'id' => $bien->ambiente_id,
                        'codigo' => $bien->ambiente_codigo,
                        'detalle' => $bien->ambiente_detalle,
                        'otros_detalle' => $bien->otros_detalle,
                        'encargado' => $bien->ambiente_encargado,
                        'institucion' => [
                            'id' => $bien->institucion_id,
                            'nombre' => $bien->institucion_nombre,
                            'cod_modular' => $bien->institucion_cod_modular,
                            'ruc' => $bien->institucion_ruc
                        ]
                    ],
                    'usuario' => [
                        'id' => $bien->usuario_id,
                        'nombres_apellidos' => $bien->nombre_usuario,
                        'dni' => $bien->usuario_dni
                    ]
                ];
            }
            $arr_Respuesta['status'] = true;
            $arr_Respuesta['contenido'] = $arr_contenido;
        }
    }

    echo json_encode($arr_Respuesta);
}
