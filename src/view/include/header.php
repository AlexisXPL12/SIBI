<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>SIBI - IES</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Sistema de Gestión de Bienes Institucionales" name="description" />
    <meta content="AnibalYucraC" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo BASE_URL ?>src/view/pp/assets/images/ies.ico">

    <!-- Plugins css -->
    <script src="<?php echo BASE_URL ?>src/view/js/principal.js"></script>
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/dataTables.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/responsive.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/buttons.bootstrap4.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/datatables/select.bootstrap4.css" rel="stylesheet" type="text/css" />
    <!-- Sweet Alerts css -->
    <link href="<?php echo BASE_URL ?>src/view/pp/plugins/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/pp/assets/css/theme.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo BASE_URL ?>src/view/include/styles.css" rel="stylesheet" type="text/css" />
    <script>
        const base_url = '<?php echo BASE_URL; ?>';
        const base_url_server = '<?php echo BASE_URL_SERVER; ?>';
        const session_usuario = '<?php echo $_SESSION["sesion_usuario"]; ?>';
        const session_session = '<?php echo $_SESSION['sesion_id']; ?>';
        const token_token = '<?php echo $_SESSION['sesion_token']; ?>';
    </script>
    <?php date_default_timezone_set('America/Lima');  ?>
    <style>
        /* ============================================
            SISTEMA DE GESTIÓN - SIBI (Estilo Moderno Institucional)
        ============================================ */

        /* Variables principales */
        :root {
            --bg-primary: #f0f4f8;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-hover: #e8f4f8;
            --text-primary: #1e3a5f;
            --text-secondary: #546e7a;
            --accent-blue: #1e88e5;
            --accent-blue-hover: #1565c0;
            --accent-green: #00897b;
            --accent-green-hover: #00695c;
            --accent-yellow: #ffa726;
            --accent-yellow-hover: #fb8c00;
            --border-color: #cfd8dc;
            --shadow: 0 2px 8px rgba(30, 136, 229, 0.08);
            --shadow-hover: 0 4px 16px rgba(30, 136, 229, 0.15);
            --gradient-primary: linear-gradient(135deg, #1e88e5 0%, #00897b 100%);
            --gradient-secondary: linear-gradient(135deg, #00897b 0%, #ffa726 100%);
        }

        /* Base styles */
        body {
            background: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            min-height: 100vh;
        }

        /* Header */
        #page-topbar {
            background: var(--bg-secondary) !important;
            border-bottom: 3px solid transparent !important;
            border-image: var(--gradient-primary) 1 !important;
            box-shadow: var(--shadow);
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1200 !important;
        }

        /* Logo */
        #page-topbar .navbar-brand-box .logo {
            color: var(--text-primary) !important;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        #page-topbar .navbar-brand-box .logo:hover {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
        }

        #page-topbar .navbar-brand-box .logo i {
            font-size: 1.6rem;
            margin-right: 12px;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Header buttons */
        #page-topbar .btn.header-item {
            background: var(--bg-primary) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        #page-topbar .btn.header-item:hover {
            background: var(--bg-hover) !important;
            border-color: var(--accent-blue) !important;
            color: var(--accent-blue) !important;
            transform: translateY(-1px);
        }

        .header-profile-user {
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        #page-topbar .btn.header-item:hover .header-profile-user {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(30, 136, 229, 0.1);
        }

        /* Navigation */
        .topnav {
            background: var(--bg-secondary) !important;
            border-bottom: 2px solid var(--border-color) !important;
            box-shadow: var(--shadow);
            position: fixed !important;
            top: 70px;
            left: 0;
            right: 0;
            z-index: 1100 !important;
        }

        .topnav .navbar-nav .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 600;
            padding: 14px 20px;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .topnav .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 3px;
            background: var(--gradient-primary);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .topnav .navbar-nav .nav-link:hover {
            color: var(--accent-blue) !important;
            background: var(--bg-hover) !important;
        }

        .topnav .navbar-nav .nav-link:hover::before {
            width: 80%;
        }

        .topnav .navbar-nav .nav-link i {
            margin-right: 8px;
            font-size: 1rem;
        }

        /* Dropdowns */
        .dropdown-menu {
            background: var(--bg-secondary) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 10px !important;
            box-shadow: var(--shadow-hover) !important;
            padding: 8px 0 !important;
            margin-top: 10px !important;
            min-width: 200px;
            z-index: 1300 !important;
        }

        /* Header dropdown positioning */
        #page-topbar .dropdown-menu {
            right: 0 !important;
            left: auto !important;
            z-index: 1400 !important;
        }

        .dropdown-item {
            padding: 12px 20px !important;
            color: var(--text-primary) !important;
            transition: all 0.3s ease !important;
            font-weight: 500;
            border-radius: 6px;
            margin: 3px 8px;
            font-size: 0.95rem;
            position: relative;
        }

        .dropdown-item::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 60%;
            background: var(--gradient-primary);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--bg-hover) !important;
            color: var(--accent-blue) !important;
            padding-left: 28px !important;
        }

        .dropdown-item:hover::before {
            width: 4px;
        }

        /* Cards */
        .card {
            background: var(--bg-card) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow) !important;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-hover) !important;
            transform: translateY(-3px);
            border-color: var(--accent-blue);
        }

        .card:hover::before {
            opacity: 1;
        }

        .card-title {
            color: var(--text-primary) !important;
            font-weight: 700;
        }

        .card h2,
        .card h3,
        .card h4 {
            color: var(--text-primary) !important;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card .btn-primary {
            background: var(--gradient-primary) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.2);
        }

        .card .btn-primary:hover {
            background: var(--gradient-secondary) !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(30, 136, 229, 0.3);
        }

        /* Main content */
        .page-content {
            background: transparent;
            min-height: calc(100vh - 140px);
            padding-top: 140px !important;
        }

        /* Loading popup */
        #popup-carga {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(240, 244, 248, 0.95);
            backdrop-filter: blur(5px);
            z-index: 10000 !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #popup-carga .popup-content {
            background: var(--bg-secondary);
            padding: 40px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow-hover);
            border: 2px solid var(--border-color);
        }

        #popup-carga .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border-color);
            border-top: 4px solid var(--accent-blue);
            border-right: 4px solid var(--accent-green);
            border-bottom: 4px solid var(--accent-yellow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        #popup-carga p {
            margin: 0;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.05rem;
        }

        /* Footer */
        .footer {
            background: var(--bg-secondary) !important;
            border-top: 3px solid transparent !important;
            border-image: var(--gradient-primary) 1 !important;
            margin-top: 4rem;
            padding: 2rem 0;
        }

        .footer .text-center,
        .footer .text-right {
            color: var(--text-secondary) !important;
            font-weight: 500;
        }

        /* Badges y elementos adicionales */
        .badge-primary {
            background: var(--gradient-primary) !important;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-success {
            background: linear-gradient(135deg, var(--accent-green) 0%, var(--accent-blue) 100%) !important;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        .badge-warning {
            background: linear-gradient(135deg, var(--accent-yellow) 0%, var(--accent-green) 100%) !important;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #page-topbar .navbar-brand-box .logo span {
                font-size: 0.85rem;
            }

            .topnav .navbar-nav .nav-link {
                padding: 12px 16px;
                margin: 2px 0;
            }

            .dropdown-menu {
                min-width: 180px;
            }

            .page-content {
                padding-top: 130px !important;
            }

            #page-topbar .dropdown-menu {
                right: 10px !important;
                min-width: 180px;
            }
        }

        /* Focus states for accessibility */
        .btn:focus,
        .dropdown-item:focus,
        .nav-link:focus {
            outline: 3px solid var(--accent-blue);
            outline-offset: 2px;
        }

        /* Ensure dropdown visibility */
        .dropdown.show .dropdown-menu {
            display: block !important;
            opacity: 1;
            visibility: visible;
        }

        /* Scrollbar personalizado */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gradient-secondary);
        }
    </style>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <div class="main-content">

            <header id="page-topbar">
                <div class="navbar-header">
                    <!-- LOGO -->
                    <div class="navbar-brand-box d-flex align-items-left">
                        <a href="<?php echo BASE_URL ?>" class="logo">
                            <i class="mdi mdi-package-variant"></i>
                            <span>
                                SISTEMA DE GESTION DE INVENTARIO
                            </span>
                        </a>

                        <button type="button" class="btn btn-sm mr-2 font-size-16 d-lg-none header-item waves-effect waves-light" data-toggle="collapse" data-target="#topnav-menu-content">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="dropdown d-inline-block ml-2">
                            <button type="button" class="btn header-item waves-effect waves-light"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png">
                                <span class="d-none d-sm-inline-block ml-1"><?php /* echo $_SESSION['sesion_sigi_usuario_nom']; */ ?></span>
                                <i class="mdi mdi-chevron-down d-none d-sm-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="javascript:void(0)">
                                    Mi perfil
                                </a>
                                <button class="dropdown-item d-flex align-items-center justify-content-between" onclick="sent_email_password();">
                                    <span>Cambiar mi Contraseña</span>
                                </button>
                                <button class="dropdown-item d-flex align-items-center justify-content-between" onclick="cerrar_sesion();">
                                    <span>Cerrar Sesión</span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <div class="topnav">
                <div class="container-fluid">
                    <nav class="navbar navbar-light navbar-expand-lg topnav-menu">

                        <div class="collapse navbar-collapse" id="topnav-menu-content">
                            <ul class="navbar-nav">

                                <!-- ---------------------------------------------- INICIO MENU SIGI ------------------------------------------------------------ -->
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo BASE_URL ?>">
                                        <i class="mdi mdi-home"></i>Inicio
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle arrow-none" href="#" id="topnav-components" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-diamond-stone"></i>Gestión <div class="arrow-down"></div>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="topnav-components">
                                        <a href="<?php echo BASE_URL ?>usuarios" class="dropdown-item">Usuarios</a>
                                        <a href="<?php echo BASE_URL ?>clientes-api" class="dropdown-item">Clientes</a>
                                        <a href="<?php echo BASE_URL ?>tokens" class="dropdown-item">Tokens</a>
                                        <a href="<?php echo BASE_URL ?>cont-request" class="dropdown-item">Contador</a>
                                        <a href="<?php echo BASE_URL ?>bienes" class="dropdown-item">Bienes</a>
                                        <a href="<?php echo BASE_URL ?>ambientes" class="dropdown-item">Ambientes o dependencias</a>
                                        <a href="<?php echo BASE_URL ?>categorias" class="dropdown-item">Categorias</a>
                                        <a href="<?php echo BASE_URL ?>carreras" class="dropdown-item">Programas de Estudio</a>
                                        <a href="<?php echo BASE_URL ?>movimientos" class="dropdown-item">Movimientos</a>
                                        <a href="<?php echo BASE_URL ?>reportes" class="dropdown-item">Reportes</a>
                                    </div>
                                </li>

                                <!-- ---------------------------------------------- FIN MENU SIGI ------------------------------------------------------------ -->
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>


            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->

                    <!-- Popup de carga -->
                    <div id="popup-carga" style="display: none;">
                        <div class="popup-overlay">
                            <div class="popup-content">
                                <div class="spinner"></div>
                                <p>Cargando, por favor espere...</p>
                            </div>
                        </div>
                    </div>