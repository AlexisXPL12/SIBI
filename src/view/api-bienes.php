<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API de Bienes</title>
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
        pre {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .codigo-patrimonial-group {
            display: flex;
            gap: 10px;
        }
        .codigo-patrimonial-group input {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">API de Bienes</h1>

        <!-- Campo oculto para el token -->
        <input type="hidden" id="token" value="">

        <!-- Sección de Filtros -->
        <div class="filter-section">
            <h4>Buscar Bienes</h4>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Código Patrimonial:</label>
                    <div class="codigo-patrimonial-group">
                        <input type="text" class="form-control" id="prefijo" placeholder="Prefijo (IES)" maxlength="3">
                        <input type="text" class="form-control" id="numero" placeholder="Número (001)" maxlength="3">
                        <input type="text" class="form-control" id="anio" placeholder="Año (2025)" maxlength="4">
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12 mb-3">
                    <label for="nombre" class="form-label">Nombre del Bien:</label>
                    <input type="text" class="form-control" id="nombre" placeholder="Ej: Laptop HP">
                </div>
            </div>
            <button id="btn_buscar" class="btn btn-primary">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

        <!-- Sección de Resultados -->
        <div class="mt-4">
            <h4>Resultados</h4>
            <div id="resultados" class="mt-3">
                <p>Cargando...</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>src/view/js/api_bienes.js"></script>
</body>
</html>




