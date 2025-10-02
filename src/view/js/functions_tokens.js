async function cargarClientesEnSelect() {
    try {
        const formData = new FormData();
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/ClienteApi.php?tipo=listar_clientes_select', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });
        let json = await respuesta.json();
        if (json.status) {
            let selectCliente = document.getElementById('id_client_api');
            let selectBusquedaCliente = document.getElementById('busqueda_tabla_cliente');
            if (selectCliente) {
                selectCliente.innerHTML = '<option value="">Seleccione un cliente</option>';
                json.contenido.forEach(cliente => {
                    selectCliente.innerHTML += `<option value="${cliente.id}">${cliente.razon_social}</option>`;
                });
            }
            if (selectBusquedaCliente) {
                selectBusquedaCliente.innerHTML = '<option value="">TODOS</option>';
                json.contenido.forEach(cliente => {
                    selectBusquedaCliente.innerHTML += `<option value="${cliente.id}">${cliente.razon_social}</option>`;
                });
            }
        }
    } catch (e) {
        console.log("Error al cargar clientes: " + e);
    }
}

function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_tokens_ordenados();
}

async function listar_tokens_ordenados() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_token = document.getElementById('busqueda_tabla_token').value;
        let busqueda_tabla_cliente = document.getElementById('busqueda_tabla_cliente').value;
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value;
        document.getElementById('filtro_token').value = busqueda_tabla_token;
        document.getElementById('filtro_cliente').value = busqueda_tabla_cliente;
        document.getElementById('filtro_estado').value = busqueda_tabla_estado;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_token', busqueda_tabla_token);
        formData.append('busqueda_tabla_cliente', busqueda_tabla_cliente);
        formData.append('busqueda_tabla_estado', busqueda_tabla_estado);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Tokens.php?tipo=listar_tokens_ordenados_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });
        let json = await respuesta.json();
        document.getElementById('tablas').innerHTML = `
            <table class="table dt-responsive" width="100%">
                <thead>
                    <tr>
                        <th>Nro</th>
                        <th>Token</th>
                        <th>Cliente</th>
                        <th>Fecha Registro</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="contenido_tabla"></tbody>
            </table>
        `;
        document.querySelector('#modals_editar').innerHTML = ``;

        if (json.status) {
            let datos = json.contenido;
            datos.forEach(item => {
                generarfilastabla(item);
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            document.getElementById('tablas').innerHTML = `No se encontraron resultados`;
        }

        let paginacion = generar_paginacion(json.total, cantidad_mostrar);
        let texto_paginacion = generar_texto_paginacion(json.total, cantidad_mostrar);
        document.getElementById('texto_paginacion_tabla').innerHTML = texto_paginacion;
        document.getElementById('lista_paginacion_tabla').innerHTML = paginacion;
    } catch (e) {
        console.log("Error al cargar tokens: " + e);
    } finally {
        ocultarPopupCarga();
    }
}

function generarfilastabla(item) {
    let cont = 1;
    $(".filas_tabla").each(function () {
        cont++;
    });
    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";
    let estado = item.estado == 1 ? "ACTIVO" : "INACTIVO";
    nueva_fila.innerHTML = `
    <th>${cont}</th>
    <td>${item.token}</td>
    <td>${item.razon_social}</td>
    <td>${item.fecha_registro}</td>
    <td>${estado}</td>
    <td>
        <button class="btn btn-info btn-sm" data-toggle="modal" data-target=".modal_editar${item.id}">Editar</button>
    </td>
`;

    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar Token</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="id_client_api${item.id}" class="col-3 col-form-label">Cliente</label>
                                    <div class="col-9">
                                        <select class="form-control" id="id_client_api${item.id}" name="id_client_api" required>
                                            <option value="">Seleccione un cliente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="token${item.id}" class="col-3 col-form-label">Token</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="token${item.id}" name="token" value="${item.token}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="estado${item.id}" class="col-3 col-form-label">Estado</label>
                                    <div class="col-9">
                                        <select class="form-control" id="estado${item.id}" name="estado">
                                            <option value="1" ${item.estado == 1 ? 'selected' : ''}>ACTIVO</option>
                                            <option value="0" ${item.estado == 0 ? 'selected' : ''}>INACTIVO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarToken(${item.id})">Actualizar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    // Cargar clientes en el modal de edición
    let selectModal = document.getElementById(`id_client_api${item.id}`);
    json.contenido.forEach(cliente => {
        let selected = (cliente.id == item.id_client_api) ? 'selected' : '';
        selectModal.innerHTML += `<option value="${cliente.id}" ${selected}>${cliente.razon_social}</option>`;
    });
    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
}

async function registrar_token() {
    let id_client_api = document.getElementById('id_client_api').value;
    let estado = document.getElementById('estado').value;

    if (id_client_api == "" || estado == "") {
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
        const datos = new FormData(frmRegistrar);
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Tokens.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
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
        console.log("Oops, ocurrió un error: " + e);
    }
}

async function actualizarToken(id) {
    let id_client_api = document.getElementById(`id_client_api${id}`).value;
    let token = document.getElementById(`token${id}`).value;
    let estado = document.getElementById(`estado${id}`).value;

    if (id_client_api == "" || token == "" || estado == "") {
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

    const formulario = document.getElementById(`frmActualizar${id}`);
    const datos = new FormData(formulario);
    datos.append('data', id);
    datos.append('sesion', session_session);
    datos.append('token', token_token);

    try {
        let respuesta = await fetch(base_url_server + 'src/control/Tokens.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (json.status) {
            $(`.modal_editar${id}`).modal('hide');
            Swal.fire({
                type: 'success',
                title: 'Actualizar',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_tokens_ordenados();
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
        console.log("Error al actualizar token: " + e);
    }
}
