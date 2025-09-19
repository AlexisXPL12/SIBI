function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_clientes_api_ordenados();
}

async function listar_clientes_api_ordenados() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_ruc = document.getElementById('busqueda_tabla_ruc').value;
        let busqueda_tabla_razon_social = document.getElementById('busqueda_tabla_razon_social').value;
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value;

        document.getElementById('filtro_ruc').value = busqueda_tabla_ruc;
        document.getElementById('filtro_razon_social').value = busqueda_tabla_razon_social;
        document.getElementById('filtro_estado').value = busqueda_tabla_estado;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_tabla_ruc', busqueda_tabla_ruc);
        formData.append('busqueda_tabla_razon_social', busqueda_tabla_razon_social);
        formData.append('busqueda_tabla_estado', busqueda_tabla_estado);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/ClienteApi.php?tipo=listar_clientes_api_ordenados_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });
        let json = await respuesta.json();
        document.getElementById('tablas').innerHTML = `<table id="" class="table dt-responsive" width="100%">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>RUC</th>
                    <th>Razón Social</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="contenido_tabla"></tbody>
        </table>`;
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
        console.log("Error al cargar clientes API: " + e);
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
        <td>${item.ruc}</td>
        <td>${item.razon_social}</td>
        <td>${item.telefono}</td>
        <td>${item.correo}</td>
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
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar Cliente API</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="ruc${item.id}" class="col-3 col-form-label">RUC</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="ruc${item.id}" name="ruc" value="${item.ruc}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="razon_social${item.id}" class="col-3 col-form-label">Razón Social</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="razon_social${item.id}" name="razon_social" value="${item.razon_social}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="telefono${item.id}" class="col-3 col-form-label">Teléfono</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="telefono${item.id}" name="telefono" value="${item.telefono}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="correo${item.id}" class="col-3 col-form-label">Correo</label>
                                    <div class="col-9">
                                        <input type="email" class="form-control" id="correo${item.id}" name="correo" value="${item.correo}">
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
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarClienteApi(${item.id})">Actualizar</button>
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

async function registrar_cliente_api() {
    let ruc = document.getElementById('ruc').value;
    let razon_social = document.querySelector('#razon_social').value;
    let telefono = document.querySelector('#telefono').value;
    let correo = document.querySelector('#correo').value;
    let estado = document.querySelector('#estado').value;

    if (ruc == "" || razon_social == "" || telefono == "" || correo == "" || estado == "") {
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

        let respuesta = await fetch(base_url_server + 'src/control/ClienteApi.php?tipo=registrar', {
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

async function actualizarClienteApi(id) {
    let ruc = document.getElementById('ruc' + id).value;
    let razon_social = document.querySelector('#razon_social' + id).value;
    let telefono = document.querySelector('#telefono' + id).value;
    let correo = document.querySelector('#correo' + id).value;
    let estado = document.querySelector('#estado' + id).value;

    if (ruc == "" || razon_social == "" || telefono == "" || correo == "" || estado == "") {
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
        let respuesta = await fetch(base_url_server + 'src/control/ClienteApi.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (json.status) {
            $('.modal_editar' + id).modal('hide');
            Swal.fire({
                type: 'success',
                title: 'Actualizar',
                text: json.mensaje,
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
            listar_clientes_api_ordenados();
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
        console.log("Error al actualizar cliente API: " + e);
    }
}
