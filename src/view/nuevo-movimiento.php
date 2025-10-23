<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nuevo Movimiento</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="form-group row mb-2">
                        <label for="id_bien" class="col-3 col-form-label">Bien:</label>
                        <div class="col-9">
                            <select class="form-control" id="id_bien" name="id_bien" required>
                                <option value="">Seleccione un bien</option>
                            </select>

                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="tipo_movimiento" class="col-3 col-form-label">Tipo de Movimiento:</label>
                        <div class="col-9">
                            <select class="form-control" id="tipo_movimiento" name="tipo_movimiento" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="INGRESO">INGRESO</option>
                                <option value="TRASLADO">TRASLADO</option>
                                <option value="BAJA">BAJA</option>
                                <option value="PRESTAMO">PRESTAMO</option>
                                <option value="DEVOLUCION">DEVOLUCION</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="id_dependencia_origen" class="col-3 col-form-label">Dependencia Origen:</label>
                        <div class="col-9">
                            <select class="form-control" id="id_dependencia_origen" name="id_dependencia_origen"
                                disabled>
                                <option value="">Seleccione una dependencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="id_dependencia_destino" class="col-3 col-form-label">Dependencia Destino:</label>
                        <div class="col-9">
                            <select class="form-control" id="id_dependencia_destino" name="id_dependencia_destino">
                                <option value="">Seleccione una dependencia</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="motivo" class="col-3 col-form-label">Motivo:</label>
                        <div class="col-9">
                            <textarea name="motivo" id="motivo" class="form-control" required></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="observaciones" class="col-3 col-form-label">Observaciones:</label>
                        <div class="col-9">
                            <textarea name="observaciones" id="observaciones" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="documento_referencia" class="col-3 col-form-label">Documento de Referencia:</label>
                        <div class="col-9">
                            <select class="form-control" id="documento_referencia" name="documento_referencia">
                                <option value="">Seleccione un tipo de documento</option>
                                <option value="Factura">Factura</option>
                                <option value="Orden de Compra">Orden de Compra</option>
                                <option value="Acta de Entrega">Acta de Entrega</option>
                                <option value="Informe Técnico">Informe Técnico</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 justify-content-end row text-center">
                        <div class="col-12">
                            <a href="<?php echo BASE_URL; ?>movimientos"
                                class="btn btn-light waves-effect waves-light">Regresar</a>
                            <button type="button" class="btn btn-success waves-effect waves-light"
                                onclick="registrarMovimiento();">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_movimiento.js"></script>
<script>
    cargarDatosRegistro();
    document.addEventListener('DOMContentLoaded', function() {
        cargarDatosRegistro();
        let selectBien = document.getElementById('id_bien');
        if (selectBien) {
            selectBien.addEventListener('change', function() {
                cargarDependenciaDelBien(this.value);
            });
        } else {
            console.error("Elemento id_bien no encontrado");
        }
    });
</script>