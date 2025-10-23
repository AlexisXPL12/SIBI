<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación API</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        const base_url = '<?php echo BASE_URL; ?>';
        const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .auth-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="auth-section mt-5">
            <h2 class="text-center mb-4">Autenticación API</h2>
            <div class="mb-3">
                <label for="token" class="form-label">Token de Acceso:</label>
                <input type="text" class="form-control" id="token" placeholder="Ingrese su token">
            </div>
            <button id="btn-verificar" class="btn btn-primary w-100">
                <i class="fas fa-key me-2"></i> Verificar y Acceder
            </button>
            <div id="mensaje" class="mt-3"></div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('btn-verificar').addEventListener('click', async function() {
            const token = document.getElementById('token').value;
            const mensajeDiv = document.getElementById('mensaje');

            if (!token) {
                mensajeDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Debe ingresar un token de autenticación.
                    </div>
                `;
                return;
            }

            try {
                const response = await fetch(base_url_server + 'src/control/Apibien.php?tipo=verificarToken', {
                    method: 'POST',
                    headers: {
                        'Authorization': token,
                        'Content-Type': 'application/x-www-form-urlencoded',
                    }
                });

                const result = await response.json();

                if (result.status) {
                    // Guardar el token en localStorage
                    localStorage.setItem('apiToken', token);

                    // Redirigir a la página de la API
                    window.location.href = base_url + 'api-bienes';
                } else {
                    mensajeDiv.innerHTML = `
                        <div class="alert alert-danger">
                            Error: ${result.msg}
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error al verificar el token:', error);
                mensajeDiv.innerHTML = `
                    <div class="alert alert-danger">
                        Error al verificar el token: ${error.message}
                    </div>
                `;
            }
        });
    </script>
</body>
</html>
