<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nueva Categoría</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="form-group row mb-2">
                        <label for="codigo_categoria" class="col-3 col-form-label">Código:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="codigo_categoria" name="codigo_categoria" placeholder="Ej: CAT-001">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="nombre_categoria" class="col-3 col-form-label">Nombre de la Categoría:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria" placeholder="Ej: Equipos de Cómputo">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="descripcion" class="col-3 col-form-label">Descripción:</label>
                        <div class="col-9">
                            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la categoría"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="vida_util_anos" class="col-3 col-form-label">Vida Útil (años):</label>
                        <div class="col-9">
                            <input type="number" class="form-control" id="vida_util_anos" name="vida_util_anos" value="10">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="estado" class="col-3 col-form-label">Estado:</label>
                        <div class="col-9">
                            <select class="form-control" id="estado" name="estado">
                                <option value="ACTIVO" selected>ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0 justify-content-end row text-center">
                        <div class="col-12">
                            <a href="<?php echo BASE_URL; ?>categorias" class="btn btn-light waves-effect waves-light">Regresar</a>
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrarCategoria();">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_categoria.js"></script>
