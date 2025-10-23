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
// GENERAR FILA
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
        <td>${item.options}</td>
    `;

    // Modal de edición
    document.querySelector('#modals_editar').innerHTML += `
        <div class="modal fade modal_editar${item.id}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title h4" id="myLargeModalLabel">Actualizar datos del bien</h5>
                        <button type="button" class="close waves-effect waves-light" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12">
                            <form class="form-horizontal" id="frmActualizar${item.id}">
                                <div class="form-group row mb-2">
                                    <label for="codigo_patrimonial${item.id}" class="col-3 col-form-label">Código Patrimonial:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="codigo_patrimonial${item.id}" name="codigo_patrimonial" value="${item.codigo_patrimonial}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="nombre_bien${item.id}" class="col-3 col-form-label">Nombre del Bien:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="nombre_bien${item.id}" name="nombre_bien" value="${item.nombre_bien}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="descripcion${item.id}" class="col-3 col-form-label">Descripción:</label>
                                    <div class="col-9">
                                        <textarea name="descripcion" id="descripcion${item.id}" class="form-control">${item.descripcion || ''}</textarea>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="nombre_categoria${item.id}" class="col-3 col-form-label">Categoría:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="nombre_categoria${item.id}" value="${item.nombre_categoria || 'N/A'}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="nombre_dependencia${item.id}" class="col-3 col-form-label">Dependencia:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="nombre_dependencia${item.id}" value="${item.nombre_dependencia || 'N/A'}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="marca${item.id}" class="col-3 col-form-label">Marca:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="marca${item.id}" name="marca" value="${item.marca || ''}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="modelo${item.id}" class="col-3 col-form-label">Modelo:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="modelo${item.id}" name="modelo" value="${item.modelo || ''}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="serie${item.id}" class="col-3 col-form-label">Serie:</label>
                                    <div class="col-9">
                                        <input type="text" class="form-control" id="serie${item.id}" name="serie" value="${item.serie || ''}">
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="estado_bien${item.id}" class="col-3 col-form-label">Estado:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="estado_bien${item.id}" name="estado_bien">
                                            <option value="ACTIVO" ${item.estado_bien === 'ACTIVO' ? 'selected' : ''}>ACTIVO</option>
                                            <option value="BAJA" ${item.estado_bien === 'BAJA' ? 'selected' : ''}>BAJA</option>
                                            <option value="MANTENIMIENTO" ${item.estado_bien === 'MANTENIMIENTO' ? 'selected' : ''}>MANTENIMIENTO</option>
                                            <option value="PRESTADO" ${item.estado_bien === 'PRESTADO' ? 'selected' : ''}>PRESTADO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row mb-2">
                                    <label for="condicion_bien${item.id}" class="col-3 col-form-label">Condición:</label>
                                    <div class="col-9">
                                        <select class="form-control" id="condicion_bien${item.id}" name="condicion_bien">
                                            <option value="NUEVO" ${item.condicion_bien === 'NUEVO' ? 'selected' : ''}>NUEVO</option>
                                            <option value="BUENO" ${item.condicion_bien === 'BUENO' ? 'selected' : ''}>BUENO</option>
                                            <option value="REGULAR" ${item.condicion_bien === 'REGULAR' ? 'selected' : ''}>REGULAR</option>
                                            <option value="MALO" ${item.condicion_bien === 'MALO' ? 'selected' : ''}>MALO</option>
                                            <option value="INSERVIBLE" ${item.condicion_bien === 'INSERVIBLE' ? 'selected' : ''}>INSERVIBLE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group mb-0 justify-content-end row text-center">
                                    <div class="col-12">
                                        <button type="button" class="btn btn-light waves-effect waves-light" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="actualizarBien(${item.id})">Actualizar</button>
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

async function actualizarBien(id) {
    let codigo_patrimonial = document.querySelector('#codigo_patrimonial' + id).value;
    let nombre_bien = document.querySelector('#nombre_bien' + id).value;
    let descripcion = document.querySelector('#descripcion' + id).value;

    if (codigo_patrimonial == "" || nombre_bien == "") {
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
        let respuesta = await fetch(base_url_server + 'src/control/Bien.php?tipo=actualizar', {
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
            listar_bienes_ordenados();
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
        console.log("Error al actualizar bien: " + e);
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






