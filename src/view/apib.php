<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autenticación API - SIBI</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script>
        const base_url = '<?php echo BASE_URL; ?>';
        const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
    </script>
    <style>
        :root {
            --bg-primary: #f0f4f8;
            --bg-secondary: #ffffff;
            --text-primary: #1e3a5f;
            --text-secondary: #546e7a;
            --accent-blue: #1e88e5;
            --accent-green: #00897b;
            --border-color: #cfd8dc;
            --shadow: 0 2px 8px rgba(30, 136, 229, 0.08);
            --shadow-hover: 0 8px 24px rgba(30, 136, 229, 0.2);
            --gradient-primary: linear-gradient(135deg, #1e88e5 0%, #00897b 100%);
        }

        body {
            background: var(--bg-primary);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 500px;
        }

        .auth-card {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            background: var(--gradient-primary);
            padding: 2.5rem 2rem;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .auth-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
            position: relative;
            z-index: 1;
        }

        .auth-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
            position: relative;
            z-index: 1;
        }

        .auth-header p {
            margin: 0.5rem 0 0 0;
            opacity: 0.95;
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .auth-body {
            padding: 2.5rem 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label i {
            color: var(--accent-blue);
        }

        .input-group-custom {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            z-index: 10;
        }

        .form-control {
            border: 2px solid var(--border-color);
            border-radius: 10px;
            padding: 0.875rem 1rem 0.875rem 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 136, 229, 0.15);
            outline: none;
        }

        .btn-auth {
            background: var(--gradient-primary);
            border: none;
            border-radius: 10px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(30, 136, 229, 0.4);
        }

        .btn-auth:active {
            transform: translateY(-1px);
        }

        .btn-auth.loading {
            opacity: 0.8;
            pointer-events: none;
        }

        .btn-auth .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .btn-auth.loading .spinner {
            display: block;
        }

        .btn-auth.loading .btn-text {
            display: none;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .alert {
            border: none;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-left: 4px solid var(--accent-green);
            color: #2e7d32;
        }

        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
            border-left: 4px solid #e53935;
            color: #c62828;
        }

        .alert i {
            margin-right: 0.5rem;
        }

        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid rgba(30, 136, 229, 0.2);
            border-radius: 10px;
            padding: 1.25rem;
            margin-top: 1.5rem;
        }

        .info-box h6 {
            color: var(--accent-blue);
            font-weight: 700;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box ul {
            margin: 0;
            padding-left: 1.5rem;
            color: var(--text-primary);
        }

        .info-box li {
            margin-bottom: 0.5rem;
        }

        .auth-footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: var(--bg-primary);
            border-top: 2px solid var(--border-color);
        }

        .auth-footer p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: var(--accent-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .auth-header {
                padding: 2rem 1.5rem;
            }

            .auth-body {
                padding: 2rem 1.5rem;
            }

            .auth-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .auth-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <!-- Header -->
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h2>Autenticación API</h2>
                <p>Sistema Integral de Bienes Institucionales</p>
            </div>

            <!-- Body -->
            <div class="auth-body">
                <div id="mensaje"></div>

                <form id="auth-form">
                    <div class="form-group">
                        <label for="token" class="form-label">
                            <i class="fas fa-key"></i>
                            Token de Acceso
                        </label>
                        <div class="input-group-custom">
                            <i class="fas fa-shield-alt input-icon"></i>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="token" 
                                placeholder="Ingrese su token de autenticación"
                                autocomplete="off"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" id="btn-verificar" class="btn-auth">
                        <div class="spinner"></div>
                        <span class="btn-text">
                            <i class="fas fa-sign-in-alt"></i>
                            Verificar y Acceder
                        </span>
                    </button>
                </form>

                <div class="info-box">
                    <h6>
                        <i class="fas fa-info-circle"></i>
                        Información Importante
                    </h6>
                    <ul>
                        <li>El token es necesario para acceder a la API</li>
                        <li>Mantenga su token seguro y confidencial</li>
                        <li>No comparta su token con terceros</li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="auth-footer">
                <p>¿Necesita ayuda? <a href="#"><i class="fas fa-question-circle"></i> Contacte al soporte</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('auth-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const token = document.getElementById('token').value;
            const mensajeDiv = document.getElementById('mensaje');
            const btnVerificar = document.getElementById('btn-verificar');

            if (!token) {
                mensajeDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Campo Requerido:</strong> Debe ingresar un token de autenticación.
                    </div>
                `;
                return;
            }

            // Activar estado de carga
            btnVerificar.classList.add('loading');
            mensajeDiv.innerHTML = '';

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

                    // Mostrar mensaje de éxito
                    mensajeDiv.innerHTML = `
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i>
                            <strong>¡Autenticación Exitosa!</strong> Redirigiendo al sistema...
                        </div>
                    `;

                    // Redirigir después de 1 segundo
                    setTimeout(() => {
                        window.location.href = base_url + 'api-bienes';
                    }, 1000);
                } else {
                    btnVerificar.classList.remove('loading');
                    mensajeDiv.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle"></i>
                            <strong>Error de Autenticación:</strong> ${result.msg}
                        </div>
                    `;
                }
            } catch (error) {
                btnVerificar.classList.remove('loading');
                console.error('Error al verificar el token:', error);
                mensajeDiv.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Error de Conexión:</strong> ${error.message}
                    </div>
                `;
            }
        });

        // Limpiar mensaje de error al escribir
        document.getElementById('token').addEventListener('input', function() {
            const mensajeDiv = document.getElementById('mensaje');
            if (mensajeDiv.querySelector('.alert-danger')) {
                mensajeDiv.innerHTML = '';
            }
        });
    </script>
</body>
</html>
