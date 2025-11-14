function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_clientes_api_ordenados();
}
// ============================================
// FUNCIÓN PARA LISTAR CLIENTES API
// ============================================
async function listar_clientes_api_ordenados() {
    try {
        console.log("=== Iniciando carga de clientes API ===");
        mostrarPopupCarga();
        
        // Obtener filtros
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_tabla_ruc = document.getElementById('busqueda_tabla_ruc').value;
        let busqueda_tabla_razon_social = document.getElementById('busqueda_tabla_razon_social').value;
        let busqueda_tabla_estado = document.getElementById('busqueda_tabla_estado').value;
        
        console.log("Parámetros:", {pagina, cantidad_mostrar, busqueda_tabla_ruc, busqueda_tabla_razon_social, busqueda_tabla_estado});
        
        // Guardar valores de filtro
        document.getElementById('filtro_ruc').value = busqueda_tabla_ruc;
        document.getElementById('filtro_razon_social').value = busqueda_tabla_razon_social;
        document.getElementById('filtro_estado').value = busqueda_tabla_estado;

        // Generar FormData - ESTA PARTE FALTABA
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

        console.log("Respuesta HTTP:", respuesta.status);

        let json = await respuesta.json();
        console.log("JSON recibido:", json);
        
        document.getElementById('tablas').innerHTML = `
            <table id="" class="table dt-responsive" width="100%">
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
                <tbody id="contenido_tabla">
                </tbody>
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

// ============================================
// FUNCIÓN PARA GENERAR FILAS
// ============================================
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
        <td>${item.ruc}</td>
        <td>${item.razon_social}</td>
        <td>${item.telefono}</td>
        <td>${item.correo}</td>
        <td>${estado}</td>
        <td>${item.options}</td>
    `;

    document.querySelector('#contenido_tabla').appendChild(nueva_fila);
}

// ============================================
// FUNCIÓN PARA ABRIR MODAL DE EDICIÓN
// ============================================
function abrirModalEditarClienteApi(id, ruc, razon_social, telefono, correo, estado) {
    Swal.fire({
        title: 'Actualizar Cliente API',
        html: `
            <div class="form-group text-left mb-3">
                <label for="swal-ruc">RUC:</label>
                <input type="text" id="swal-ruc" class="form-control" value="${ruc}">
            </div>
            <div class="form-group text-left mb-3">
                <label for="swal-razon-social">Razón Social:</label>
                <input type="text" id="swal-razon-social" class="form-control" value="${razon_social}">
            </div>
            <div class="form-group text-left mb-3">
                <label for="swal-telefono">Teléfono:</label>
                <input type="text" id="swal-telefono" class="form-control" value="${telefono}">
            </div>
            <div class="form-group text-left mb-3">
                <label for="swal-correo">Correo Electrónico:</label>
                <input type="email" id="swal-correo" class="form-control" value="${correo}">
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
        preConfirm: () => {
            const rucVal = document.getElementById('swal-ruc').value.trim();
            const razonSocialVal = document.getElementById('swal-razon-social').value.trim();
            const telefonoVal = document.getElementById('swal-telefono').value.trim();
            const correoVal = document.getElementById('swal-correo').value.trim();
            const estadoVal = document.getElementById('swal-estado').value;
            
            if (!rucVal || !razonSocialVal || !telefonoVal || !correoVal || !estadoVal) {
                Swal.showValidationMessage('Por favor complete todos los campos');
                return false;
            }
            
            return {
                ruc: rucVal,
                razon_social: razonSocialVal,
                telefono: telefonoVal,
                correo: correoVal,
                estado: estadoVal
            };
        }
    }).then((result) => {
        if (result.value !== undefined && result.value !== null) {
            actualizarClienteApi(id, result.value);
        } else if (result.isConfirmed) {
            actualizarClienteApi(id, result.value);
        }
    });
}

// ============================================
// FUNCIÓN PARA ACTUALIZAR CLIENTE API
// ============================================
async function actualizarClienteApi(id, datos) {
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
    formData.append('ruc', datos.ruc);
    formData.append('razon_social', datos.razon_social);
    formData.append('telefono', datos.telefono);
    formData.append('correo', datos.correo);
    formData.append('estado', datos.estado);
    formData.append('sesion', session_session);
    formData.append('token', token_token);

    try {
        let respuesta = await fetch(base_url_server + 'src/control/ClienteApi.php?tipo=actualizar', {
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
                text: json.mensaje || 'Cliente API actualizado correctamente',
                customClass: {
                    confirmButton: 'btn btn-success mt-2'
                },
                timer: 2000
            }).then(() => {
                listar_clientes_api_ordenados();
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje || 'Error al actualizar el cliente API',
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
