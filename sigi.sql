-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2025 a las 10:48:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sigi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bienes`
--

CREATE TABLE `bienes` (
  `id_bien` int(11) NOT NULL,
  `codigo_patrimonial` varchar(30) NOT NULL,
  `codigo_barra` varchar(50) DEFAULT NULL,
  `nombre_bien` varchar(300) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL,
  `serie` varchar(100) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `dimensiones` varchar(100) DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_dependencia` int(11) DEFAULT NULL,
  `ubicacion_especifica` varchar(200) DEFAULT NULL,
  `fecha_adquisicion` date DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `numero_factura` varchar(50) DEFAULT NULL,
  `numero_orden_compra` varchar(50) DEFAULT NULL,
  `estado_bien` enum('ACTIVO','BAJA','MANTENIMIENTO','PRESTADO') DEFAULT 'ACTIVO',
  `condicion_bien` enum('NUEVO','BUENO','REGULAR','MALO','INSERVIBLE') DEFAULT 'BUENO',
  `observaciones` text DEFAULT NULL,
  `es_inventariable` tinyint(1) DEFAULT 1,
  `usuario_registro` varchar(100) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `bienes`
--

INSERT INTO `bienes` (`id_bien`, `codigo_patrimonial`, `codigo_barra`, `nombre_bien`, `descripcion`, `marca`, `modelo`, `serie`, `color`, `dimensiones`, `id_categoria`, `id_dependencia`, `ubicacion_especifica`, `fecha_adquisicion`, `fecha_ingreso`, `numero_factura`, `numero_orden_compra`, `estado_bien`, `condicion_bien`, `observaciones`, `es_inventariable`, `usuario_registro`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'CP-001-2024', NULL, 'Laptop HP Pavilion', 'Laptop para laboratorio de programaciÃ³n', 'HP', 'Pavilion 15', NULL, NULL, NULL, 1, 2, 'Laboratorio de ProgramaciÃ³n', NULL, '2024-01-15', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(2, 'CP-002-2024', NULL, 'Proyector Epson', 'Proyector para aulas', 'Epson', 'PowerLite X41+', NULL, NULL, NULL, 5, 2, 'Aula 201', NULL, '2024-01-20', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(3, 'HE-001-2024', NULL, 'Taladro ElÃ©ctrico', 'Taladro para taller de mecÃ¡nica', 'DeWalt', 'DW511', NULL, NULL, NULL, 3, 4, 'Taller Principal', NULL, '2024-02-01', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(4, 'MO-001-2024', NULL, 'Escritorio de Oficina', 'Escritorio para Ã¡rea administrativa', 'Steelcase', 'Series 7', NULL, NULL, NULL, 4, 1, 'Oficina Principal', NULL, '2024-01-10', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `codigo_carrera` varchar(20) NOT NULL,
  `nombre_carrera` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `duracion_semestres` int(11) DEFAULT 6,
  `coordinador` varchar(150) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`id_carrera`, `codigo_carrera`, `nombre_carrera`, `descripcion`, `duracion_semestres`, `coordinador`, `estado`, `fecha_creacion`) VALUES
(1, 'CAR-001', 'ComputaciÃ³n e InformÃ¡tica', 'Carrera tÃ©cnica en desarrollo de software y sistemas', 6, 'Ing. Carlos LÃ³pez', 'ACTIVO', '2025-09-03 04:32:51'),
(2, 'CAR-002', 'Electrotecnia Industrial', 'Carrera tÃ©cnica en instalaciones y sistemas elÃ©ctricos', 6, 'Ing. MarÃ­a GarcÃ­a', 'ACTIVO', '2025-09-03 04:32:51'),
(3, 'CAR-003', 'MecÃ¡nica Automotriz', 'Carrera tÃ©cnica en reparaciÃ³n y mantenimiento automotriz', 6, 'TÃ©c. Juan RodrÃ­guez', 'ACTIVO', '2025-09-03 04:32:51'),
(4, 'CAR-004', 'ConstrucciÃ³n Civil', 'Carrera tÃ©cnica en edificaciÃ³n y obras civiles', 6, 'Ing. Ana MartÃ­nez', 'ACTIVO', '2025-09-03 04:32:51'),
(5, 'CAR-005', 'AdministraciÃ³n de Empresas', 'Carrera tÃ©cnica en gestiÃ³n empresarial', 6, 'Lic. Luis FernÃ¡ndez', 'ACTIVO', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `codigo_categoria` varchar(20) NOT NULL,
  `nombre_categoria` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `vida_util_anos` int(11) DEFAULT 10,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `codigo_categoria`, `nombre_categoria`, `descripcion`, `vida_util_anos`, `estado`, `fecha_creacion`) VALUES
(1, 'CAT-001', 'Equipos de CÃ³mputo', 'Computadoras, laptops, tablets', 4, 'ACTIVO', '2025-09-03 04:32:51'),
(2, 'CAT-002', 'Equipos de Laboratorio', 'Instrumentos y equipos para laboratorios tÃ©cnicos', 8, 'ACTIVO', '2025-09-03 04:32:51'),
(3, 'CAT-003', 'Herramientas', 'Herramientas manuales y elÃ©ctricas', 6, 'ACTIVO', '2025-09-03 04:32:51'),
(4, 'CAT-004', 'Mobiliario', 'Escritorios, sillas, mesas de trabajo', 10, 'ACTIVO', '2025-09-03 04:32:51'),
(5, 'CAT-005', 'Equipos Audiovisuales', 'Proyectores, parlantes, pantallas', 5, 'ACTIVO', '2025-09-03 04:32:51'),
(6, 'CAT-006', 'Maquinaria', 'MÃ¡quinas para talleres y laboratorios', 12, 'ACTIVO', '2025-09-03 04:32:51'),
(7, 'CAT-007', 'Equipos de Oficina', 'Impresoras, fotocopiadoras', 5, 'ACTIVO', '2025-09-03 04:32:51'),
(8, 'CAT-008', 'ElectrodomÃ©sticos', 'Refrigeradoras, microondas', 8, 'ACTIVO', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_api`
--

CREATE TABLE `client_api` (
  `id` int(11) NOT NULL,
  `ruc` varchar(20) NOT NULL,
  `razon_social` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `client_api`
--

INSERT INTO `client_api` (`id`, `ruc`, `razon_social`, `telefono`, `correo`, `fecha_registro`, `estado`) VALUES
(1, '345678765', 'fdzexrctry', '98766', 'dszxrdtd@gmail.com', '2025-10-02 05:37:59', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cont_request`
--

CREATE TABLE `cont_request` (
  `id` int(11) NOT NULL,
  `id_token` int(11) NOT NULL,
  `tipo` varchar(10) NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencias`
--

CREATE TABLE `dependencias` (
  `id_dependencia` int(11) NOT NULL,
  `codigo_dependencia` varchar(20) NOT NULL,
  `nombre_dependencia` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `responsable` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `dependencias`
--

INSERT INTO `dependencias` (`id_dependencia`, `codigo_dependencia`, `nombre_dependencia`, `descripcion`, `responsable`, `telefono`, `email`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'DEP-001', 'AdministraciÃ³n General', 'Ãrea administrativa y direcciÃ³n', 'Director General', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(2, 'DEP-002', 'ComputaciÃ³n e InformÃ¡tica', 'Carrera de computaciÃ³n e informÃ¡tica', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(3, 'DEP-003', 'Electrotecnia Industrial', 'Carrera de electricidad industrial', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(4, 'DEP-004', 'MecÃ¡nica Automotriz', 'Carrera de mecÃ¡nica automotriz', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(5, 'DEP-005', 'ConstrucciÃ³n Civil', 'Carrera de construcciÃ³n civil', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(6, 'DEP-006', 'AdministraciÃ³n de Empresas', 'Carrera de administraciÃ³n', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(7, 'DEP-007', 'Biblioteca', 'Centro de recursos bibliogrÃ¡ficos', 'Bibliotecario', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(8, 'DEP-008', 'Laboratorios', 'Laboratorios especializados', 'Coordinador de Laboratorios', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(9, 'DEP-009', 'Talleres', 'Talleres de prÃ¡ctica', 'Coordinador de Talleres', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(10, 'DEP-010', 'Recursos Humanos', 'GestiÃ³n de personal', 'Jefe de RRHH', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_actividades`
--

CREATE TABLE `log_actividades` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_bien` int(11) DEFAULT NULL,
  `accion` varchar(100) NOT NULL,
  `descripcion_accion` text DEFAULT NULL,
  `fecha_accion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `id_bien` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('INGRESO','TRASLADO','BAJA','PRESTAMO','DEVOLUCION') NOT NULL,
  `id_dependencia_origen` int(11) DEFAULT NULL,
  `id_dependencia_destino` int(11) DEFAULT NULL,
  `motivo` varchar(300) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `documento_referencia` varchar(100) DEFAULT NULL,
  `usuario_solicita` varchar(100) DEFAULT NULL,
  `usuario_autoriza` varchar(100) DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ejecucion` timestamp NULL DEFAULT NULL,
  `estado_movimiento` enum('PENDIENTE','EJECUTADO','CANCELADO') DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `token` varchar(30) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id`, `id_usuario`, `fecha_hora_inicio`, `fecha_hora_fin`, `token`, `ip_address`, `user_agent`) VALUES
(1, 13, '2025-09-10 19:20:23', '2025-09-11 03:20:23', 'ABrg/QG4l1G89fzG*iNT/O6[*[odBb', NULL, NULL),
(2, 13, '2025-09-10 19:20:33', '2025-09-11 03:20:33', '13NBHb}2T(r5ZmNAWyh(wc1BcLHZ/{', NULL, NULL),
(3, 13, '2025-09-10 19:20:54', '2025-09-11 03:20:54', 'tuSQa2@&ZE$OxI]}rUnsIdUIGCfvhC', NULL, NULL),
(4, 13, '2025-09-10 19:21:02', '2025-09-11 03:21:02', ']@RBo{ur*i5[}1voeBlX}qUcq2hq0C', NULL, NULL),
(5, 13, '2025-09-10 19:24:26', '2025-09-11 03:24:26', '{9k&f7mDrOkEDNPJVTF8WyHBvs]gs%', NULL, NULL),
(6, 13, '2025-09-10 19:24:49', '2025-09-11 03:24:49', '1/]G4W/Bre11e0k6oB0tpwIqSDvWVZ', NULL, NULL),
(7, 13, '2025-09-10 19:27:46', '2025-09-11 03:27:46', 'oickH24cS1(YzG]#C%BGtihmH1$@*a', NULL, NULL),
(8, 13, '2025-09-10 19:32:10', '2025-09-11 03:32:10', 'W9dA/nV$b0POP*YleP]PZtL]YKRCFH', NULL, NULL),
(9, 13, '2025-09-10 19:35:56', '2025-09-11 03:35:56', 'jo&ogB%YGV4eWJ#ZgOdZ0Jv%mj4CwC', NULL, NULL),
(10, 13, '2025-09-10 19:36:06', '2025-09-11 03:36:06', 'Sm{ELWpFXKrNk0t9atDLd2c7RYd&uf', NULL, NULL),
(11, 13, '2025-09-10 19:36:36', '2025-09-11 03:36:36', 'Kuie/GsR}Wpn12LiP}KZ{[Ph0rQD0y', NULL, NULL),
(12, 13, '2025-09-10 19:37:00', '2025-09-11 03:37:00', 'TZ{p9EYoXs}rE@YLhyDl4tV1@*ne/j', NULL, NULL),
(13, 13, '2025-09-10 19:40:12', '2025-09-11 03:40:12', 'oaSr9Fmt#x3E*(ph#sJUcHXZoFZtB[', NULL, NULL),
(14, 13, '2025-09-10 19:40:41', '2025-09-11 03:40:41', 'jTcUYid6dc#SqWdey[jCDn]LO/qZyl', NULL, NULL),
(15, 13, '2025-09-10 19:46:26', '2025-09-11 03:46:26', 'OOZ)Ml%#Q0Q2NYf5Jx2uuwG@HFIrE@', NULL, NULL),
(16, 13, '2025-09-10 19:51:44', '2025-09-10 20:04:00', 'HSsV*P6Ym7d)m03D(i9#3elCnbX&o&', NULL, NULL),
(17, 13, '2025-09-10 20:03:18', '2025-09-10 20:04:39', 't2&RYzzz44)2]t9uja9DZ&l%]PKdVm', NULL, NULL),
(18, 13, '2025-09-10 20:03:21', '2025-09-10 20:05:21', 'YJlgHrXHMHYbPs{C@YYK5WHeveF{/C', NULL, NULL),
(19, 13, '2025-09-10 20:22:01', '2025-09-10 20:23:02', 'v%ru$KETE[OALFKapduj4kkoR@p]eX', NULL, NULL),
(20, 13, '2025-09-10 20:22:19', '2025-09-10 20:30:31', '3jjY6R%LD&s7qAl&l[tD9RAqTIo88e', NULL, NULL),
(21, 13, '2025-09-10 20:29:44', '2025-09-10 20:30:45', 'Odd(#PBWyRC$xiEcEUMDTif7x3z78e', NULL, NULL),
(22, 13, '2025-09-10 20:35:26', '2025-09-10 20:41:10', 'C{%UM1OEJ2mTLFM[(tn{e$C48nX$mU', NULL, NULL),
(23, 18, '2025-09-10 20:40:22', '2025-09-10 20:47:51', '1SoUfmxJ*#C7[#Q@2gN}FYC7fyeb/]', NULL, NULL),
(24, 13, '2025-09-10 20:46:59', '2025-09-10 20:48:00', 'C$JbE19NM0$k{Z9SmpQ*%60@I}RQRZ', NULL, NULL),
(25, 13, '2025-09-10 20:50:03', '2025-09-10 21:30:00', 'H4Y[qlSs14TL92dnLvcCZy7W86yBaN', NULL, NULL),
(26, 13, '2025-09-10 21:29:11', '2025-09-10 22:47:43', 'f@mbJxwHM1XFrZWWt/YRp1AhY@*&RP', NULL, NULL),
(27, 13, '2025-09-10 22:58:54', '2025-09-11 06:19:25', 'B&[P&7L@R*}0f8V#nVW*TyrZtKOHIl', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `id_client_api` int(11) NOT NULL,
  `token` varchar(200) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`id`, `id_client_api`, `token`, `fecha_registro`, `estado`) VALUES
(1, 1, 'JVJcNS%Vlqur1n8C(Q%1SB*55O@ShYnR', '2025-10-02 06:05:37', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `dni` varchar(11) NOT NULL,
  `nombres_apellidos` varchar(140) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `password` varchar(1000) NOT NULL,
  `id_dependencia` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `tipo_usuario` enum('ADMINISTRATIVO','DOCENTE','ESTUDIANTE','PERSONAL_APOYO') NOT NULL,
  `rol_sistema` enum('ADMINISTRADOR','SUPERVISOR','OPERADOR','CONSULTA') DEFAULT 'CONSULTA',
  `estado` int(11) NOT NULL DEFAULT 1,
  `reset_password` int(1) NOT NULL DEFAULT 0,
  `token_password` varchar(30) NOT NULL DEFAULT '',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `dni`, `nombres_apellidos`, `correo`, `telefono`, `password`, `id_dependencia`, `id_carrera`, `tipo_usuario`, `rol_sistema`, `estado`, `reset_password`, `token_password`, `ultimo_acceso`, `fecha_registro`, `fecha_actualizacion`) VALUES
(13, '10123456', 'Luis Alberto Ramos', 'luis.ramos@instituto.edu.pe', '946123456', '$2b$10$fMEaHoPW3rXV3hQh/l3eKOQ.YtKtGLSk39Q1oFnJqT/nKFUze/hKu', 2, 1, 'ADMINISTRATIVO', 'ADMINISTRADOR', 1, 1, '1Ih1qD/sS%UJ@1SyAUd]tme2/T*%&I', NULL, '2025-09-11 00:16:02', '2025-09-11 01:27:55'),
(14, '10234567', 'Ana MarÃ­a Quispe', 'ana.quispe@instituto.edu.pe', '946123457', '$2b$10$YjRmKmfibz9A6p4ypgDrHuqgrwzAfNCoHONQ/ftnCcQ2RytIEOapu', 2, 1, 'DOCENTE', 'OPERADOR', 1, 1, '', NULL, '2025-09-11 00:16:02', '2025-09-11 00:19:41'),
(15, '10345678', 'Carlos Miguel Huaman', 'carlos.huaman@instituto.edu.pe', '946123458', '$2b$10$92ZYrFFGevGawieCHBMLDeMJ219/mqjn1MWuSiInq6yoYYyhOg5ji', 3, 3, 'DOCENTE', 'OPERADOR', 1, 1, '', NULL, '2025-09-11 00:16:02', '2025-09-11 00:19:41'),
(16, '10456789', 'MarÃ­a Fernanda Ortiz', 'maria.ortiz@instituto.edu.pe', '946123459', '$2b$10$1syakDf0dy9PFrBR5qXLDuRDbanxlpUtAGBxU3u9y2YyHJqum4VzW', 2, 1, 'ESTUDIANTE', 'CONSULTA', 1, 1, '', NULL, '2025-09-11 00:16:02', '2025-09-11 00:19:41'),
(17, '10567890', 'Javier Enrique Sosa', 'javier.sosa@instituto.edu.pe', '946123460', '$2b$10$ZUnnlc8uCtXo7cgasf1Cau6yDTw3lJ9NbtMi6bKO248380l6xhGM6', 4, 4, 'PERSONAL_APOYO', 'OPERADOR', 1, 1, '', NULL, '2025-09-11 00:16:02', '2025-09-11 00:19:41'),
(18, '71816086', 'Alexis Gabriel VALDIVIA TRUCIOS', 'valdivia@gmail.com', '974653924', '$2y$10$2.1/w3XToUivOhtULplNq.5rNHAeItt2NGKsZaoS/gGGxzRLs6G8u', NULL, NULL, 'ADMINISTRATIVO', 'CONSULTA', 1, 0, '', NULL, '2025-09-10 20:40:05', '2025-09-11 01:40:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD PRIMARY KEY (`id_bien`),
  ADD UNIQUE KEY `codigo_patrimonial` (`codigo_patrimonial`),
  ADD KEY `idx_codigo_patrimonial` (`codigo_patrimonial`),
  ADD KEY `idx_nombre_bien` (`nombre_bien`),
  ADD KEY `idx_estado_bien` (`estado_bien`),
  ADD KEY `idx_dependencia` (`id_dependencia`),
  ADD KEY `idx_categoria` (`id_categoria`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id_carrera`),
  ADD UNIQUE KEY `codigo_carrera` (`codigo_carrera`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `codigo_categoria` (`codigo_categoria`);

--
-- Indices de la tabla `client_api`
--
ALTER TABLE `client_api`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ruc` (`ruc`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `cont_request`
--
ALTER TABLE `cont_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`id_token`),
  ADD KEY `idx_tipo_mes` (`tipo`,`fecha`);

--
-- Indices de la tabla `dependencias`
--
ALTER TABLE `dependencias`
  ADD PRIMARY KEY (`id_dependencia`),
  ADD UNIQUE KEY `codigo_dependencia` (`codigo_dependencia`);

--
-- Indices de la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_bien` (`id_bien`),
  ADD KEY `idx_usuario_log` (`id_usuario`),
  ADD KEY `idx_fecha_log` (`fecha_accion`),
  ADD KEY `idx_accion` (`accion`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `id_dependencia_origen` (`id_dependencia_origen`),
  ADD KEY `id_dependencia_destino` (`id_dependencia_destino`),
  ADD KEY `idx_bien_movimiento` (`id_bien`),
  ADD KEY `idx_fecha_movimiento` (`fecha_ejecucion`),
  ADD KEY `idx_tipo_movimiento` (`tipo_movimiento`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_usuario_sesion` (`id_usuario`),
  ADD KEY `idx_fecha_inicio` (`fecha_hora_inicio`);

--
-- Indices de la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `idx_client_api` (`id_client_api`),
  ADD KEY `idx_fecha_registro` (`fecha_registro`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni` (`dni`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_dependencia` (`id_dependencia`),
  ADD KEY `id_carrera` (`id_carrera`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bienes`
--
ALTER TABLE `bienes`
  MODIFY `id_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `client_api`
--
ALTER TABLE `client_api`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cont_request`
--
ALTER TABLE `cont_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `dependencias`
--
ALTER TABLE `dependencias`
  MODIFY `id_dependencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bienes`
--
ALTER TABLE `bienes`
  ADD CONSTRAINT `bienes_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`),
  ADD CONSTRAINT `bienes_ibfk_2` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id_dependencia`);

--
-- Filtros para la tabla `cont_request`
--
ALTER TABLE `cont_request`
  ADD CONSTRAINT `cont_request_ibfk_1` FOREIGN KEY (`id_token`) REFERENCES `tokens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  ADD CONSTRAINT `log_actividades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `log_actividades_ibfk_2` FOREIGN KEY (`id_bien`) REFERENCES `bienes` (`id_bien`);

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`id_bien`) REFERENCES `bienes` (`id_bien`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`id_dependencia_origen`) REFERENCES `dependencias` (`id_dependencia`),
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`id_dependencia_destino`) REFERENCES `dependencias` (`id_dependencia`);

--
-- Filtros para la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD CONSTRAINT `sesiones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `tokens_ibfk_1` FOREIGN KEY (`id_client_api`) REFERENCES `client_api` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id_dependencia`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
