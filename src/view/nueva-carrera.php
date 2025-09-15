<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Nueva Carrera</h4>
                <br>
                <form class="form-horizontal" id="frmRegistrar">
                    <div class="form-group row mb-2">
                        <label for="codigo_carrera" class="col-3 col-form-label">Código:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="codigo_carrera" name="codigo_carrera" placeholder="Ej: CAR-001">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="nombre_carrera" class="col-3 col-form-label">Nombre de la Carrera:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="nombre_carrera" name="nombre_carrera" placeholder="Ej: Computación e Informática">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="descripcion" class="col-3 col-form-label">Descripción:</label>
                        <div class="col-9">
                            <textarea name="descripcion" id="descripcion" class="form-control" placeholder="Descripción de la carrera"></textarea>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="duracion_semestres" class="col-3 col-form-label">Duración (semestres):</label>
                        <div class="col-9">
                            <input type="number" class="form-control" id="duracion_semestres" name="duracion_semestres" value="6">
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label for="coordinador" class="col-3 col-form-label">Coordinador:</label>
                        <div class="col-9">
                            <input type="text" class="form-control" id="coordinador" name="coordinador" placeholder="Nombre del coordinador">
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
                            <a href="<?php echo BASE_URL; ?>carreras" class="btn btn-light waves-effect waves-light">Regresar</a>
                            <button type="button" class="btn btn-success waves-effect waves-light" onclick="registrarCarrera();">Registrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo BASE_URL; ?>src/view/js/functions_carrera.js"></script>
