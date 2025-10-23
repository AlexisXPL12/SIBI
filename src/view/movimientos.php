<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="page-title-box d-flex align-items-center justify-content-between p-0">
                    <h4 class="mb-0 font-size-18">Movimientos</h4>
                    <div class="page-title-right">
                        <a href="<?php echo BASE_URL; ?>nuevo-movimiento" class="btn btn-primary waves-effect waves-light">+ Nuevo</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Filtros de Búsqueda</h4>
                <div class="row col-12">
                    <!-- Filtro por Tipo de Movimiento -->
                    <div class="form-group row mb-3 col-md-4">
                        <label for="busqueda_tipo_movimiento" class="col-5 col-form-label">Tipo:</label>
                        <div class="col-7">
                            <select class="form-control" name="busqueda_tipo_movimiento" id="busqueda_tipo_movimiento">
                                <option value="">Todos</option>
                                <option value="INGRESO">INGRESO</option>
                                <option value="TRASLADO">TRASLADO</option>
                                <option value="BAJA">BAJA</option>
                                <option value="PRESTAMO">PRESTAMO</option>
                                <option value="DEVOLUCION">DEVOLUCION</option>
                            </select>
                        </div>
                    </div>
                    <!-- Filtro por Estado del Movimiento -->
                    <div class="form-group row mb-3 col-md-4">
                        <label for="busqueda_estado_movimiento" class="col-5 col-form-label">Estado:</label>
                        <div class="col-7">
                            <select class="form-control" name="busqueda_estado_movimiento" id="busqueda_estado_movimiento">
                                <option value="">Todos</option>
                                <option value="PENDIENTE">PENDIENTE</option>
                                <option value="EJECUTADO">EJECUTADO</option>
                                <option value="CANCELADO">CANCELADO</option>
                            </select>
                        </div>
                    </div>
                    <!-- Espacio adicional para alinear el botón de búsqueda -->
                    <div class="form-group row mb-3 col-md-4 d-flex align-items-end">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-primary waves-effect waves-light" onclick="numero_pagina(1);">
                                <i class="fa fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Resultados de Búsqueda</h4>
                <div id="filtros_tabla_header" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <input type="hidden" id="pagina" value="1">
                    <input type="hidden" id="filtro_tipo_movimiento" value="">
                    <input type="hidden" id="filtro_estado_movimiento" value="">
                    <input type="hidden" id="filtro_bien" value="">
                    <input type="hidden" id="filtro_dependencia" value="">
                    <div>
                        <label for="cantidad_mostrar">Mostrar</label>
                        <select name="cantidad_mostrar" id="cantidad_mostrar" class="form-control-sm" onchange="numero_pagina(1);">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="cantidad_mostrar">registros</label>
                    </div>
                </div>
                <div id="tablas"></div>
                <div id="filtros_tabla_footer" class="form-group row page-title-box d-flex align-items-center justify-content-between m-0 mb-1 p-0">
                    <div id="texto_paginacion_tabla"></div>
                    <div id="paginacion_tabla">
                        <ul class="pagination justify-content-end" id="lista_paginacion_tabla"></ul>
                    </div>
                </div>
                <div id="modals_editar"></div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_movimiento.js"></script>
<script>
    listar_movimientos_ordenados();
</script>
