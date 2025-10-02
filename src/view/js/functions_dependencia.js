function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_dependencias_ordenadas();
}

async function listar_dependencias_ordenadas() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_codigo = document.getElementById('busqueda_tabla_codigo').value;
        let busqueda_tabla_dependencia = document.getElementById('busqueda_tabla_dependencia').value;

        document.getElementById('filtro_codigo').value = busqueda_tabla_codigo;
        document.getElementById('filtro_dependencia').value = busqueda_tabla_dependencia;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_codigo', busqueda_tabla_codigo);
        formData.append('busqueda_tabla_dependencia', busqueda_tabla_dependencia);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Ambiente.php?tipo=listar_dependencias_ordenadas_tabla', {
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
                        No hay dependencias disponibles.
                    </div>
                `;
            } else {
                document.getElementById('tablas').innerHTML = `
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Responsable</th>
                                <th>Código</th>
                                <th>Dependencia</th>
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
        console.error("Error al cargar dependencias: ", e);
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
        <td>${item.encargado}</td>
        <td>${item.codigo}</td>
        <td>${item.detalle}</td>
        <td>${item.options}</td>
    `;

    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar datos de la dependencia</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="encargado${item.id}" class="col-3 col-form-label">Responsable:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="encargado${item.id}" name="encargado" value="${item.encargado}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="codigo${item.id}" class="col-3 col-form-label">Código:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="codigo${item.id}" name="codigo" value="${item.codigo}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="detalle${item.id}" class="col-3 col-form-label">Nombre:</label>
                                    <div class="col-9">
                                        <textarea name="detalle" id="detalle${item.id}" class="form-control">${item.detalle}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="otros_detalle${item.id}" class="col-3 col-form-label">Descripción:</label>
                                    <div class="col-9">
                                        <textarea name="otros_detalle" id="otros_detalle${item.id}" class="form-control" rows="5">${item.otros_detalle}</textarea>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarDependencia(${item.id})">Actualizar</button>
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
    // Inicializar el modal después de agregarlo al DOM
    $('.modal_editar' + item.id).modal({
        backdrop: 'static',
        keyboard: true,
        show: false
    });
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

async function actualizarDependencia(id) {
    let encargado = document.querySelector('#encargado' + id).value;
    let codigo = document.querySelector('#codigo' + id).value;
    let detalle = document.querySelector('#detalle' + id).value;
    let otros_detalle = document.querySelector('#otros_detalle' + id).value;

    if (encargado == "" || codigo == "" || detalle == "" || otros_detalle == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos vacíos...',
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
        let respuesta = await fetch(base_url_server + 'src/control/Ambiente.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            // Cerrar el modal
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
            listar_dependencias_ordenadas();
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
        console.log("Error al actualizar dependencia: " + e);
    }
}

async function registrarDependencia() {
    let encargado = document.querySelector('#encargado').value;
    let codigo = document.querySelector('#codigo').value;
    let detalle = document.querySelector('#detalle').value;
    let otros_detalle = document.querySelector('#otros_detalle').value;

    if (encargado == "" || codigo == "" || detalle == "" || otros_detalle == "") {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Campos vacíos...',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        });
        return;
    }

    try {
        const datos = new FormData(document.getElementById('frmRegistrar'));
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Ambiente.php?tipo=registrar', {
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
                window.location.href = base_url + 'ambientes';
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
        console.log("Error al registrar dependencia: " + e);
    }
}


