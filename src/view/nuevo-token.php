<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nuevo Token</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="form-group row mb-2">
                        <label for="id_client_api" class="col-3 col-form-label">Cliente</label>
                        <div class="col-9">
                            <select class="form-control" id="id_client_api" name="id_client_api" required>
                                <option value="">Seleccione un cliente</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="estado" class="col-3 col-form-label">Estado</label>
                        <div class="col-9">
                            <select class="form-control" id="estado" name="estado">
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 justify-content-end row text-center">
                        <div class="col-12">
                            <a href="<?php echo BASE_URL;?>tokens" class="btn btn-light waves-effect waves-light">Regresar</a>
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrar_token();">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_tokens.js"></script>
<script>
    cargarClientesEnSelect();
</script>

