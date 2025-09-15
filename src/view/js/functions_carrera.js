function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_carreras_ordenadas();
}

async function listar_carreras_ordenadas() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_codigo_carrera = document.getElementById('busqueda_codigo_carrera').value;
        let busqueda_nombre_carrera = document.getElementById('busqueda_nombre_carrera').value;

        document.getElementById('filtro_codigo_carrera').value = busqueda_codigo_carrera;
        document.getElementById('filtro_nombre_carrera').value = busqueda_nombre_carrera;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_codigo_carrera', busqueda_codigo_carrera);
        formData.append('busqueda_nombre_carrera', busqueda_nombre_carrera);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Carrera.php?tipo=listar_carreras_ordenadas_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        if (!respuesta.ok) {
            throw new Error(`Error en la solicitud: ${respuesta.status} ${respuesta.statusText}`);
        }

        let json = await respuesta.json();

        document.querySelector('#modals_editar').innerHTML = ``;

        if (json.status) {
            let datos = json.contenido;
            if (datos.length === 0) {
                document.getElementById('tablas').innerHTML = `
                    <div class="alert alert-info text-center">
                        No hay carreras disponibles.
                    </div>
                `;
            } else {
                document.getElementById('tablas').innerHTML = `
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Código</th>
                                <th>Nombre de la Carrera</th>
                                <th>Descripción</th>
                                <th>Duración (semestres)</th>
                                <th>Coordinador</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="contenido_tabla"></tbody>
                    </table>
                `;
                datos.forEach(item => {
                    generarFilaTabla(item);
                });
            }
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            document.getElementById('tablas').innerHTML = `
                <div class="alert alert-warning text-center">
                    ${json.msg || "No se encontraron resultados."}
                </div>
            `;
        }

        if (json.total) {
            let paginacion = generar_paginacion(json.total, cantidad_mostrar);
            let texto_paginacion = generar_texto_paginacion(json.total, cantidad_mostrar);
            document.getElementById('texto_paginacion_tabla').innerHTML = texto_paginacion;
            document.getElementById('lista_paginacion_tabla').innerHTML = paginacion;
        }
    } catch (e) {
        console.error("Error al cargar carreras: ", e);
        document.getElementById('tablas').innerHTML = `
            <div class="alert alert-danger text-center">
                Error al cargar los datos: ${e.message}
            </div>
        `;
    } finally {
        ocultarPopupCarga();
    }
}

function generarFilaTabla(item) {
    let cont = 1;
    $(".filas_tabla").each(function () {
        cont++;
    });

    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";

    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td>${item.codigo_carrera}</td>
        <td>${item.nombre_carrera}</td>
        <td>${item.descripcion}</td>
        <td>${item.duracion_semestres}</td>
        <td>${item.coordinador}</td>
        <td>${item.estado}</td>
        <td>${item.options}</td>
    `;

    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar datos de la carrera</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="codigo_carrera${item.id}" class="col-3 col-form-label">Código:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="codigo_carrera${item.id}" name="codigo_carrera" value="${item.codigo_carrera}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="nombre_carrera${item.id}" class="col-3 col-form-label">Nombre de la Carrera:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="nombre_carrera${item.id}" name="nombre_carrera" value="${item.nombre_carrera}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="descripcion${item.id}" class="col-3 col-form-label">Descripción:</label>
                                    <div class="col-9">
                                        <textarea name="descripcion" id="descripcion${item.id}" class="form-control">${item.descripcion}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="duracion_semestres${item.id}" class="col-3 col-form-label">Duración (semestres):</label>
                                    <div class="col-9">
                                        <input type="number" class="form-control" id="duracion_semestres${item.id}" name="duracion_semestres" value="${item.duracion_semestres}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="coordinador${item.id}" class="col-3 col-form-label">Coordinador:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="coordinador${item.id}" name="coordinador" value="${item.coordinador}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="estado${item.id}" class="col-3 col-form-label">Estado:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="estado${item.id}" name="estado">
                                            <option value="ACTIVO" ${item.estado === 'ACTIVO' ? 'selected' : ''}>ACTIVO</option>
                                            <option value="INACTIVO" ${item.estado === 'INACTIVO' ? 'selected' : ''}>INACTIVO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarCarrera(${item.id})">Actualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
}

function generar_paginacion(total, cantidad_mostrar) {
    let paginas = Math.ceil(total / cantidad_mostrar);
    let html = '';
    for (let i = 1; i <= paginas; i++) {
        html += `<li class="page-item"><a class="page-link" href="javascript:numero_pagina(${i});">${i}</a></li>`;
    }
    return html;
}

function generar_texto_paginacion(total, cantidad_mostrar) {
    let pagina_actual = document.getElementById('pagina').value;
    let inicio = ((pagina_actual - 1) * cantidad_mostrar) + 1;
    let fin = Math.min(pagina_actual * cantidad_mostrar, total);
    return `Mostrando ${inicio} a ${fin} de ${total} registros`;
}

async function actualizarCarrera(id) {
    let codigo_carrera = document.querySelector('#codigo_carrera' + id).value;
    let nombre_carrera = document.querySelector('#nombre_carrera' + id).value;
    let descripcion = document.querySelector('#descripcion' + id).value;
    let duracion_semestres = document.querySelector('#duracion_semestres' + id).value;
    let coordinador = document.querySelector('#coordinador' + id).value;
    let estado = document.querySelector('#estado' + id).value;

    if (codigo_carrera == "" || nombre_carrera == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos obligatorios vacíos...',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: '',
            timer: 1000
        });
        return;
    }

    const formulario = document.getElementById('frmActualizar' + id);
    const datos = new FormData(formulario);
    datos.append('data', id);
    datos.append('sesion', session_session);
    datos.append('token', token_token);

    try {
        let respuesta = await fetch(base_url_server + 'src/control/Carrera.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            $('.modal_editar' + id).modal('hide');
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();

            Swal.fire({
                type: 'success',
                title: 'Actualizar',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_carreras_ordenadas();
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
        }
    } catch (e) {
        console.log("Error al actualizar carrera: " + e);
    }
}
async function registrarCarrera() {
    let codigo_carrera = document.querySelector('#codigo_carrera').value;
    let nombre_carrera = document.querySelector('#nombre_carrera').value;
    let descripcion = document.querySelector('#descripcion').value;
    let duracion_semestres = document.querySelector('#duracion_semestres').value;
    let coordinador = document.querySelector('#coordinador').value;
    let estado = document.querySelector('#estado').value;

    if (codigo_carrera == "" || nombre_carrera == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos obligatorios vacíos...',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        });
        return;
    }

    try {
        const datos = new FormData(document.getElementById('frmRegistrar'));
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Carrera.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            document.getElementById("frmRegistrar").reset();
            Swal.fire({
                type: 'success',
                title: 'Registro',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            setTimeout(() => {
                window.location.href = base_url + 'carreras';
            }, 1000);
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
        }
    } catch (e) {
        console.log("Error al registrar carrera: " + e);
    }
}
