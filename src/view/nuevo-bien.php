<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nuevo Bien</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="form-group row mb-2">
                        <label for="codigo_patrimonial" class="col-3 col-form-label">Código Patrimonial:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="codigo_patrimonial" name="codigo_patrimonial" placeholder="Ej: CP-001-2024">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="nombre_bien" class="col-3 col-form-label">Nombre del Bien:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="nombre_bien" name="nombre_bien" placeholder="Ej: Laptop HP Pavilion">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="descripcion" class="col-3 col-form-label">Descripción:</label>
                        <div class="col-9">
                            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción del bien"></textarea>
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
    var lista_bienes_registro = [];
    var v_ambientes;
</script>
<!-- end page title -->