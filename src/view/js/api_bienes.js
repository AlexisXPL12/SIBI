async function llamar_api() {
    const token = document.getElementById('token').value;
    const data = document.getElementById('data').value;

    if (!token) {
        alert('Debe ingresar un token de autenticación.');
        return;
    }

    if (!data) {
        alert('Debe ingresar un nombre de bien para buscar.');
        return;
    }

    try {
        const response = await fetch('src/control/BienApi.php?tipo=verBienApiByNombre', {
            method: 'POST',
            headers: {
                'Authorization': token,
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `data=${encodeURIComponent(data)}`
        });

        const result = await response.json();
        const resultadosDiv = document.getElementById('resultados');

        if (result.status) {
            if (result.contenido.length > 0) {
                resultadosDiv.innerHTML = `
                    <pre>${JSON.stringify(result.contenido, null, 2)}</pre>
                    <p class="mt-3"><strong>Total de registros:</strong> ${result.contenido.length}</p>
                `;
            } else {
                resultadosDiv.innerHTML = `
                    <div class="alert alert-info">
                        No se encontraron bienes con el nombre "${data}".
                    </div>
                `;
            }
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
