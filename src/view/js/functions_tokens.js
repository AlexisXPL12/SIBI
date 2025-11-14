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
            let selectBusquedaCliente = document.getElementById('busqueda_tabla_cliente');
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
        console.log("=== Iniciando carga de tokens ===");
        mostrarPopupCarga();
        
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_cliente = document.getElementById('busqueda_tabla_cliente').value;
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value;

        console.log("Parámetros:", {pagina, cantidad_mostrar, busqueda_tabla_cliente, busqueda_tabla_estado});

        // Guardar valores de filtro
        document.getElementById('filtro_cliente').value = busqueda_tabla_cliente;
        document.getElementById('filtro_estado').value = busqueda_tabla_estado;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
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

        console.log("Respuesta HTTP:", respuesta.status);

        let json = await respuesta.json();
        console.log("JSON recibido:", json);

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

        if (json.status) {
            let datos = json.contenido;
            datos.forEach(item => {
                generarfilastabla(item);
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            document.getElementById('tablas').innerHTML = `
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> No se encontraron resultados
                </div>
            `;
        }

        let paginacion = generar_paginacion(json.total, cantidad_mostrar);
        let texto_paginacion = generar_texto_paginacion(json.total, cantidad_mostrar);
        document.getElementById('texto_paginacion_tabla').innerHTML = texto_paginacion;
        document.getElementById('lista_paginacion_tabla').innerHTML = paginacion;

    } catch (e) {
        console.error("Error completo:", e);
    } finally {
        ocultarPopupCarga();
    }
}

function generarfilastabla(item) {
    let cont = 1;
    $(".filas_tabla").each(function () {
        cont++;
    });

    let estado;
    if (item.estado == 1) {
        estado = '<span class="badge badge-success">ACTIVO</span>';
    } else {
        estado = '<span class="badge badge-secondary">INACTIVO</span>';
    }

    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";
    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td>${item.token}</td>
        <td>${item.razon_social}</td>
        <td>${item.fecha_registro}</td>
        <td>${estado}</td>
        <td>${item.options}</td>
    `;

    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
}
// ============================================
// FUNCIÓN AUXILIAR PARA CARGAR CLIENTES
// ============================================
async function cargarClientesParaModal() {
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
            let options = '<option value="">Seleccione un cliente</option>';
            json.contenido.forEach(cliente => {
                options += `<option value="${cliente.id}">${cliente.razon_social}</option>`;
            });
            return options;
        }
        return '<option value="">Error al cargar clientes</option>';
    } catch (e) {
        console.log("Error al cargar clientes: " + e);
        return '<option value="">Error al cargar clientes</option>';
    }
}
// ============================================
// FUNCIÓN PARA ABRIR MODAL DE EDICIÓN CON SWEETALERT
// ============================================
async function abrirModalEditarToken(id, id_client_api, token, estado) {
    // Primero cargar los clientes
    let clientesOptions = await cargarClientesParaModal();
    
    Swal.fire({
        title: 'Actualizar Token',
        html: `
            <div class="form-group text-left mb-3">
                <label for="swal-cliente">Cliente:</label>
                <select id="swal-cliente" class="form-control">
                    ${clientesOptions}
                </select>
            </div>
            <div class="form-group text-left mb-3">
                <label for="swal-token">Token:</label>
                <input type="text" id="swal-token" class="form-control" value="${token}" readonly>
            </div>
            <div class="form-group text-left mb-3">
                <label for="swal-estado">Estado:</label>
                <select id="swal-estado" class="form-control">
                    <option value="">Seleccione...</option>
                    <option value="1" ${estado == 1 ? 'selected' : ''}>ACTIVO</option>
                    <option value="0" ${estado == 0 ? 'selected' : ''}>INACTIVO</option>
                </select>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: 'Actualizar',
        cancelButtonText: 'Cancelar',
        customClass: {
            confirmButton: 'btn btn-success mt-2',
            cancelButton: 'btn btn-secondary mt-2'
        },
        buttonsStyling: false,
        focusConfirm: false,
        didOpen: () => {
            // Establecer el cliente seleccionado después de que se abra el modal
            document.getElementById('swal-cliente').value = id_client_api;
        },
        preConfirm: () => {
            const clienteVal = document.getElementById('swal-cliente').value;
            const tokenVal = document.getElementById('swal-token').value.trim();
            const estadoVal = document.getElementById('swal-estado').value;
            
            if (!clienteVal || !tokenVal || estadoVal === "") {
                Swal.showValidationMessage('Por favor complete todos los campos');
                return false;
            }
            
            return {
                id_client_api: clienteVal,
                token: tokenVal,
                estado: estadoVal
            };
        }
    }).then((result) => {
        if (result.value) {
            actualizarToken(id, result.value);
        }
    });
}

// ============================================
// FUNCIÓN PARA ACTUALIZAR TOKEN
// ============================================
async function actualizarToken(id, datos) {
    if (!datos) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Datos incompletos',
            customClass: {
                confirmButton: 'btn btn-danger mt-2'
            }
        });
        return;
    }

    // Mostrar loading
    Swal.fire({
        title: 'Actualizando...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const formData = new FormData();
    formData.append('data', id);
    formData.append('id_client_api', datos.id_client_api);
    formData.append('token', datos.token);  // Token del registro
    formData.append('estado', datos.estado);
    formData.append('sesion', session_session);
    formData.append('token_sesion', token_token);  // ⭐ CAMBIAR NOMBRE AQUÍ

    try {
        let respuesta = await fetch(base_url_server + 'src/control/Tokens.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        const textoRespuesta = await respuesta.text();
        let json;
        
        try {
            json = JSON.parse(textoRespuesta);
        } catch (e) {
            console.error("Error al parsear JSON:", textoRespuesta);
            Swal.fire({
                icon: 'error',
                title: 'Error del Servidor',
                text: 'La respuesta del servidor no es válida',
                customClass: {
                    confirmButton: 'btn btn-danger mt-2'
                }
            });
            return;
        }

        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: json.mensaje || 'Token actualizado correctamente',
                customClass: {
                    confirmButton: 'btn btn-success mt-2'
                },
                timer: 2000
            }).then(() => {
                listar_tokens_ordenados();
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje || 'Error al actualizar el token',
                customClass: {
                    confirmButton: 'btn btn-danger mt-2'
                }
            });
        }
    } catch (e) {
        console.error("Error en la petición:", e);
        Swal.fire({
            icon: 'error',
            title: 'Error de Conexión',
            text: 'No se pudo conectar con el servidor',
            customClass: {
                confirmButton: 'btn btn-danger mt-2'
            }
        });
    }
}

async function registrar_token() {
    let id_client_api = document.getElementById('id_client_api').value;
    let estado = document.getElementById('estado').value;

    if (id_client_api == "" || estado == "") {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Campos vacíos...',
            customClass: {
                confirmButton: 'btn btn-confirm mt-2'
            }
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
                icon: 'success',
                title: 'Registro',
                text: json.mensaje,
                customClass: {
                    confirmButton: 'btn btn-confirm mt-2'
                },
                timer: 1000
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje,
                customClass: {
                    confirmButton: 'btn btn-confirm mt-2'
                },
                timer: 1000
            });
        }
    } catch (e) {
        console.log("Oops, ocurrió un error: " + e);
    }
}

