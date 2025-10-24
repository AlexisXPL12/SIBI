// Verificar el token al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    const token = localStorage.getItem('apiToken');

    if (!token) {
        window.location.href = base_url + 'autenticacion-api.php';
        return;
    }

    document.getElementById('token').value = token;
    verificarToken(token);
});

// Función para verificar el token con el servidor
async function verificarToken(token) {
    try {
        const response = await fetch(base_url_server + 'src/control/Apibien.php?tipo=verificarToken', {
            method: 'POST',
            headers: {
                'Authorization': token,
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        });

        const result = await response.json();
        const resultadosDiv = document.getElementById('resultados');

        if (!result.status) {
            localStorage.removeItem('apiToken');
            window.location.href = base_url + 'autenticacion-api.php';
            return;
        }

        resultadosDiv.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle fa-lg"></i>
                <strong>Autenticación Exitosa</strong><br>
                Token verificado correctamente. Puede realizar búsquedas de bienes.
            </div>
        `;
    } catch (error) {
        console.error('Error al verificar el token:', error);
        document.getElementById('resultados').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle fa-lg"></i>
                <strong>Error de Autenticación</strong><br>
                ${error.message}
            </div>
        `;
    }
}

// Función para buscar bienes
async function llamar_api() {
    const token = document.getElementById('token').value;
    const prefijo = document.getElementById('prefijo').value;
    const numero = document.getElementById('numero').value;
    const anio = document.getElementById('anio').value;
    const nombre = document.getElementById('nombre').value;

    if (!token) {
        alert('Debe autenticarse primero.');
        window.location.href = base_url + 'autenticacion-api.php';
        return;
    }

    if (!prefijo && !numero && !anio && !nombre) {
        alert('Debe ingresar al menos un criterio de búsqueda.');
        return;
    }

    // Mostrar spinner de carga
    const resultadosDiv = document.getElementById('resultados');
    resultadosDiv.innerHTML = `
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p style="color: var(--text-secondary); font-weight: 600;">Buscando bienes en el sistema...</p>
        </div>
    `;

    try {
        const params = new URLSearchParams();
        if (prefijo) params.append('prefijo', prefijo);
        if (numero) params.append('numero', numero);
        if (anio) params.append('anio', anio);
        if (nombre) params.append('nombre', nombre);

        const response = await fetch(base_url_server + 'src/control/Apibien.php?tipo=buscar_bienes' + (params.toString() ? '&' + params.toString() : ''), {
            method: 'GET',
            headers: {
                'Authorization': token,
            }
        });

        const result = await response.json();

        if (result.status) {
            if (result.contenido.length > 0) {
                mostrarResultados(result.contenido);
                actualizarEstadisticas(result.contenido);
                
                // Actualizar última búsqueda
                const criterio = nombre || `${prefijo || ''}${numero || ''}${anio || ''}`;
                document.getElementById('ultima-busqueda').textContent = criterio.substring(0, 15) + (criterio.length > 15 ? '...' : '');
            } else {
                resultadosDiv.innerHTML = `
                    <div class="alert alert-info text-center">
                        <i class="fas fa-search fa-3x mb-3" style="opacity: 0.3;"></i>
                        <h5><strong>No se encontraron resultados</strong></h5>
                        <p>No se encontraron bienes con los criterios especificados.</p>
                    </div>
                `;
                document.getElementById('results-count').style.display = 'none';
                document.getElementById('stats-container').style.display = 'none';
            }
            
            // Limpiar inputs
            document.getElementById('prefijo').value = '';
            document.getElementById('numero').value = '';
            document.getElementById('anio').value = '';
            document.getElementById('nombre').value = '';
        } else {
            resultadosDiv.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle fa-lg"></i>
                    <strong>Error en la Búsqueda</strong><br>
                    ${result.msg}
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al buscar el bien:', error);
        document.getElementById('resultados').innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-times-circle fa-lg"></i>
                <strong>Error de Conexión</strong><br>
                ${error.message}
            </div>
        `;
    }
}

// Función para obtener el color del estado
function getEstadoClass(estado) {
    const estadoLower = (estado || '').toLowerCase();
    if (estadoLower.includes('bueno') || estadoLower.includes('operativo') || estadoLower.includes('excelente')) {
        return 'estado-bueno';
    } else if (estadoLower.includes('regular') || estadoLower.includes('mantenimiento')) {
        return 'estado-regular';
    } else if (estadoLower.includes('malo') || estadoLower.includes('inoperativo') || estadoLower.includes('deteriorado')) {
        return 'estado-malo';
    }
    return 'estado-regular';
}

// Función para obtener el icono del bien
function getIconoBien(nombreBien) {
    const nombre = (nombreBien || '').toLowerCase();
    if (nombre.includes('computadora') || nombre.includes('laptop') || nombre.includes('pc')) {
        return 'fas fa-laptop';
    } else if (nombre.includes('impresora')) {
        return 'fas fa-print';
    } else if (nombre.includes('escritorio') || nombre.includes('mesa')) {
        return 'fas fa-table';
    } else if (nombre.includes('silla')) {
        return 'fas fa-chair';
    } else if (nombre.includes('telefono') || nombre.includes('celular')) {
        return 'fas fa-mobile-alt';
    } else if (nombre.includes('monitor') || nombre.includes('pantalla')) {
        return 'fas fa-desktop';
    } else if (nombre.includes('proyector')) {
        return 'fas fa-video';
    } else if (nombre.includes('archivador') || nombre.includes('estante')) {
        return 'fas fa-archive';
    } else if (nombre.includes('aire') || nombre.includes('ventilador')) {
        return 'fas fa-fan';
    } else if (nombre.includes('vehiculo') || nombre.includes('auto')) {
        return 'fas fa-car';
    }
    return 'fas fa-box';
}

// Función para actualizar las estadísticas
function actualizarEstadisticas(bienes) {
    const statsContainer = document.getElementById('stats-container');
    statsContainer.style.display = 'grid';

    const totalBienes = bienes.length;
    let bienesBuenos = 0;
    let bienesRegulares = 0;
    let bienesMalos = 0;

    bienes.forEach(bien => {
        const estado = (bien.estado_bien || '').toLowerCase();
        if (estado.includes('bueno') || estado.includes('operativo') || estado.includes('excelente')) {
            bienesBuenos++;
        } else if (estado.includes('regular') || estado.includes('mantenimiento')) {
            bienesRegulares++;
        } else if (estado.includes('malo') || estado.includes('inoperativo') || estado.includes('deteriorado')) {
            bienesMalos++;
        } else {
            bienesRegulares++;
        }
    });

    document.getElementById('total-bienes').textContent = totalBienes;
    document.getElementById('bienes-buenos').textContent = bienesBuenos;
    document.getElementById('bienes-regulares').textContent = bienesRegulares;
}

// Función para mostrar los resultados en cards
function mostrarResultados(bienes) {
    const resultadosDiv = document.getElementById('resultados');
    const resultsCount = document.getElementById('results-count');
    
    resultsCount.textContent = `${bienes.length} ${bienes.length === 1 ? 'resultado' : 'resultados'}`;
    resultsCount.style.display = 'inline-block';

    let html = '';

    bienes.forEach((bien, index) => {
        const estadoClass = getEstadoClass(bien.estado_bien);
        const icono = getIconoBien(bien.nombre_bien);
        
        html += `
            <div class="bien-card" style="animation-delay: ${index * 0.05}s;">
                <div class="bien-header">
                    <div class="bien-codigo">
                        <i class="fas fa-barcode"></i> ${bien.codigo_patrimonial || 'N/A'}
                    </div>
                    <div class="bien-estado ${estadoClass}">
                        ${bien.estado_bien || 'N/A'}
                    </div>
                </div>
                
                <div class="bien-info">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="${icono}"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">NOMBRE DEL BIEN</div>
                            <div class="info-value">${bien.nombre_bien || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-tag"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">MARCA</div>
                            <div class="info-value">${bien.marca || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-cube"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">MODELO</div>
                            <div class="info-value">${bien.modelo || 'N/A'}</div>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">UBICACIÓN</div>
                            <div class="info-value">${bien.ubicacion_especifica || 'N/A'}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    resultadosDiv.innerHTML = html;
}

// Asignar eventos
document.getElementById('btn_buscar').addEventListener('click', llamar_api);

// Permitir búsqueda con Enter en todos los campos
document.getElementById('prefijo').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') llamar_api();
});

document.getElementById('numero').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') llamar_api();
});

document.getElementById('anio').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') llamar_api();
});

document.getElementById('nombre').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') llamar_api();
});