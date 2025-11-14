function numero_pagina(pagina) {
    document.getElementById('pagina').value = pagina;
    listar_bienes_ordenados();
}

// ============================================
// LISTAR BIENES
// ============================================
async function listar_bienes_ordenados() {
    try {
        mostrarPopupCarga();
        let pagina = document.getElementById('pagina').value;
        let cantidad_mostrar = document.getElementById('cantidad_mostrar').value;
        let busqueda_codigo_patrimonial = document.getElementById('busqueda_codigo_patrimonial').value;
        let busqueda_nombre_bien = document.getElementById('busqueda_nombre_bien').value;

        document.getElementById('filtro_codigo_patrimonial').value = busqueda_codigo_patrimonial;
        document.getElementById('filtro_nombre_bien').value = busqueda_nombre_bien;

        const formData = new FormData();
        formData.append('pagina', pagina);
        formData.append('cantidad_mostrar', cantidad_mostrar);
        formData.append('busqueda_codigo_patrimonial', busqueda_codigo_patrimonial);
        formData.append('busqueda_nombre_bien', busqueda_nombre_bien);
        formData.append('sesion', session_session);
        formData.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Bien.php?tipo=listar_bienes_ordenados_tabla', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        if (!respuesta.ok) {
            throw new Error(`Error en la solicitud: ${respuesta.status} ${respuesta.statusText}`);
        }
        
        let json = await respuesta.json();

        if (json.status) {
            let datos = json.contenido;
            if (datos.length === 0) {
                document.getElementById('tablas').innerHTML = `
                    <div class="alert alert-info text-center">
                        No hay bienes disponibles.
                    </div>
                `;
            } else {
                document.getElementById('tablas').innerHTML = `
                    <table class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Código Patrimonial</th>
                                <th>Nombre del Bien</th>
                                <th>Categoría</th>
                                <th>Dependencia</th>
                                <th>Marca/Modelo</th>
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
            document.querySelector('#modals_editar').innerHTML = ``;
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
        console.error("Error al cargar bienes: ", e);
        document.getElementById('tablas').innerHTML = `
            <div class="alert alert-danger text-center">
                Error al cargar los datos: ${e.message}
            </div>
        `;
    } finally {
        ocultarPopupCarga();
    }
}

// ============================================
// GENERAR FILA (CON SWEETALERT)
// ============================================
function generarFilaTabla(item) {
    let cont = 1;
    $(".filas_tabla").each(function () {
        cont++;
    });

    // Badge de estado
    let badgeEstado = {
        'ACTIVO': '<span class="badge badge-success">ACTIVO</span>',
        'BAJA': '<span class="badge badge-danger">BAJA</span>',
        'MANTENIMIENTO': '<span class="badge badge-warning">MANTENIMIENTO</span>',
        'PRESTADO': '<span class="badge badge-info">PRESTADO</span>'
    }[item.estado_bien] || '<span class="badge badge-secondary">N/A</span>';

    // Marca/Modelo
    let marcaModelo = '';
    if (item.marca && item.modelo) {
        marcaModelo = `${item.marca} - ${item.modelo}`;
    } else if (item.marca) {
        marcaModelo = item.marca;
    } else if (item.modelo) {
        marcaModelo = item.modelo;
    } else {
        marcaModelo = '<span class="text-muted">N/A</span>';
    }

    // Escapar comillas para JavaScript
    const escaparComillas = (str) => {
        if (!str) return '';
        return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
    };

    let nueva_fila = document.createElement("tr");
    nueva_fila.id = "fila" + item.id;
    nueva_fila.className = "filas_tabla";

    nueva_fila.innerHTML = `
        <th>${cont}</th>
        <td>${item.codigo_patrimonial}</td>
        <td>${item.nombre_bien}</td>
        <td>${item.nombre_categoria || '<span class="text-muted">N/A</span>'}</td>
        <td>${item.nombre_dependencia || '<span class="text-muted">N/A</span>'}</td>
        <td>${marcaModelo}</td>
        <td>${badgeEstado}</td>
        <td>
            <button 
                type="button" 
                title="Editar" 
                class="btn btn-primary btn-sm waves-effect waves-light" 
                onclick="abrirModalEditarBien(
                    '${item.id}',
                    '${escaparComillas(item.codigo_patrimonial)}',
                    '${escaparComillas(item.nombre_bien)}',
                    '${escaparComillas(item.descripcion)}',
                    '${escaparComillas(item.marca)}',
                    '${escaparComillas(item.modelo)}',
                    '${escaparComillas(item.serie)}',
                    '${item.estado_bien}',
                    '${item.condicion_bien}',
                    '${escaparComillas(item.nombre_categoria)}',
                    '${escaparComillas(item.nombre_dependencia)}'
                )">
                <i class="fa fa-edit"></i>
            </button>
        </td>
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

// ============================================
// FUNCIÓN PARA ABRIR MODAL DE EDICIÓN CON SWEETALERT
// ============================================
function abrirModalEditarBien(id, codigo_patrimonial, nombre_bien, descripcion, marca, modelo, serie, estado_bien, condicion_bien, nombre_categoria, nombre_dependencia) {
    Swal.fire({
        title: '<i class="fa fa-edit"></i> Actualizar Bien',
        html: `
            <div class="container-fluid">
                <div class="form-group text-left mb-3">
                    <label for="swal-codigo-patrimonial">Código Patrimonial: <span class="text-danger">*</span></label>
                    <input type="text" id="swal-codigo-patrimonial" class="form-control" value="${codigo_patrimonial}">
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-nombre-bien">Nombre del Bien: <span class="text-danger">*</span></label>
                    <input type="text" id="swal-nombre-bien" class="form-control" value="${nombre_bien}">
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-descripcion">Descripción:</label>
                    <textarea id="swal-descripcion" class="form-control" rows="3">${descripcion || ''}</textarea>
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-categoria">Categoría:</label>
                    <input type="text" id="swal-categoria" class="form-control bg-light" value="${nombre_categoria || 'N/A'}" readonly>
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-dependencia">Dependencia:</label>
                    <input type="text" id="swal-dependencia" class="form-control bg-light" value="${nombre_dependencia || 'N/A'}" readonly>
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-marca">Marca:</label>
                    <input type="text" id="swal-marca" class="form-control" value="${marca || ''}">
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-modelo">Modelo:</label>
                    <input type="text" id="swal-modelo" class="form-control" value="${modelo || ''}">
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-serie">Serie:</label>
                    <input type="text" id="swal-serie" class="form-control" value="${serie || ''}">
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-estado-bien">Estado: <span class="text-danger">*</span></label>
                    <select id="swal-estado-bien" class="form-control">
                        <option value="ACTIVO" ${estado_bien === 'ACTIVO' ? 'selected' : ''}>ACTIVO</option>
                        <option value="BAJA" ${estado_bien === 'BAJA' ? 'selected' : ''}>BAJA</option>
                        <option value="MANTENIMIENTO" ${estado_bien === 'MANTENIMIENTO' ? 'selected' : ''}>MANTENIMIENTO</option>
                        <option value="PRESTADO" ${estado_bien === 'PRESTADO' ? 'selected' : ''}>PRESTADO</option>
                    </select>
                </div>
                <div class="form-group text-left mb-3">
                    <label for="swal-condicion-bien">Condición: <span class="text-danger">*</span></label>
                    <select id="swal-condicion-bien" class="form-control">
                        <option value="NUEVO" ${condicion_bien === 'NUEVO' ? 'selected' : ''}>NUEVO</option>
                        <option value="BUENO" ${condicion_bien === 'BUENO' ? 'selected' : ''}>BUENO</option>
                        <option value="REGULAR" ${condicion_bien === 'REGULAR' ? 'selected' : ''}>REGULAR</option>
                        <option value="MALO" ${condicion_bien === 'MALO' ? 'selected' : ''}>MALO</option>
                        <option value="INSERVIBLE" ${condicion_bien === 'INSERVIBLE' ? 'selected' : ''}>INSERVIBLE</option>
                    </select>
                </div>
            </div>
        `,
        width: '700px',
        showCancelButton: true,
        confirmButtonText: '<i class="fa fa-save"></i> Actualizar',
        cancelButtonText: '<i class="fa fa-times"></i> Cancelar',
        customClass: {
            confirmButton: 'btn btn-success mt-2',
            cancelButton: 'btn btn-secondary mt-2'
        },
        buttonsStyling: false,
        focusConfirm: false,
        preConfirm: () => {
            const codigoPatrimonialVal = document.getElementById('swal-codigo-patrimonial').value.trim();
            const nombreBienVal = document.getElementById('swal-nombre-bien').value.trim();
            const descripcionVal = document.getElementById('swal-descripcion').value.trim();
            const marcaVal = document.getElementById('swal-marca').value.trim();
            const modeloVal = document.getElementById('swal-modelo').value.trim();
            const serieVal = document.getElementById('swal-serie').value.trim();
            const estadoBienVal = document.getElementById('swal-estado-bien').value;
            const condicionBienVal = document.getElementById('swal-condicion-bien').value;
            
            if (!codigoPatrimonialVal || !nombreBienVal) {
                Swal.showValidationMessage('El código patrimonial y nombre del bien son obligatorios');
                return false;
            }
            
            return {
                codigo_patrimonial: codigoPatrimonialVal,
                nombre_bien: nombreBienVal,
                descripcion: descripcionVal,
                marca: marcaVal,
                modelo: modeloVal,
                serie: serieVal,
                estado_bien: estadoBienVal,
                condicion_bien: condicionBienVal
            };
        }
    }).then((result) => {
        if (result.value) {
            actualizarBien(id, result.value);
        }
    });
}

// ============================================
// FUNCIÓN PARA ACTUALIZAR BIEN
// ============================================
async function actualizarBien(id, datos) {
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
    formData.append('codigo_patrimonial', datos.codigo_patrimonial);
    formData.append('nombre_bien', datos.nombre_bien);
    formData.append('descripcion', datos.descripcion);
    formData.append('marca', datos.marca);
    formData.append('modelo', datos.modelo);
    formData.append('serie', datos.serie);
    formData.append('estado_bien', datos.estado_bien);
    formData.append('condicion_bien', datos.condicion_bien);
    formData.append('sesion', session_session);
    formData.append('token', token_token);

    try {
        let respuesta = await fetch(base_url_server + 'src/control/Bien.php?tipo=actualizar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: formData
        });

        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }

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
                text: json.mensaje || 'Bien actualizado correctamente',
                customClass: {
                    confirmButton: 'btn btn-success mt-2'
                },
                timer: 2000
            }).then(() => {
                listar_bienes_ordenados();
            });
        } else if (json.msg == "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: json.mensaje || 'Error al actualizar el bien',
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
            text: 'No se pudo conectar con el servidor: ' + e.message,
            customClass: {
                confirmButton: 'btn btn-danger mt-2'
            }
        });
    }
}
async function datos_form() {
    try {
        mostrarPopupCarga();
        const datos = new FormData();
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Ambiente.php?tipo=listar_dependencias', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();

        if (json.status) {
            let contenido_select = '<option value="">Seleccione</option>';
            json.contenido.forEach(el => {
                contenido_select += `<option value="${el.id_dependencia}">${el.nombre_dependencia}</option>`;
            });
            document.getElementById('id_dependencia').innerHTML = contenido_select;
        } else {
            console.error("Error al cargar dependencias:", json.msg);
        }
    } catch (e) {
        console.error("Error cargando dependencias: ", e);
    } finally {
        ocultarPopupCarga();
    }
}


function listar_bienes_ingreso() {
    try {
        mostrarPopupCarga();
        document.querySelector('#contenido_bienes_tabla_ingresos').innerHTML = '';
        let cont = 1;
        $(".filas_tabla_bienes").each(function () {
            cont++;
        });
        let index = 0;
        let contador = 0
        lista_bienes_registro.forEach(item => {
            let nueva_fila = document.createElement("tr");
            nueva_fila.id = "fila" + item.id;
            nueva_fila.className = "filas_tabla_bienes";

            nombre_amb = '';

            v_ambientes.forEach(ambiente => {
                if (ambiente.id == item.ambiente) {
                    nombre_amb = ambiente.detalle;
                }
            })
            nueva_fila.innerHTML = `
                            <th>${contador}</th>
                            <td>${item.cod_patrimonial}</td>
                            <td>${item.denominacion}</td>
                            <td>${nombre_amb}</td>
                            <td><button type="button" class="btn btn-danger" onclick="eliminar_bien_ingreso(${index});"><i class="fa fa-trash"></i></button></td>
                `;
            document.querySelector('#contenido_bienes_tabla_ingresos').appendChild(nueva_fila);
            index++;
            contador++;
        });
        //console.log(lista_bienes_registro);

    } catch (error) {
        console.log("ocurrio un error al agregar el bien " + error);
    } finally {
        ocultarPopupCarga();
    }
}
async function cargarCategorias() {
    try {
        const datos = new FormData();
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Categoria.php?tipo=listar_categorias', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();

        if (json.status) {
            let contenido_select = '<option value="">Seleccione</option>';
            json.contenido.forEach(el => {
                contenido_select += `<option value="${el.id_categoria}">${el.nombre_categoria}</option>`;
            });
            document.getElementById('id_categoria').innerHTML = contenido_select;
        } else {
            console.error("Error al cargar categorías:", json.msg);
        }
    } catch (e) {
        console.error("Error cargando categorías: ", e);
    }
}


function listar_bienes_ingreso() {
    try {
        mostrarPopupCarga();
        document.querySelector('#contenido_bienes_tabla_ingresos').innerHTML = '';
        let cont = 1;
        $(".filas_tabla_bienes").each(function () {
            cont++;
        });
        let index = 0;
        let contador = 0
        lista_bienes_registro.forEach(item => {
            let nueva_fila = document.createElement("tr");
            nueva_fila.id = "fila" + item.id;
            nueva_fila.className = "filas_tabla_bienes";

            nombre_amb = '';

            v_ambientes.forEach(ambiente => {
                if (ambiente.id == item.ambiente) {
                    nombre_amb = ambiente.detalle;
                }
            })
            nueva_fila.innerHTML = `
                            <th>${contador}</th>
                            <td>${item.cod_patrimonial}</td>
                            <td>${item.denominacion}</td>
                            <td>${nombre_amb}</td>
                            <td><button type="button" class="btn btn-danger" onclick="eliminar_bien_ingreso(${index});"><i class="fa fa-trash"></i></button></td>
                `;
            document.querySelector('#contenido_bienes_tabla_ingresos').appendChild(nueva_fila);
            index++;
            contador++;
        });
        //console.log(lista_bienes_registro);

    } catch (error) {
        console.log("ocurrio un error al agregar el bien " + error);
    } finally {
        ocultarPopupCarga();
    }
}
function agregar_bien_ingreso() {
    try {
        mostrarPopupCarga();
        let ambiente = document.querySelector('#ambiente').value;
        let cod_patrimonial = document.querySelector('#cod_patrimonial').value;
        let denominacion = document.querySelector('#denominacion').value;
        let marca = document.querySelector('#marca').value;
        let modelo = document.querySelector('#modelo').value;
        let tipo = document.querySelector('#tipo').value;
        let color = document.querySelector('#color').value;
        let serie = document.querySelector('#serie').value;
        let dimensiones = document.querySelector('#dimensiones').value;
        let valor = document.querySelector('#valor').value;
        let situacion = document.querySelector('#situacion').value;
        let estado_conservacion = document.querySelector('#estado_conservacion').value;
        let observaciones = document.querySelector('#observaciones').value;
        if (ambiente == "" || denominacion == "" || marca == "" || modelo == "" || tipo == "" || color == "" || serie == "" || dimensiones == "" || valor == "" || situacion == "" || estado_conservacion == "" || observaciones == "") {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'Campos vacíos',
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: ''
            })
            return;
        }
        let cont = 0;
        lista_bienes_registro.forEach(bien => {
            if (bien.cod_patrimonial == cod_patrimonial && cod_patrimonial != "") {
                cont++;
            }
        });
        if (cont == 0) {
            let nuevo_bien = new Object();
            nuevo_bien.ambiente = ambiente;
            nuevo_bien.cod_patrimonial = cod_patrimonial;
            nuevo_bien.denominacion = denominacion;
            nuevo_bien.marca = marca;
            nuevo_bien.modelo = modelo;
            nuevo_bien.tipo = tipo;
            nuevo_bien.color = color;
            nuevo_bien.serie = serie;
            nuevo_bien.dimensiones = dimensiones;
            nuevo_bien.valor = valor;
            nuevo_bien.situacion = situacion;
            nuevo_bien.estado_conservacion = estado_conservacion;
            nuevo_bien.observaciones = observaciones;
            lista_bienes_registro.push(nuevo_bien);
            document.getElementById("frmAgregarBienes").reset();
            //console.log(nuevo_bien);
            console.log(lista_bienes_registro);
            listar_bienes_ingreso();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: 'El código patrimonial ya esta agregado en la lista de ingreso de bienes',
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: ''
            })
            return;
        }

    } catch (e) {
        console.log("Oops, ocurrio un error al agregar bien a ingreso" + e);
    } finally {
        ocultarPopupCarga();
    }
}
function eliminar_bien_ingreso(index) {
    lista_bienes_registro.splice(index, 1);
    listar_bienes_ingreso();
}
async function registrarBien() {
    const id_categoria = document.getElementById('id_categoria').value;
    const id_dependencia = document.getElementById('id_dependencia').value;

    console.log("id_categoria:", id_categoria, "id_dependencia:", id_dependencia);

    if (!id_categoria || id_categoria === '' || id_categoria === 'undefined' ||
        !id_dependencia || id_dependencia === '' || id_dependencia === 'undefined') {
        Swal.fire({
            type: 'error',
            title: 'Error',
            text: 'Debe seleccionar una categoría y una dependencia válidas.',
            confirmButtonClass: 'btn btn-confirm mt-2',
            footer: ''
        });
        return;
    }

    try {
        const datos = new FormData(document.getElementById('frmRegistrar'));

        // Capturar dimensiones
        let largo = document.querySelector('#dim_largo').value || 0;
        let ancho = document.querySelector('#dim_ancho').value || 0;
        let alto = document.querySelector('#dim_alto').value || 0;
        let dimensiones = `${largo}x${ancho}x${alto} cm`;

        // Eliminar si existe y reemplazar
        datos.delete('dimensiones');
        datos.append('dimensiones', dimensiones);

        // Agregar usuario_registro desde la sesión
        datos.append('usuario_registro', session_usuario);
        datos.append('sesion', session_session);
        datos.append('token', token_token);

        let respuesta = await fetch(base_url_server + 'src/control/Bien.php?tipo=registrar', {
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
                window.location.href = base_url + 'bienes';
            }, 1000);
        } else if (json.msg === "Error_Sesion") {
            alerta_sesion();
        } else {
            Swal.fire({
                type: 'error',
                title: 'Error',
                text: json.mensaje || 'Error desconocido.',
                confirmButtonClass: 'btn btn-confirm mt-2',
                footer: '',
                timer: 1000
            });
        }
    } catch (e) {
        console.error("Error al registrar bien: ", e);
    }
}






