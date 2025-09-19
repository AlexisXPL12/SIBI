function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_movimientos_ordenados();
}

async function listar_movimientos_ordenados() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tipo_movimiento = document.getElementById('busqueda_tipo_movimiento').value;
        let busqueda_estado_movimiento = document.getElementById('busqueda_estado_movimiento').value;
        let busqueda_bien = document.getElementById('busqueda_bien').value;
        let busqueda_dependencia = document.getElementById('busqueda_dependencia').value;

        document.getElementById('filtro_tipo_movimiento').value = busqueda_tipo_movimiento;
        document.getElementById('filtro_estado_movimiento').value = busqueda_estado_movimiento;
        document.getElementById('filtro_bien').value = busqueda_bien;
        document.getElementById('filtro_dependencia').value = busqueda_dependencia;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tipo_movimiento', busqueda_tipo_movimiento);
        formData.append('busqueda_estado_movimiento', busqueda_estado_movimiento);
        formData.append('busqueda_bien', busqueda_bien);
        formData.append('busqueda_dependencia', busqueda_dependencia);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=listar_movimientos_ordenados_tabla', {
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
                        No hay movimientos disponibles.
                    </div>
                `;
            } else {
                document.getElementById('tablas').innerHTML = `
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Bien</th>
                                <th>Tipo</th>
                                <th>Origen/Destino</th>
                                <th>Motivo</th>
                                <th>Estado</th>
                                <th>Fecha Solicitud</th>
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
        console.error("Error al cargar movimientos: ", e);
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

    let origen_destino = '';
    if (item.dependencia_origen) {
        origen_destino += item.dependencia_origen;
    }
    if (item.dependencia_destino) {
        origen_destino += ' → ' + item.dependencia_destino;
    }

    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";

    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td>${item.nombre_bien || 'N/A'} (${item.codigo_patrimonial || 'N/A'})</td>
        <td>${item.tipo_movimiento}</td>
        <td>${origen_destino}</td>
        <td>${item.motivo}</td>
        <td><span class="badge ${item.estado_movimiento === 'PENDIENTE' ? 'badge-warning' : item.estado_movimiento === 'EJECUTADO' ? 'badge-success' : 'badge-danger'}">${item.estado_movimiento}</span></td>
        <td>${item.fecha_solicitud}</td>
        <td>${item.options}</td>
    `;

    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar datos del movimiento</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="id_bien${item.id}" class="col-3 col-form-label">Bien:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="id_bien${item.id}" name="id_bien" required>
                                            <option value="">Seleccione un bien</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="tipo_movimiento${item.id}" class="col-3 col-form-label">Tipo de Movimiento:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="tipo_movimiento${item.id}" name="tipo_movimiento" required>
                                            <option value="INGRESO" ${item.tipo_movimiento === 'INGRESO' ? 'selected' : ''}>INGRESO</option>
                                            <option value="TRASLADO" ${item.tipo_movimiento === 'TRASLADO' ? 'selected' : ''}>TRASLADO</option>
                                            <option value="BAJA" ${item.tipo_movimiento === 'BAJA' ? 'selected' : ''}>BAJA</option>
                                            <option value="PRESTAMO" ${item.tipo_movimiento === 'PRESTAMO' ? 'selected' : ''}>PRESTAMO</option>
                                            <option value="DEVOLUCION" ${item.tipo_movimiento === 'DEVOLUCION' ? 'selected' : ''}>DEVOLUCION</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="id_dependencia_origen${item.id}" class="col-3 col-form-label">Dependencia Origen:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="id_dependencia_origen${item.id}" name="id_dependencia_origen">
                                            <option value="">Seleccione una dependencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="id_dependencia_destino${item.id}" class="col-3 col-form-label">Dependencia Destino:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="id_dependencia_destino${item.id}" name="id_dependencia_destino">
                                            <option value="">Seleccione una dependencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="motivo${item.id}" class="col-3 col-form-label">Motivo:</label>
                                    <div class="col-9">
                                        <textarea name="motivo" id="motivo${item.id}" class="form-control" required>${item.motivo}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="observaciones${item.id}" class="col-3 col-form-label">Observaciones:</label>
                                    <div class="col-9">
                                        <textarea name="observaciones" id="observaciones${item.id}" class="form-control">${item.observaciones}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="documento_referencia${item.id}" class="col-3 col-form-label">Documento de Referencia:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="documento_referencia${item.id}" name="documento_referencia" value="${item.documento_referencia}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="usuario_solicita${item.id}" class="col-3 col-form-label">Usuario que Solicita:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="usuario_solicita${item.id}" name="usuario_solicita" value="${item.usuario_solicita}" readonly>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarMovimiento(${item.id})">Actualizar</button>
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

    // Cargar datos en los selects del modal
    cargarDatosModal(item.id);
}

async function cargarDatosModal(id) {
    try {
        const formData = new FormData();
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=datos_registro', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        let json = await respuesta.json();

        if (json.status) {
            // Cargar bienes
            let selectBien = document.getElementById('id_bien' + id);
            selectBien.innerHTML = '<option value="">Seleccione un bien</option>';
            json.bienes.forEach(bien => {
                let option = document.createElement('option');
                option.value = bien.id_bien;
                option.textContent = `${bien.nombre_bien} (${bien.codigo_patrimonial})`;
                selectBien.appendChild(option);
            });

            // Cargar dependencias
            let selectOrigen = document.getElementById('id_dependencia_origen' + id);
            let selectDestino = document.getElementById('id_dependencia_destino' + id);

            selectOrigen.innerHTML = '<option value="">Seleccione una dependencia</option>';
            selectDestino.innerHTML = '<option value="">Seleccione una dependencia</option>';

            json.dependencias.forEach(dependencia => {
                let optionOrigen = document.createElement('option');
                optionOrigen.value = dependencia.id_dependencia;
                optionOrigen.textContent = dependencia.nombre_dependencia;

                let optionDestino = document.createElement('option');
                optionDestino.value = dependencia.id_dependencia;
                optionDestino.textContent = dependencia.nombre_dependencia;

                selectOrigen.appendChild(optionOrigen);
                selectDestino.appendChild(optionDestino);
            });
        }
    } catch (e) {
        console.error("Error al cargar datos para el modal: ", e);
    }
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

async function actualizarMovimiento(id) {
    let id_bien = document.querySelector('#id_bien' + id).value;
    let tipo_movimiento = document.querySelector('#tipo_movimiento' + id).value;
    let id_dependencia_origen = document.querySelector('#id_dependencia_origen' + id).value;
    let id_dependencia_destino = document.querySelector('#id_dependencia_destino' + id).value;
    let motivo = document.querySelector('#motivo' + id).value;
    let observaciones = document.querySelector('#observaciones' + id).value;
    let documento_referencia = document.querySelector('#documento_referencia' + id).value;
    let usuario_solicita = document.querySelector('#usuario_solicita' + id).value;

    if (id_bien == "" || tipo_movimiento == "" || motivo == "") {
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
        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=actualizar', {
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
            listar_movimientos_ordenados();
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
        console.log("Error al actualizar movimiento: " + e);
    }
}

async function ejecutarMovimiento(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas ejecutar este movimiento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, ejecutar',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('id_movimiento', id);
                formData.append('usuario_autoriza', session_usuario);
                formData.append('sesion', session_session);
                formData.append('token', token_token);

                let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=ejecutar', {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    body: formData
                });

                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito',
                        text: json.mensaje,
                        confirmButtonClass: 'btn btn-confirm mt-2',
                        timer: 1000
                    });
                    listar_movimientos_ordenados();
                } else if (json.msg == "Error_Sesion") {
                    alerta_sesion();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: json.mensaje,
                        confirmButtonClass: 'btn btn-confirm mt-2',
                        timer: 1000
                    });
                }
            } catch (e) {
                console.log("Error al ejecutar movimiento: " + e);
            }
        }
    });
}

async function cancelarMovimiento(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas cancelar este movimiento?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cancelar',
        cancelButtonText: 'Cancelar'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const formData = new FormData();
                formData.append('id_movimiento', id);
                formData.append('usuario_autoriza', session_usuario);
                formData.append('sesion', session_session);
                formData.append('token', token_token);

                let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=cancelar', {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    body: formData
                });

                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire({
                        type: 'success',
                        title: 'Éxito',
                        text: json.mensaje,
                        confirmButtonClass: 'btn btn-confirm mt-2',
                        timer: 1000
                    });
                    listar_movimientos_ordenados();
                } else if (json.msg == "Error_Sesion") {
                    alerta_sesion();
                } else {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: json.mensaje,
                        confirmButtonClass: 'btn btn-confirm mt-2',
                        timer: 1000
                    });
                }
            } catch (e) {
                console.log("Error al cancelar movimiento: " + e);
            }
        }
    });
}
async function cargarDatosRegistro() {
    try {
        const formData = new FormData();
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=datos_registro', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        let json = await respuesta.json();

        if (json.status) {
            // Cargar bienes
            let selectBien = document.getElementById('id_bien');
            selectBien.innerHTML = '<option value="">Seleccione un bien</option>';
            json.bienes.forEach(bien => {
                let option = document.createElement('option');
                option.value = bien.id_bien;
                option.textContent = `${bien.nombre_bien} (${bien.codigo_patrimonial})`;
                selectBien.appendChild(option);
            });

            // Cargar dependencias
            let selectOrigen = document.getElementById('id_dependencia_origen');
            let selectDestino = document.getElementById('id_dependencia_destino');

            selectOrigen.innerHTML = '<option value="">Seleccione una dependencia</option>';
            selectDestino.innerHTML = '<option value="">Seleccione una dependencia</option>';

            json.dependencias.forEach(dependencia => {
                let optionOrigen = document.createElement('option');
                optionOrigen.value = dependencia.id_dependencia;
                optionOrigen.textContent = dependencia.nombre_dependencia;

                let optionDestino = document.createElement('option');
                optionDestino.value = dependencia.id_dependencia;
                optionDestino.textContent = dependencia.nombre_dependencia;

                selectOrigen.appendChild(optionOrigen);
                selectDestino.appendChild(optionDestino);
            });
        }
    } catch (e) {
        console.error("Error al cargar datos para el registro: ", e);
    }
}

async function registrarMovimiento() {
    let id_bien = document.querySelector('#id_bien').value;
    let tipo_movimiento = document.querySelector('#tipo_movimiento').value;
    let id_dependencia_origen = document.querySelector('#id_dependencia_origen').value;
    let id_dependencia_destino = document.querySelector('#id_dependencia_destino').value;
    let motivo = document.querySelector('#motivo').value;
    let observaciones = document.querySelector('#observaciones').value;
    let documento_referencia = document.querySelector('#documento_referencia').value;
    let usuario_solicita = document.querySelector('#usuario_solicita').value;

    if (id_bien == "" || tipo_movimiento == "" || motivo == "") {
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
        datos.append('estado_movimiento', 'PENDIENTE');

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=registrar', {
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
                window.location.href = base_url + 'movimientos';
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
        console.log("Error al registrar movimiento: " + e);
    }
}