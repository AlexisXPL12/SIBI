-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 23-10-2025 a las 16:25:20
-- Versión del servidor: 5.7.39
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sibi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bienes`
--

CREATE TABLE `bienes` (
  `id_bien` int(11) NOT NULL,
  `codigo_patrimonial` varchar(30) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `codigo_barra` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `nombre_bien` varchar(300) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci,
  `marca` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `serie` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `color` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `dimensiones` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `id_dependencia` int(11) DEFAULT NULL,
  `ubicacion_especifica` varchar(200) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_adquisicion` date DEFAULT NULL,
  `fecha_ingreso` date NOT NULL,
  `numero_factura` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `numero_orden_compra` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_bien` enum('ACTIVO','BAJA','MANTENIMIENTO','PRESTADO') COLLATE utf8mb4_spanish2_ci DEFAULT 'ACTIVO',
  `condicion_bien` enum('NUEVO','BUENO','REGULAR','MALO','INSERVIBLE') COLLATE utf8mb4_spanish2_ci DEFAULT 'BUENO',
  `observaciones` text COLLATE utf8mb4_spanish2_ci,
  `es_inventariable` tinyint(1) DEFAULT '1',
  `usuario_registro` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `bienes`
--

INSERT INTO `bienes` (`id_bien`, `codigo_patrimonial`, `codigo_barra`, `nombre_bien`, `descripcion`, `marca`, `modelo`, `serie`, `color`, `dimensiones`, `id_categoria`, `id_dependencia`, `ubicacion_especifica`, `fecha_adquisicion`, `fecha_ingreso`, `numero_factura`, `numero_orden_compra`, `estado_bien`, `condicion_bien`, `observaciones`, `es_inventariable`, `usuario_registro`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'CP-001-2024', NULL, 'Laptop HP Pavilion', 'Laptop para laboratorio de programaciÃ³n', 'HP', 'Pavilion 15', NULL, NULL, NULL, 1, 2, 'Laboratorio de ProgramaciÃ³n', NULL, '2024-01-15', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(2, 'CP-002-2024', NULL, 'Proyector Epson', 'Proyector para aulas', 'Epson', 'PowerLite X41+', NULL, NULL, NULL, 5, 2, 'Aula 201', NULL, '2024-01-20', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(3, 'HE-001-2024', NULL, 'Taladro ElÃ©ctrico', 'Taladro para taller de mecÃ¡nica', 'DeWalt', 'DW511', NULL, NULL, NULL, 3, 4, 'Taller Principal', NULL, '2024-02-01', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(4, 'MO-001-2024', NULL, 'Escritorio de Oficina', 'Escritorio para Ã¡rea administrativa', 'Steelcase', 'Series 7', NULL, NULL, NULL, 4, 1, 'Oficina Principal', NULL, '2024-01-10', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(6, '12345', NULL, 'Laptop', 'Is very nice', 'Asus', 'i5', '715236123', 'Negro', '18x30x3 cm', 1, 10, 'Contabiidad', '2025-02-12', '2025-02-12', NULL, NULL, 'ACTIVO', 'NUEVO', 'Ninguna', 1, '18', '2025-10-22 23:29:45', '2025-10-22 23:29:45'),
(7, 'IES-001-2025', NULL, 'Laptop Dell Inspiron 15', 'Laptop para uso académico - 8GB RAM, 256GB SSD', 'Dell', 'Inspiron 15 3520', 'DL2025ABC001', 'Negro', NULL, 1, 2, 'Laboratorio de Programación - Mesa 1', '2025-01-15', '2025-01-15', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye cargador original', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(8, 'IES-002-2025', NULL, 'Laptop Lenovo ThinkPad', 'Laptop para docentes - 16GB RAM, 512GB SSD', 'Lenovo', 'ThinkPad E14', 'LN2025XYZ002', 'Negro', NULL, 1, 2, 'Oficina de Coordinación', '2025-01-20', '2025-01-20', NULL, NULL, 'ACTIVO', 'NUEVO', 'Sistema operativo Windows 11 Pro', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(9, 'IES-003-2025', NULL, 'Computadora de Escritorio HP', 'PC All-in-One para administración', 'HP', 'All-in-One 24-df1', 'HP2025DEF003', 'Blanco', NULL, 1, 1, 'Secretaría Académica', '2025-02-01', '2025-02-01', NULL, NULL, 'ACTIVO', 'NUEVO', 'Monitor de 24 pulgadas integrado', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(10, 'IES-004-2025', NULL, 'Tablet Samsung Galaxy Tab', 'Tablet para presentaciones móviles', 'Samsung', 'Galaxy Tab S8', 'SM2025GHI004', 'Gris', NULL, 1, 1, 'Dirección General', '2025-02-10', '2025-02-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye stylus y funda protectora', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(11, 'IES-005-2025', NULL, 'Laptop Asus VivoBook', 'Laptop para laboratorio de redes', 'Asus', 'VivoBook 15 X513', 'AS2025JKL005', 'Azul', NULL, 1, 2, 'Laboratorio de Redes - Estación 5', '2025-02-15', '2025-02-15', NULL, NULL, 'ACTIVO', 'NUEVO', 'Configurado con software de simulación', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(12, 'IES-006-2025', NULL, 'Osciloscopio Digital Tektronix', 'Osciloscopio de 100 MHz para laboratorio', 'Tektronix', 'TBS 2104', 'TK2025MNO006', 'Negro/Gris', NULL, 2, 3, 'Laboratorio de Electrotecnia - Mesa 2', '2025-01-25', '2025-01-25', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye sondas y manual de usuario', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(13, 'IES-007-2025', NULL, 'Multímetro Digital Fluke', 'Multímetro de precisión industrial', 'Fluke', '87V', 'FK2025PQR007', 'Amarillo', NULL, 2, 3, 'Laboratorio de Electrotecnia - Gaveta 3', '2025-01-25', '2025-01-25', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con estuche de transporte', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(14, 'IES-008-2025', NULL, 'Fuente de Alimentación Regulable', 'Fuente DC 0-30V, 0-5A', 'GW Instek', 'GPS-3030DD', 'GW2025STU008', 'Gris', NULL, 2, 3, 'Laboratorio de Electrotecnia - Estante A', '2025-02-05', '2025-02-05', NULL, NULL, 'ACTIVO', 'NUEVO', 'Doble salida independiente', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(15, 'IES-009-2025', NULL, 'Microscopio Biológico', 'Microscopio trinocular LED', 'Olympus', 'CX23', 'OL2025VWX009', 'Blanco', NULL, 2, 8, 'Laboratorio de Ciencias - Mesa Central', '2025-02-20', '2025-02-20', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye oculares y objetivos', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(16, 'IES-010-2025', NULL, 'Generador de Funciones', 'Generador de señales hasta 20 MHz', 'Rigol', 'DG1022Z', 'RG2025YZA010', 'Negro', NULL, 2, 3, 'Laboratorio de Electrotecnia - Mesa 5', '2025-03-01', '2025-03-01', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con salida USB y software', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(17, 'IES-011-2025', NULL, 'Taladro Percutor Bosch', 'Taladro eléctrico profesional 850W', 'Bosch', 'GSB 16 RE', 'BS2025BCD011', 'Azul', NULL, 3, 4, 'Taller de Mecánica - Caja de Herramientas 1', '2025-01-30', '2025-01-30', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye maletín y brocas', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(18, 'IES-012-2025', NULL, 'Esmeril Angular Makita', 'Esmeril de 4.5 pulgadas 720W', 'Makita', 'GA4530', 'MK2025EFG012', 'Verde', NULL, 3, 4, 'Taller de Mecánica - Estante B', '2025-01-30', '2025-01-30', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con discos de corte y desbaste', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(19, 'IES-013-2025', NULL, 'Juego de Llaves Combinadas', 'Set de 12 llaves métricas 6-19mm', 'Stanley', 'STMT74175', 'ST2025HIJ013', 'Cromado', NULL, 3, 4, 'Taller de Mecánica - Panel de Herramientas', '2025-02-08', '2025-02-08', NULL, NULL, 'ACTIVO', 'NUEVO', 'Acero cromo-vanadio', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(20, 'IES-014-2025', NULL, 'Soldadora Inverter Lincoln', 'Soldadora eléctrica 160A', 'Lincoln Electric', 'Invertec 160S', 'LE2025KLM014', 'Rojo', NULL, 3, 9, 'Taller de Soldadura - Estación 2', '2025-02-12', '2025-02-12', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con cables y pinza', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(21, 'IES-015-2025', NULL, 'Compresor de Aire Ingersoll', 'Compresor portátil 50L 2HP', 'Ingersoll Rand', 'SS3L2', 'IR2025NOP015', 'Rojo', NULL, 3, 4, 'Taller de Mecánica - Zona de Compresores', '2025-02-25', '2025-02-25', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye manguera y accesorios', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(22, 'IES-016-2025', NULL, 'Escritorio Ejecutivo de Madera', 'Escritorio de 1.60m con cajonera', 'Steelcase', 'Series 7 Executive', NULL, 'Caoba', NULL, 4, 1, 'Dirección General', '2025-01-10', '2025-01-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Madera de primera calidad', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(23, 'IES-017-2025', NULL, 'Silla Ergonómica de Oficina', 'Silla giratoria con soporte lumbar', 'Herman Miller', 'Aeron Size B', NULL, 'Negro', NULL, 4, 1, 'Dirección General', '2025-01-10', '2025-01-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Ajuste de altura y brazos', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(24, 'IES-018-2025', NULL, 'Mesa de Trabajo para Laboratorio', 'Mesa de acero inoxidable 2m x 1m', 'Lab Pro', 'LT-2000', NULL, 'Acero', NULL, 4, 8, 'Laboratorio Multifuncional', '2025-01-18', '2025-01-18', NULL, NULL, 'ACTIVO', 'NUEVO', 'Resistente a químicos', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(25, 'IES-019-2025', NULL, 'Estante Metálico Industrial', 'Estantería 5 niveles 2m altura', 'Metalsistem', 'MS-5N200', NULL, 'Gris', NULL, 4, 9, 'Almacén de Talleres', '2025-02-03', '2025-02-03', NULL, NULL, 'ACTIVO', 'NUEVO', 'Capacidad 200kg por nivel', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(26, 'IES-020-2025', NULL, 'Sillas Apilables para Aula', 'Set de 30 sillas con paleta', 'Mobiliario Escolar', 'ME-AP30', NULL, 'Azul', NULL, 4, 2, 'Aula 301', '2025-02-15', '2025-02-15', NULL, NULL, 'ACTIVO', 'NUEVO', 'Estructura metálica reforzada', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(27, 'IES-021-2025', NULL, 'Proyector Epson PowerLite', 'Proyector 3LCD 3600 lúmenes', 'Epson', 'PowerLite X49', 'EP2025QRS021', 'Blanco', NULL, 5, 2, 'Aula 201', '2025-01-22', '2025-01-22', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye control remoto y cables', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(28, 'IES-022-2025', NULL, 'Pantalla de Proyección Eléctrica', 'Pantalla motorizada 100 pulgadas', 'Elite Screens', 'Spectrum Electric', NULL, 'Blanco', NULL, 5, 2, 'Aula 201', '2025-01-22', '2025-01-22', NULL, NULL, 'ACTIVO', 'NUEVO', 'Control remoto y pared', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(29, 'IES-023-2025', NULL, 'Sistema de Audio Bose', 'Parlantes activos 200W', 'Bose', 'L1 Compact', 'BO2025TUV023', 'Negro', NULL, 5, 1, 'Auditorio Principal', '2025-02-05', '2025-02-05', NULL, NULL, 'ACTIVO', 'NUEVO', 'Sistema portátil con trípode', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(30, 'IES-024-2025', NULL, 'Cámara de Videoconferencia', 'Cámara HD 1080p con micrófono', 'Logitech', 'MeetUp', 'LG2025WXY024', 'Negro', NULL, 5, 1, 'Sala de Reuniones', '2025-02-18', '2025-02-18', NULL, NULL, 'ACTIVO', 'NUEVO', 'Ángulo de visión 120°', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(31, 'IES-025-2025', NULL, 'Televisor Smart LG', 'TV LED 65 pulgadas 4K', 'LG', '65UN7300', 'LG2025ZAB025', 'Negro', NULL, 5, 7, 'Sala de Espera', '2025-03-01', '2025-03-01', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye soporte de pared', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(32, 'IES-026-2025', NULL, 'Torno Paralelo Industrial', 'Torno de 1m entre puntos', 'Optimum', 'OPTIturn TU 3008', 'OP2025CDE026', 'Verde', NULL, 6, 9, 'Taller de Mecánica de Producción', '2025-02-10', '2025-02-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Incluye accesorios básicos', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(33, 'IES-027-2025', NULL, 'Fresadora Universal', 'Fresadora vertical/horizontal', 'Optimum', 'OPTImill MF4', 'OP2025FGH027', 'Verde', NULL, 6, 9, 'Taller de Mecánica de Producción', '2025-02-10', '2025-02-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con plato divisor', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(34, 'IES-028-2025', NULL, 'Sierra de Cinta para Metal', 'Sierra industrial 220V', 'Jet', 'HVBS-710G', 'JT2025IJK028', 'Azul', NULL, 6, 9, 'Taller de Corte', '2025-02-20', '2025-02-20', NULL, NULL, 'ACTIVO', 'NUEVO', 'Corte angular hasta 60°', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(35, 'IES-029-2025', NULL, 'Prensa Hidráulica', 'Prensa de 20 toneladas', 'Bahco', 'BH6P20', 'BH2025LMN029', 'Rojo', NULL, 6, 4, 'Taller de Mecánica Automotriz', '2025-03-05', '2025-03-05', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con manómetro de presión', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(36, 'IES-030-2025', NULL, 'Elevador de Autos', 'Elevador hidráulico 4 toneladas', 'Rotary', 'SM14N', 'RT2025OPQ030', 'Rojo', NULL, 6, 4, 'Taller de Mecánica Automotriz - Fosa 1', '2025-03-10', '2025-03-10', NULL, NULL, 'ACTIVO', 'NUEVO', 'Instalación profesional incluida', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(37, 'IES-031-2025', NULL, 'Impresora Multifuncional HP', 'Impresora láser color WiFi', 'HP', 'LaserJet Pro MFP M479fdw', 'HP2025RST031', 'Blanco', NULL, 7, 1, 'Secretaría Académica', '2025-01-12', '2025-01-12', NULL, NULL, 'ACTIVO', 'NUEVO', 'Imprime, escanea, copia y fax', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(38, 'IES-032-2025', NULL, 'Fotocopiadora Xerox', 'Copiadora multifuncional A3', 'Xerox', 'WorkCentre 5330', 'XR2025UVW032', 'Gris', NULL, 7, 1, 'Centro de Copiado', '2025-01-28', '2025-01-28', NULL, NULL, 'ACTIVO', 'NUEVO', 'Capacidad 30 ppm', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(39, 'IES-033-2025', NULL, 'Escáner Documental Epson', 'Escáner de alta velocidad', 'Epson', 'WorkForce DS-780N', 'EP2025XYZ033', 'Negro', NULL, 7, 1, 'Archivo General', '2025-02-16', '2025-02-16', NULL, NULL, 'ACTIVO', 'NUEVO', 'Alimentador automático 100 hojas', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(40, 'IES-034-2025', NULL, 'Destructora de Documentos', 'Destructora nivel P-4', 'Fellowes', 'Powershred 99Ci', 'FW2025ABC034', 'Negro', NULL, 7, 1, 'Oficina Administrativa', '2025-02-22', '2025-02-22', NULL, NULL, 'ACTIVO', 'NUEVO', 'Corte cruzado seguro', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(41, 'IES-035-2025', NULL, 'Teléfono IP Cisco', 'Teléfono VoIP escritorio', 'Cisco', '8841', 'CS2025DEF035', 'Negro', NULL, 7, 1, 'Dirección General', '2025-03-03', '2025-03-03', NULL, NULL, 'ACTIVO', 'NUEVO', 'Pantalla a color 5 pulgadas', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(42, 'IES-036-2025', NULL, 'Refrigeradora Samsung', 'Refrigerador 2 puertas 400L', 'Samsung', 'RT38K5982BS', 'SM2025GHI036', 'Acero Inoxidable', NULL, 8, 1, 'Sala de Descanso Docente', '2025-01-14', '2025-01-14', NULL, NULL, 'ACTIVO', 'NUEVO', 'Tecnología No Frost', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(43, 'IES-037-2025', NULL, 'Microondas LG', 'Horno microondas 1.5 pies', 'LG', 'MS1535GIS', 'LG2025JKL037', 'Plateado', NULL, 8, 1, 'Sala de Descanso Docente', '2025-01-14', '2025-01-14', NULL, NULL, 'ACTIVO', 'NUEVO', 'Función grill', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(44, 'IES-038-2025', NULL, 'Dispensador de Agua Electrolux', 'Dispensador frío/caliente', 'Electrolux', 'EQAXF01TXWG', 'EL2025MNO038', 'Blanco', NULL, 8, 1, 'Recepción', '2025-02-02', '2025-02-02', NULL, NULL, 'ACTIVO', 'NUEVO', 'Con filtro purificador', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(45, 'IES-039-2025', NULL, 'Cafetera Industrial Oster', 'Cafetera de 12 tazas', 'Oster', 'BVSTDCDR5B', 'OS2025PQR039', 'Negro', NULL, 8, 1, 'Sala de Profesores', '2025-02-14', '2025-02-14', NULL, NULL, 'ACTIVO', 'NUEVO', 'Programable 24 horas', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43'),
(46, 'IES-040-2025', NULL, 'Ventilador Industrial Honeywell', 'Ventilador de pie 20 pulgadas', 'Honeywell', 'HSF600E', 'HN2025STU040', 'Negro', NULL, 8, 2, 'Aula 305', '2025-03-08', '2025-03-08', NULL, NULL, 'ACTIVO', 'NUEVO', '3 velocidades, oscilante', 1, 'admin', '2025-10-23 04:29:43', '2025-10-23 04:29:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id_carrera` int(11) NOT NULL,
  `codigo_carrera` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nombre_carrera` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci,
  `duracion_semestres` int(11) DEFAULT '6',
  `coordinador` varchar(150) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') COLLATE utf8mb4_spanish2_ci DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
  `codigo_categoria` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nombre_categoria` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci,
  `vida_util_anos` int(11) DEFAULT '10',
  `estado` enum('ACTIVO','INACTIVO') COLLATE utf8mb4_spanish2_ci DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `codigo_categoria`, `nombre_categoria`, `descripcion`, `vida_util_anos`, `estado`, `fecha_creacion`) VALUES
(1, 'CAT-001', 'Equipos de Cómputo', 'Computadoras, laptops, tablets', 4, 'ACTIVO', '2025-09-03 04:32:51'),
(2, 'CAT-002', 'Equipos de Laboratorio', 'Instrumentos y equipos para laboratorios técnicos', 8, 'ACTIVO', '2025-09-03 04:32:51'),
(3, 'CAT-003', 'Herramientas', 'Herramientas manuales y eléctricas', 6, 'ACTIVO', '2025-09-03 04:32:51'),
(4, 'CAT-004', 'Mobiliario', 'Escritorios, sillas, mesas de trabajo', 10, 'ACTIVO', '2025-09-03 04:32:51'),
(5, 'CAT-005', 'Equipos Audiovisuales', 'Proyectores, parlantes, pantallas', 5, 'ACTIVO', '2025-09-03 04:32:51'),
(6, 'CAT-006', 'Maquinaria', 'Máquinas para talleres y laboratorios', 12, 'ACTIVO', '2025-09-03 04:32:51'),
(7, 'CAT-007', 'Equipos de Oficina', 'Impresoras, fotocopiadoras', 5, 'ACTIVO', '2025-09-03 04:32:51'),
(8, 'CAT-008', 'Electrodomésticos', 'Refrigeradoras, microondas', 8, 'ACTIVO', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `client_api`
--

CREATE TABLE `client_api` (
  `id` int(11) NOT NULL,
  `ruc` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `razon_social` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
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
  `tipo` varchar(10) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencias`
--

CREATE TABLE `dependencias` (
  `id_dependencia` int(11) NOT NULL,
  `codigo_dependencia` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nombre_dependencia` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci,
  `responsable` varchar(150) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') COLLATE utf8mb4_spanish2_ci DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `dependencias`
--

INSERT INTO `dependencias` (`id_dependencia`, `codigo_dependencia`, `nombre_dependencia`, `descripcion`, `responsable`, `telefono`, `email`, `estado`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'DEP-001', 'Dirección General', 'Dirección y administración general de la institución', 'Director General', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(2, 'DEP-002', 'Secretaría Académica', 'Gestión académica y registro de estudiantes', 'Secretario Académico', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(3, 'DEP-003', 'Administración y Finanzas', 'Gestión administrativa y financiera', 'Jefe de Administración', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(4, 'DEP-004', 'Recursos Humanos', 'Gestión de personal docente y administrativo', 'Jefe de Recursos Humanos', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(5, 'DEP-005', 'Logística y Patrimonio', 'Control patrimonial y gestión logística', 'Jefe de Logística', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(6, 'DEP-006', 'Biblioteca', 'Centro de recursos bibliográficos y documentales', 'Bibliotecario', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(7, 'DEP-007', 'Laboratorios de Cómputo', 'Laboratorios de computación e informática', 'Coordinador de Laboratorios', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(8, 'DEP-008', 'Talleres Técnicos', 'Talleres de práctica para carreras técnicas', 'Coordinador de Talleres', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(9, 'DEP-009', 'Mantenimiento', 'Mantenimiento de infraestructura y equipos', 'Jefe de Mantenimiento', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(10, 'DEP-010', 'Servicios Generales', 'Limpieza, seguridad y servicios complementarios', 'Jefe de Servicios', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-10-23 05:15:14'),
(11, '321312', 'Laboratorio', 'dawdwad', 'Juan Julian', NULL, NULL, 'ACTIVO', '2025-10-21 10:42:05', '2025-10-21 10:42:05'),
(12, 'DEP-011', 'Bienestar Estudiantil', 'Atención y apoyo a estudiantes', 'Coordinador de Bienestar', NULL, NULL, 'ACTIVO', '2025-10-23 05:15:14', '2025-10-23 05:15:14'),
(13, 'DEP-012', 'Producción', 'Área de producción y servicios técnicos', 'Jefe de Producción', NULL, NULL, 'ACTIVO', '2025-10-23 05:15:14', '2025-10-23 05:15:14'),
(14, 'DEP-013', 'Investigación e Innovación', 'Proyectos de investigación y desarrollo', 'Coordinador de Investigación', NULL, NULL, 'ACTIVO', '2025-10-23 05:15:14', '2025-10-23 05:15:14'),
(15, 'DEP-014', 'Calidad Académica', 'Evaluación y mejora continua', 'Coordinador de Calidad', NULL, NULL, 'ACTIVO', '2025-10-23 05:15:14', '2025-10-23 05:15:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log_actividades`
--

CREATE TABLE `log_actividades` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_bien` int(11) DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion_accion` text COLLATE utf8mb4_spanish2_ci,
  `fecha_accion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `id_bien` int(11) DEFAULT NULL,
  `tipo_movimiento` enum('INGRESO','TRASLADO','BAJA','PRESTAMO','DEVOLUCION') COLLATE utf8mb4_spanish2_ci NOT NULL,
  `id_dependencia_origen` int(11) DEFAULT NULL,
  `id_dependencia_destino` int(11) DEFAULT NULL,
  `motivo` varchar(300) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_spanish2_ci,
  `documento_referencia` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `usuario_solicita` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `usuario_autoriza` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_solicitud` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_ejecucion` timestamp NULL DEFAULT NULL,
  `estado_movimiento` enum('PENDIENTE','EJECUTADO','CANCELADO') COLLATE utf8mb4_spanish2_ci DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id_movimiento`, `id_bien`, `tipo_movimiento`, `id_dependencia_origen`, `id_dependencia_destino`, `motivo`, `observaciones`, `documento_referencia`, `usuario_solicita`, `usuario_autoriza`, `fecha_solicitud`, `fecha_ejecucion`, `estado_movimiento`) VALUES
(1, 6, 'TRASLADO', 10, 7, '321231', 'eawe', 'Factura', '18', NULL, '2025-10-23 02:09:33', NULL, 'PENDIENTE'),
(2, 4, 'BAJA', 1, 9, 'de', 'dea', 'Informe Técnico', '18', NULL, '2025-10-23 03:53:13', NULL, 'PENDIENTE'),
(3, 4, 'PRESTAMO', 1, 10, 'dawd', 'awdw', 'Acta de Entrega', '13', NULL, '2025-10-23 04:06:50', NULL, 'PENDIENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora_inicio` datetime NOT NULL,
  `fecha_hora_fin` datetime NOT NULL,
  `token` varchar(30) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_spanish2_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sesiones`
--

INSERT INTO `sesiones` (`id`, `id_usuario`, `fecha_hora_inicio`, `fecha_hora_fin`, `token`, `ip_address`, `user_agent`) VALUES
(43, 18, '2025-10-21 05:17:59', '2025-10-21 05:26:23', 'YyVLVvrFWn3w1hZy8SNjSpx)h6f]#x', NULL, NULL),
(44, 18, '2025-10-21 05:28:42', '2025-10-21 05:48:20', 'bjo*ZQTT0sMVT{5RaSbTF5P4pYX2vB', NULL, NULL),
(45, 18, '2025-10-21 05:47:31', '2025-10-21 06:09:09', '%Cj#6T7R@exZ#AJ[p}y3O0&}8wdcct', NULL, NULL),
(46, 18, '2025-10-22 18:27:07', '2025-10-22 23:04:41', 'N1u*h3sgz{G*HU*GV3IaQG[)8$C9DE', NULL, NULL),
(47, 18, '2025-10-22 23:04:17', '2025-10-22 23:05:23', 'G[[R7Hl*p2[TncrIB@BBemKmxUkwmG', NULL, NULL),
(48, 13, '2025-10-22 23:05:44', '2025-10-22 23:07:53', '4()qnCo%VMH#%08Lc05$)3A1pkrkXi', NULL, NULL),
(49, 18, '2025-10-22 23:07:13', '2025-10-22 23:20:27', '9p$fjzXXqXtWlY4Wc)O9T3&IQWX}Jo', NULL, NULL),
(50, 18, '2025-10-22 23:19:56', '2025-10-23 00:16:21', 'Nyh{jFlFyRNx%RF@tPgNYcC#/K[V8r', NULL, NULL),
(51, 18, '2025-10-23 08:12:00', '2025-10-23 10:13:35', 'mAIppPr%HZ24J)ejLmOJG}%qDKZeJk', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `id_client_api` int(11) NOT NULL,
  `token` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tokens`
--

INSERT INTO `tokens` (`id`, `id_client_api`, `token`, `fecha_registro`, `estado`) VALUES
(2, 1, 'cffc7a66ee673b812e6d51b45d6dd2a0-20251023-1', '2025-10-23 08:24:05', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `dni` varchar(11) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `nombres_apellidos` varchar(140) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `password` varchar(1000) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `id_dependencia` int(11) DEFAULT NULL,
  `id_carrera` int(11) DEFAULT NULL,
  `tipo_usuario` enum('ADMINISTRATIVO','DOCENTE','ESTUDIANTE','PERSONAL_APOYO') COLLATE utf8mb4_spanish2_ci NOT NULL,
  `rol_sistema` enum('ADMINISTRADOR','SUPERVISOR','OPERADOR','CONSULTA') COLLATE utf8mb4_spanish2_ci DEFAULT 'CONSULTA',
  `estado` int(11) NOT NULL DEFAULT '1',
  `reset_password` int(1) NOT NULL DEFAULT '0',
  `token_password` varchar(30) COLLATE utf8mb4_spanish2_ci NOT NULL DEFAULT '',
  `ultimo_acceso` timestamp NULL DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
  MODIFY `id_bien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

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
  MODIFY `id_dependencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `log_actividades`
--
ALTER TABLE `log_actividades`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
