<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nuevo Bien</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row mb-2">
                                <label for="codigo_patrimonial" class="col-4 col-form-label">Código Patrimonial:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="codigo_patrimonial" name="codigo_patrimonial" placeholder="Ej: CP-001-2024" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="nombre_bien" class="col-4 col-form-label">Nombre del Bien:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="nombre_bien" name="nombre_bien" placeholder="Ej: Laptop HP Pavilion" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="descripcion" class="col-4 col-form-label">Descripción:</label>
                                <div class="col-8">
                                    <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del bien"></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="marca" class="col-4 col-form-label">Marca:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="marca" name="marca" placeholder="Ej: HP">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="modelo" class="col-4 col-form-label">Modelo:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ej: Pavilion 15">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="serie" class="col-4 col-form-label">Serie:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="serie" name="serie" placeholder="Número de serie">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="color" class="col-4 col-form-label">Color:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="color" name="color" placeholder="Ej: Negro">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="dimensiones" class="col-4 col-form-label">Dimensiones (cm):</label>
                                <div class="col-8 d-flex gap-2">
                                    <input type="number" class="form-control" id="dim_largo" name="dim_largo" placeholder="Largo" min="0" step="0.01" required>
                                    <input type="number" class="form-control" id="dim_ancho" name="dim_ancho" placeholder="Ancho" min="0" step="0.01" required>
                                    <input type="number" class="form-control" id="dim_alto" name="dim_alto" placeholder="Alto" min="0" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row mb-2">
                                <label for="id_categoria" class="col-4 col-form-label">Categoría:</label>
                                <div class="col-8">
                                    <select class="form-control" id="id_categoria" name="id_categoria" required>
                                        <option value="">Seleccione una categoría</option>
                                        <!-- Aquí se cargarían las categorías desde la base de datos -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="id_dependencia" class="col-4 col-form-label">Dependencia:</label>
                                <div class="col-8">
                                    <select class="form-control" id="id_dependencia" name="id_dependencia" required>
                                        <option value="">Seleccione una dependencia</option>
                                        <!-- Aquí se cargarían las dependencias desde la base de datos -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="ubicacion_especifica" class="col-4 col-form-label">Ubicación Específica:</label>
                                <div class="col-8">
                                    <input type="text" class="form-control" id="ubicacion_especifica" name="ubicacion_especifica" placeholder="Ej: Laboratorio de Programación">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fecha_adquisicion" class="col-4 col-form-label">Fecha Adquisición:</label>
                                <div class="col-8">
                                    <input type="date" class="form-control" id="fecha_adquisicion" name="fecha_adquisicion">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="fecha_ingreso" class="col-4 col-form-label">Fecha Ingreso:</label>
                                <div class="col-8">
                                    <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="estado_bien" class="col-4 col-form-label">Estado:</label>
                                <div class="col-8">
                                    <select class="form-control" id="estado_bien" name="estado_bien" required>
                                        <option value="">Seleccione un estado</option>
                                        <option value="ACTIVO">ACTIVO</option>
                                        <option value="BAJA">BAJA</option>
                                        <option value="MANTENIMIENTO">MANTENIMIENTO</option>
                                        <option value="PRESTADO">PRESTADO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="condicion_bien" class="col-4 col-form-label">Condición:</label>
                                <div class="col-8">
                                    <select class="form-control" id="condicion_bien" name="condicion_bien" required>
                                        <option value="">Seleccione una condición</option>
                                        <option value="NUEVO">NUEVO</option>
                                        <option value="BUENO">BUENO</option>
                                        <option value="REGULAR">REGULAR</option>
                                        <option value="MALO">MALO</option>
                                        <option value="INSERVIBLE">INSERVIBLE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <label for="es_inventariable" class="col-4 col-form-label">Inventariable:</label>
                                <div class="col-8">
                                    <select class="form-control" id="es_inventariable" name="es_inventariable" required>
                                        <option value="1">Sí</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="observaciones" class="col-2 col-form-label">Observaciones:</label>
                        <div class="col-10">
                            <textarea name="observaciones" id="observaciones" class="form-control" placeholder="Observaciones adicionales"></textarea>
                        </div>
                    </div>
                    <div class="form-group mb-0 justify-content-end row text-center">
                        <div class="col-12">
                            <a href="<?php echo BASE_URL; ?>bienes" class="btn btn-light waves-effect waves-light">Regresar</a>
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrarBien();">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_bien.js"></script>
<script>
    datos_form();       
    cargarCategorias();  
    var lista_bienes_registro = [];
    var v_ambientes = [];
</script>