// Verificar el token al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el token de localStorage (si existe)
    const token = localStorage.getItem('apiToken');

    if (!token) {
        // Si no hay token, redirigir a la página de autenticación
        window.location.href = base_url + 'autenticacion-api.php';
        return;
    }

    // Almacenar el token en el campo oculto
    document.getElementById('token').value = token;

    // Verificar el token con el servidor
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
            // Si el token no es válido, redirigir a la página de autenticación
            localStorage.removeItem('apiToken');
            window.location.href = base_url + 'autenticacion-api.php';
            return;
        }

        // Si el token es válido, mostrar mensaje de éxito
        resultadosDiv.innerHTML = '<p>Token verificado. Puede buscar bienes.</p>';
    } catch (error) {
        console.error('Error al verificar el token:', error);
        document.getElementById('resultados').innerHTML = `
            <div class="alert alert-danger">
                Error al verificar el token: ${error.message}
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

    // Al menos uno de los campos de búsqueda debe estar lleno
    if (!prefijo && !numero && !anio && !nombre) {
        alert('Debe ingresar al menos un criterio de búsqueda.');
        return;
    }

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
        const resultadosDiv = document.getElementById('resultados');

        if (result.status) {
            if (result.contenido.length > 0) {
                mostrarResultados(result.contenido);
            } else {
                resultadosDiv.innerHTML = `
                    <div class="alert alert-info">
                        No se encontraron bienes con los criterios de búsqueda.
                    </div>
                `;
            }
            // Limpiar los inputs después de la búsqueda
            document.getElementById('prefijo').value = '';
            document.getElementById('numero').value = '';
            document.getElementById('anio').value = '';
            document.getElementById('nombre').value = '';
        } else {
            resultadosDiv.innerHTML = `
                <div class="alert alert-danger">
                    Error: ${result.msg}
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al buscar el bien:', error);
        document.getElementById('resultados').innerHTML = `
            <div class="alert alert-danger">
                Error al cargar los datos: ${error.message}
            </div>
        `;
    }
}

// Función para mostrar los resultados de manera ordenada
function mostrarResultados(bienes) {
    const resultadosDiv = document.getElementById('resultados');

    // Crear una tabla para mostrar los datos de manera ordenada
    let html = `
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>N°</th>
                        <th>Código Patrimonial</th>
                        <th>Nombre del Bien</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                    </tr>
                </thead>
                <tbody>
    `;

    bienes.forEach((bien, index) => {
        html += `
            <tr>
                <td>${index + 1}</td>
                <td>${bien.codigo_patrimonial || 'N/A'}</td>
                <td>${bien.nombre_bien || 'N/A'}</td>
                <td>${bien.marca || 'N/A'}</td>
                <td>${bien.modelo || 'N/A'}</td>
                <td>${bien.estado_bien || 'N/A'}</td>
                <td>${bien.ubicacion_especifica || 'N/A'}</td>
            </tr>
        `;
    });

    html += `
                </tbody>
            </table>
        </div>
        <p class="mt-3"><strong>Total de registros:</strong> ${bienes.length}</p>
    `;

    resultadosDiv.innerHTML = html;
}

// Asignar el evento al botón de búsqueda
document.getElementById('btn_buscar').addEventListener('click', llamar_api);






