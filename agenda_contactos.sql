-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2026 a las 15:50:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda_contactos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(2, 'Amigos'),
(1, 'Familia'),
(5, 'Otro'),
(3, 'Trabajo'),
(4, 'Universidad');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contactos`
--

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `categoria_id` int(11) NOT NULL DEFAULT 5,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `favorito` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `contactos`
--

INSERT INTO `contactos` (`id`, `id_usuario`, `nombre`, `correo`, `telefono`, `ciudad`, `categoria_id`, `fecha_creacion`, `favorito`) VALUES
(1, 1, 'Fernanda Caiza', 'mariacaiza10@outlook.com', '0959689556', 'Quito', 5, '2026-11-11 04:08:43', 1),
(2, 1, 'Maria Guamán', 'maria.guaman@gmail.com', '0967650651', 'Guayaquil', 5, '2026-11-11 04:08:43', 0),
(4, 1, 'Hector Guamán', 'hector.guaman@example.com', '09940047005', 'Loja', 5, '2026-11-11 04:08:43', 1),
(5, 1, 'David Guamán', 'david.guaman@example.com', '0981475402', 'Quito', 3, '2026-11-11 04:08:43', 1),
(6, 1, 'Magali Castillo', 'casmagesfa@gmail.com', '0994758016', 'Guayaquil', 4, '2026-11-11 04:08:43', 0),
(7, 1, 'Sarita Guamán', 'saraisabel@gmail.com', '0995074665', 'Quito', 1, '2026-11-11 04:08:43', 0),
(9, 1, 'Daniela Curai', 'danicurai1023@gmail.com', '0987376802', 'Quito', 4, '2026-11-11 04:08:43', 0),
(10, 1, 'Wilson Escobar', 'wilsonescobar@gmail.com', '099 789 8855', 'Cuenca', 1, '2026-11-11 06:41:08', 0),
(11, 3, 'Omar Chaparro', 'omarchaparro@gamil.com', '0995909733', 'Machala', 5, '2026-11-12 21:34:03', 0),
(12, 3, 'Lenin Montalvo', 'Lenin78mon@gmail.com', '0997835409', 'Santo Domingo', 5, '2026-11-12 21:35:35', 1),
(13, 1, 'Fabricio Perez', 'Fabriciop56@gmail.com', '0991358926', 'Machala', 5, '2026-11-12 21:43:16', 0),
(14, 1, 'Pablito', 'pablojaramillo87@gmail.com', '099 457 8978', 'Ambato', 5, '2026-11-12 21:50:04', 0),
(15, 4, 'Roberto Carrillo', 'robertocarrillo47@gmail.com', '099 458 7896', 'Portoviejo', 5, '2026-11-12 22:16:23', 0),
(16, 2, 'Mateo Rodriguez', 'mateorodriguez@gmail.com', '099 789 4578', 'Azogues', 5, '2026-11-13 18:31:17', 0),
(17, 1, 'Lenin Montalvo', 'leninnmontalvo@gmail.ocm', '099 789 7899', 'Loja', 3, '2026-11-14 17:09:09', 0),
(19, 1, 'Jose Morales', 'josemorales@gmail.com', '099 789 7899', 'Portoviejo', 2, '2026-11-22 07:17:35', 0),
(20, 8, 'Pablo Derbez', 'pablo7898@gmail.com', '099 787 7874', 'Machala', 4, '2026-11-22 07:22:49', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `fecha_registro`) VALUES
(1, 'Administrador', 'admin@agenda.com', '$2y$10$fYCeZbVCO5ENPIoZPd163uP9NK/6BeySYjQUxq6sfpzUfGObgDkz6', '2026-11-11 04:38:48'),
(2, 'Anthony Castillo', 'anthonyariel1021@gmail.com', '$2y$10$TP.NQlumCSqVkuV3GOTQG.gSrn.RBQIuD3b14lmZpErOQ3mi77WOe', '2026-11-11 05:37:54'),
(3, 'Emilio Castellanos', 'emiliocastellanos@gamil.com', '$2y$10$YdByV6tg./V03GyCEoMWl.p4BwiaSceluVQfAX.XgBHXsErelFMnO', '2026-11-11 06:07:38'),
(4, 'Erick Castillo', 'erickcastillo@gmail.com', '$2y$10$t.EcuEiKefSM658hDFg4ZexoQnK5g2ML8fhmYq8Rt0HSD9KDSAtF2', '2026-11-11 06:19:38'),
(5, 'Ana Karina', 'anakarina@gmail.com', '$2y$10$EZUHIxx9nThv8ClOUcUqg.9VzhkKb0tn4lS5jVyQkYupihEpNNcUu', '2026-11-11 06:26:25'),
(6, 'Magali Castillo', 'casmagesfa@gmail.com', '$2y$10$d4VICjRl.WP968aGDBOK9uAOuoTab0MVNngHVZlZS3duVomYk/HZ.', '2026-11-13 18:32:57'),
(7, 'Erick Torres', 'ericktorres@gmail.com', '$2y$10$EXrOuDRsQKensl/aZAU0ceNd9PcazVVYiWUl7Ov.L78GIhL8N/cL2', '2026-11-14 17:41:08'),
(8, 'Paola Jara', 'paolajara@gmail.com', '$2y$10$jPUCNgO3W9AtOpWMePl9gOql8CUsKFd8JhusfxzfPCDMtcubgHlg6', '2026-11-22 07:20:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categoria_id` (`categoria_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `contactos`
--
ALTER TABLE `contactos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `contactos`
--
ALTER TABLE `contactos`
  ADD CONSTRAINT `fk_contacto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
