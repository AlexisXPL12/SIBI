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
        const session_usuario = '<?php echo $_SESSION["sesion_usuario"] ; ?>';
        const session_session = '<?php echo $_SESSION['sesion_id']; ?>';
        const token_token = '<?php echo $_SESSION['sesion_token']; ?>';

    </script>
    <?php date_default_timezone_set('America/Lima');  ?>
    <style>
        /* ============================================
        SISTEMA DE GESTIÓN - SIBI
           ============================================ */

         /* Variables principales */
        :root {
            --bg-primary: #0f172a;
            --bg-secondary: #1e293b;
            --bg-card: rgba(30, 41, 59, 0.8);
            --bg-hover: #334155;
            --text-primary: #e2e8f0;
            --text-secondary: #94a3b8;
            --accent-blue: #3b82f6;
            --accent-blue-hover: #2563eb;
            --border-color: rgba(148, 163, 184, 0.2);
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        /* Base styles */
        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%) !important;
            color: var(--text-primary) !important;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        /* Header */
        #page-topbar {
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color) !important;
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
        }

        #page-topbar .navbar-brand-box .logo:hover {
            color: var(--accent-blue) !important;
            text-decoration: none;
        }

        #page-topbar .navbar-brand-box .logo i {
            font-size: 1.8rem;
            margin-right: 12px;
            color: var(--accent-blue);
        }

        /* Header buttons */
        #page-topbar .btn.header-item {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        #page-topbar .btn.header-item:hover {
            background: var(--bg-hover) !important;
            border-color: var(--accent-blue) !important;
            color: var(--text-primary) !important;
        }

        .header-profile-user {
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        #page-topbar .btn.header-item:hover .header-profile-user {
            border-color: var(--accent-blue);
        }

        /* Navigation */
        .topnav {
            background: rgba(30, 41, 59, 0.9) !important;
            backdrop-filter: blur(10px);
            border: none !important;
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
            padding: 15px 20px;
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .topnav .navbar-nav .nav-link:hover {
            color: white !important;
            background: var(--accent-blue) !important;
        }

        .topnav .navbar-nav .nav-link i {
            margin-right: 8px;
            font-size: 1rem;
        }

        /* Dropdowns */
        .dropdown-menu {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 8px !important;
            box-shadow: var(--shadow) !important;
            backdrop-filter: blur(15px);
            padding: 8px 0 !important;
            margin-top: 8px !important;
            min-width: 180px;
            z-index: 1300 !important;
        }

        /* Header dropdown positioning */
        #page-topbar .dropdown-menu {
            right: 0 !important;
            left: auto !important;
            z-index: 1400 !important;
        }

        .dropdown-item {
            padding: 10px 20px !important;
            color: var(--text-primary) !important;
            transition: all 0.3s ease !important;
            font-weight: 500;
            border-radius: 4px;
            margin: 2px 8px;
        }

        .dropdown-item:hover {
            background: var(--accent-blue) !important;
            color: white !important;
        }

        /* Cards */
        .card {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            box-shadow: var(--shadow) !important;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            border-color: var(--accent-blue);
        }

        .card-title {
            color: var(--text-primary) !important;
            font-weight: 700;
        }

        .card h2, .card h3, .card h4 {
            color: var(--accent-blue) !important;
            font-weight: 800;
        }

        .card .btn-primary {
            background: var(--accent-blue) !important;
            border: none !important;
            border-radius: 8px !important;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .card .btn-primary:hover {
            background: var(--accent-blue-hover) !important;
            transform: translateY(-2px);
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
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            z-index: 10000 !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #popup-carga .popup-content {
            background: var(--bg-card);
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
        }

        #popup-carga .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-top: 3px solid var(--accent-blue);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #popup-carga p {
            margin: 0;
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Footer */
        .footer {
            background: var(--bg-secondary) !important;
            border-top: 1px solid var(--border-color) !important;
            margin-top: 4rem;
            padding: 2rem 0;
        }

        .footer .text-center,
        .footer .text-right {
            color: var(--text-secondary) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            #page-topbar .navbar-brand-box .logo span {
                font-size: 0.9rem;
            }
            
            .topnav .navbar-nav .nav-link {
                padding: 12px 15px;
                margin: 2px 0;
            }
            
            .dropdown-menu {
                min-width: 160px;
            }
            
            .page-content {
                padding-top: 120px !important;
            }
            
            #page-topbar .dropdown-menu {
                right: 10px !important;
                min-width: 160px;
            }
        }

        /* Focus states for accessibility */
        .btn:focus,
        .dropdown-item:focus,
        .nav-link:focus {
            outline: 2px solid var(--accent-blue);
            outline-offset: 2px;
        }

        /* Ensure dropdown visibility */
        .dropdown.show .dropdown-menu {
            display: block !important;
            opacity: 1;
            visibility: visible;
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