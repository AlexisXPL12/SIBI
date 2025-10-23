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

        // Eliminamos las líneas que buscan los elementos eliminados
        // let busqueda_bien = document.getElementById('busqueda_bien').value;
        // let busqueda_dependencia = document.getElementById('busqueda_dependencia').value;

        // Asignamos valores vacíos para los filtros eliminados
        let busqueda_bien = '';
        let busqueda_dependencia = '';

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
        <td>
            <div class="btn-group" role="group">
                <button type="button" title="Editar" class="btn btn-primary btn-sm waves-effect waves-light" data-toggle="modal" data-target=".modal_editar${item.id}">
                    <i class="fa fa-edit"></i>
                </button>
                ${item.estado_movimiento === 'PENDIENTE' ?
                    `<button type="button" title="Ejecutar" class="btn btn-success btn-sm waves-effect waves-light" onclick="ejecutarMovimiento(${item.id})">
                        <i class="fa fa-check"></i>
                    </button>
                    <button type="button" title="Cancelar" class="btn btn-danger btn-sm waves-effect waves-light" onclick="cancelarMovimiento(${item.id})">
                        <i class="fa fa-times"></i>
                    </button>` : ''}
            </div>
        </td>
    `;
    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
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

            // Cargar dependencias destino
            let selectDestino = document.getElementById('id_dependencia_destino');
            selectDestino.innerHTML = '<option value="">Seleccione una dependencia</option>';
            json.dependencias.forEach(dependencia => {
                let optionDestino = document.createElement('option');
                optionDestino.value = dependencia.id_dependencia;
                optionDestino.textContent = dependencia.nombre_dependencia;
                selectDestino.appendChild(optionDestino);
            });

            // Cargar dependencia de origen del usuario
            let selectOrigen = document.getElementById('id_dependencia_origen');
            selectOrigen.innerHTML = '';
            if (json.usuario_dependencia_id && json.usuario_dependencia_nombre) {
                let optionOrigen = document.createElement('option');
                optionOrigen.value = json.usuario_dependencia_id;
                optionOrigen.textContent = json.usuario_dependencia_nombre;
                optionOrigen.selected = true;
                selectOrigen.appendChild(optionOrigen);
            }
            selectOrigen.disabled = true;
        }
    } catch (e) {
        console.error("Error al cargar datos para el registro: ", e);
    }
}

// Asegúrate de que esta función esté definida antes de ser utilizada
async function cargarDependenciaDelBien(idBien) {
    if (!idBien) {
        document.getElementById('id_dependencia_origen').innerHTML = '<option value="">Seleccione una dependencia</option>';
        return;
    }
    try {
        const formData = new FormData();
        formData.append('id_bien', idBien);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=obtener_dependencia_bien', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        let json = await respuesta.json();
        if (json.status && json.dependencia_id && json.dependencia_nombre) {
            let selectOrigen = document.getElementById('id_dependencia_origen');
            selectOrigen.innerHTML = ''; // Limpiar opciones anteriores
            let optionOrigen = document.createElement('option');
            optionOrigen.value = json.dependencia_id;
            optionOrigen.textContent = json.dependencia_nombre;
            optionOrigen.selected = true;
            selectOrigen.appendChild(optionOrigen);
        } else {
            console.error("Error al cargar la dependencia del bien: ", json.msg);
            let selectOrigen = document.getElementById('id_dependencia_origen');
            selectOrigen.innerHTML = '<option value="">Dependencia no encontrada</option>';
        }
    } catch (e) {
        console.error("Error al cargar la dependencia del bien: ", e);
        let selectOrigen = document.getElementById('id_dependencia_origen');
        selectOrigen.innerHTML = '<option value="">Error al cargar</option>';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    cargarDatosRegistro();

    // Asegúrate de que el elemento id_bien exista antes de agregar el evento
    let selectBien = document.getElementById('id_bien');
    if (selectBien) {
        selectBien.addEventListener('change', function () {
            cargarDependenciaDelBien(this.value);
        });
    } else {
        console.error("Elemento id_bien no encontrado");
    }
});

async function registrarMovimiento() {
    let id_bien = document.querySelector('#id_bien').value;
    let tipo_movimiento = document.querySelector('#tipo_movimiento').value;
    let id_dependencia_origen = document.querySelector('#id_dependencia_origen').value;
    let id_dependencia_destino = document.querySelector('#id_dependencia_destino').value;
    let motivo = document.querySelector('#motivo').value;
    let observaciones = document.querySelector('#observaciones').value;
    let documento_referencia = document.querySelector('#documento_referencia').value;

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
        document.getElementById('id_dependencia_origen').disabled = false;

        const datos = new FormData(document.getElementById('frmRegistrar'));
        datos.append('usuario_solicita', session_usuario);
        datos.append('sesion', session_session);
        datos.append('token', token_token);
        datos.append('usuario_solicita', session_usuario);
        datos.append('estado_movimiento', 'PENDIENTE');

        let respuesta = await fetch(base_url_server + 'src/control/Movimiento.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        document.getElementById('id_dependencia_origen').disabled = true;

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
