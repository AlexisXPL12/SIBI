-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-09-2025 a las 13:26:13
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
-- Base de datos: `sibi`
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
(1, 'CP-001-2024', NULL, 'Laptop HP Pavilion', 'Laptop para laboratorio de programación', 'HP', 'Pavilion 15', NULL, NULL, NULL, 1, 2, 'Laboratorio de Programación', NULL, '2024-01-15', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(2, 'CP-002-2024', NULL, 'Proyector Epson', 'Proyector para aulas', 'Epson', 'PowerLite X41+', NULL, NULL, NULL, 5, 2, 'Aula 201', NULL, '2024-01-20', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(3, 'HE-001-2024', NULL, 'Taladro Eléctrico', 'Taladro para taller de mecánica', 'DeWalt', 'DW511', NULL, NULL, NULL, 3, 4, 'Taller Principal', NULL, '2024-02-01', NULL, NULL, 'ACTIVO', 'NUEVO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(4, 'MO-001-2024', NULL, 'Escritorio de Oficina', 'Escritorio para área administrativa', 'Steelcase', 'Series 7', NULL, NULL, NULL, 4, 1, 'Oficina Principal', NULL, '2024-01-10', NULL, NULL, 'ACTIVO', 'BUENO', NULL, 1, 'admin', '2025-09-03 04:32:51', '2025-09-03 04:32:51');

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
(1, 'CAR-001', 'Computación e Informática', 'Carrera técnica en desarrollo de software y sistemas', 6, 'Ing. Carlos López', 'ACTIVO', '2025-09-03 04:32:51'),
(2, 'CAR-002', 'Electrotecnia Industrial', 'Carrera técnica en instalaciones y sistemas eléctricos', 6, 'Ing. María García', 'ACTIVO', '2025-09-03 04:32:51'),
(3, 'CAR-003', 'Mecánica Automotriz', 'Carrera técnica en reparación y mantenimiento automotriz', 6, 'Téc. Juan Rodríguez', 'ACTIVO', '2025-09-03 04:32:51'),
(4, 'CAR-004', 'Construcción Civil', 'Carrera técnica en edificación y obras civiles', 6, 'Ing. Ana Martínez', 'ACTIVO', '2025-09-03 04:32:51'),
(5, 'CAR-005', 'Administración de Empresas', 'Carrera técnica en gestión empresarial', 6, 'Lic. Luis Fernández', 'ACTIVO', '2025-09-03 04:32:51');

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
(1, 'DEP-001', 'Administración General', 'Área administrativa y dirección', 'Director General', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(2, 'DEP-002', 'Computación e Informática', 'Carrera de computación e informática', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(3, 'DEP-003', 'Electrotecnia Industrial', 'Carrera de electricidad industrial', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(4, 'DEP-004', 'Mecánica Automotriz', 'Carrera de mecánica automotriz', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(5, 'DEP-005', 'Construcción Civil', 'Carrera de construcción civil', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(6, 'DEP-006', 'Administración de Empresas', 'Carrera de administración', 'Jefe de Carrera', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(7, 'DEP-007', 'Biblioteca', 'Centro de recursos bibliográficos', 'Bibliotecario', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(8, 'DEP-008', 'Laboratorios', 'Laboratorios especializados', 'Coordinador de Laboratorios', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(9, 'DEP-009', 'Talleres', 'Talleres de práctica', 'Coordinador de Talleres', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51'),
(10, 'DEP-010', 'Recursos Humanos', 'Gestión de personal', 'Jefe de RRHH', NULL, NULL, 'ACTIVO', '2025-09-03 04:32:51', '2025-09-03 04:32:51');

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
(1, '12345678', 'Juan Pérez Administrador', 'admin@instituto.edu.pe', '987654321', '$2y$10$example_hash', 1, NULL, 'ADMINISTRATIVO', 'ADMINISTRADOR', 1, 0, '', NULL, '2025-09-02 23:32:51', '2025-09-03 04:32:51'),
(2, '87654321', 'María García Docente', 'docente@instituto.edu.pe', '987654322', '$2y$10$example_hash', 2, 1, 'DOCENTE', 'OPERADOR', 1, 0, '', NULL, '2025-09-02 23:32:51', '2025-09-03 04:32:51');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_bienes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_bienes` (
`id_bien` int(11)
,`codigo_patrimonial` varchar(30)
,`codigo_barra` varchar(50)
,`nombre_bien` varchar(300)
,`descripcion` text
,`marca` varchar(100)
,`modelo` varchar(100)
,`serie` varchar(100)
,`color` varchar(50)
,`ubicacion_especifica` varchar(200)
,`codigo_categoria` varchar(20)
,`nombre_categoria` varchar(150)
,`codigo_dependencia` varchar(20)
,`nombre_dependencia` varchar(200)
,`responsable` varchar(150)
,`fecha_adquisicion` date
,`fecha_ingreso` date
,`estado_bien` enum('ACTIVO','BAJA','MANTENIMIENTO','PRESTADO')
,`condicion_bien` enum('NUEVO','BUENO','REGULAR','MALO','INSERVIBLE')
,`observaciones` text
,`fecha_actualizacion` timestamp
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_resumen_categoria`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_resumen_categoria` (
`id_categoria` int(11)
,`codigo_categoria` varchar(20)
,`nombre_categoria` varchar(150)
,`total_bienes` bigint(21)
,`bienes_activos` decimal(22,0)
,`antiguedad_promedio` decimal(8,4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_resumen_dependencia`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_resumen_dependencia` (
`id_dependencia` int(11)
,`codigo_dependencia` varchar(20)
,`nombre_dependencia` varchar(200)
,`responsable` varchar(150)
,`total_bienes` bigint(21)
,`bienes_activos` decimal(22,0)
,`bienes_mantenimiento` decimal(22,0)
,`bienes_baja` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_bienes`
--
DROP TABLE IF EXISTS `vista_bienes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_bienes`  AS SELECT `b`.`id_bien` AS `id_bien`, `b`.`codigo_patrimonial` AS `codigo_patrimonial`, `b`.`codigo_barra` AS `codigo_barra`, `b`.`nombre_bien` AS `nombre_bien`, `b`.`descripcion` AS `descripcion`, `b`.`marca` AS `marca`, `b`.`modelo` AS `modelo`, `b`.`serie` AS `serie`, `b`.`color` AS `color`, `b`.`ubicacion_especifica` AS `ubicacion_especifica`, `c`.`codigo_categoria` AS `codigo_categoria`, `c`.`nombre_categoria` AS `nombre_categoria`, `d`.`codigo_dependencia` AS `codigo_dependencia`, `d`.`nombre_dependencia` AS `nombre_dependencia`, `d`.`responsable` AS `responsable`, `b`.`fecha_adquisicion` AS `fecha_adquisicion`, `b`.`fecha_ingreso` AS `fecha_ingreso`, `b`.`estado_bien` AS `estado_bien`, `b`.`condicion_bien` AS `condicion_bien`, `b`.`observaciones` AS `observaciones`, `b`.`fecha_actualizacion` AS `fecha_actualizacion` FROM ((`bienes` `b` left join `categorias` `c` on(`b`.`id_categoria` = `c`.`id_categoria`)) left join `dependencias` `d` on(`b`.`id_dependencia` = `d`.`id_dependencia`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resumen_categoria`
--
DROP TABLE IF EXISTS `vista_resumen_categoria`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resumen_categoria`  AS SELECT `c`.`id_categoria` AS `id_categoria`, `c`.`codigo_categoria` AS `codigo_categoria`, `c`.`nombre_categoria` AS `nombre_categoria`, count(`b`.`id_bien`) AS `total_bienes`, sum(case when `b`.`estado_bien` = 'ACTIVO' then 1 else 0 end) AS `bienes_activos`, avg(year(curdate()) - year(`b`.`fecha_adquisicion`)) AS `antiguedad_promedio` FROM (`categorias` `c` left join `bienes` `b` on(`c`.`id_categoria` = `b`.`id_categoria`)) WHERE `c`.`estado` = 'ACTIVO' GROUP BY `c`.`id_categoria`, `c`.`codigo_categoria`, `c`.`nombre_categoria` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resumen_dependencia`
--
DROP TABLE IF EXISTS `vista_resumen_dependencia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resumen_dependencia`  AS SELECT `d`.`id_dependencia` AS `id_dependencia`, `d`.`codigo_dependencia` AS `codigo_dependencia`, `d`.`nombre_dependencia` AS `nombre_dependencia`, `d`.`responsable` AS `responsable`, count(`b`.`id_bien`) AS `total_bienes`, sum(case when `b`.`estado_bien` = 'ACTIVO' then 1 else 0 end) AS `bienes_activos`, sum(case when `b`.`estado_bien` = 'MANTENIMIENTO' then 1 else 0 end) AS `bienes_mantenimiento`, sum(case when `b`.`estado_bien` = 'BAJA' then 1 else 0 end) AS `bienes_baja` FROM (`dependencias` `d` left join `bienes` `b` on(`d`.`id_dependencia` = `b`.`id_dependencia`)) WHERE `d`.`estado` = 'ACTIVO' GROUP BY `d`.`id_dependencia`, `d`.`codigo_dependencia`, `d`.`nombre_dependencia`, `d`.`responsable` ;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_dependencia`) REFERENCES `dependencias` (`id_dependencia`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_carrera`) REFERENCES `carreras` (`id_carrera`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
