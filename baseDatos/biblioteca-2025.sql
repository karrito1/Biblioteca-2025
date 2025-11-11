-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 03:37:40
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
-- Base de datos: `biblioteca-2025`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `disponibilidad` enum('disponible','prestado','no disponible') NOT NULL DEFAULT 'disponible',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `prestados` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `isbn`, `categoria`, `cantidad`, `disponibilidad`, `fecha_registro`, `prestados`) VALUES
(2, 'El Principito', 'Antoine de Saint-Exupéry', '9780156012195412', 'Infantil', 0, 'no disponible', '2025-10-05 23:49:07', 2),
(3, 'los camios', 'lalalla', '45255', 'infantil', 49, 'disponible', '2025-10-21 06:37:31', 1),
(4, 'ñaca', 'dsadadsdsa', 'sddsdsaasd3232324', 'infantil', 455, 'disponible', '2025-10-21 06:45:30', 0),
(5, 'el moan', 'camilo', '125212', 'miedo', 523, 'disponible', '2025-10-21 14:27:34', 0),
(6, 'ñacadsfdsfds', 'dsadadsdsa', 'fdgfgfdg3343', 'infantil', 50, 'disponible', '2025-10-24 11:58:12', 0),
(7, 'xzcczxzccxz', 'ccac', '854474', 'miedo', 5, 'disponible', '2025-10-26 04:02:19', 0),
(8, 'los sapos', 'eddssd', 'dsdsdsds', 'dddfdfdf', 522, 'disponible', '2025-11-03 06:47:56', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `fecha_prestamo` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_devolucion` date NOT NULL,
  `estado` enum('activo','devuelto','vencido') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `usuario_id`, `libro_id`, `fecha_prestamo`, `fecha_devolucion`, `estado`) VALUES
(16, 1, 4, '2025-11-08 05:00:00', '2025-11-11', 'activo'),
(18, 2, 4, '2025-11-04 05:00:00', '2025-11-11', 'activo'),
(19, 2, 7, '2025-11-07 05:00:00', '2025-11-14', 'activo'),
(20, 1, 4, '2025-11-07 05:00:00', '2025-11-14', 'activo'),
(21, 1, 4, '2025-11-07 05:00:00', '2025-11-14', 'activo'),
(22, 1, 5, '2025-11-07 05:00:00', '2025-11-14', 'activo'),
(23, 2, 6, '2025-11-11 05:00:00', '2025-11-18', 'activo'),
(24, 1, 8, '2025-11-11 05:00:00', '2025-11-18', 'activo'),
(25, 1, 2, '2025-11-11 05:00:00', '2025-11-18', 'activo'),
(26, 1, 2, '2025-11-11 05:00:00', '2025-11-18', 'activo'),
(27, 1, 3, '2025-11-11 05:00:00', '2025-11-18', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `libro_id` int(11) NOT NULL,
  `fecha_reserva` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('Pendiente','Aprobada','Rechazada') NOT NULL DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `libro_id`, `fecha_reserva`, `estado`) VALUES
(6, 1, 4, '2025-11-02 05:00:00', ''),
(8, 2, 7, '2025-11-03 05:00:00', ''),
(10, 1, 4, '2025-11-03 05:00:00', ''),
(12, 2, 6, '2025-11-09 05:00:00', ''),
(13, 1, 8, '2025-11-10 05:00:00', ''),
(14, 1, 2, '2025-11-10 05:00:00', ''),
(15, 1, 2, '2025-11-10 05:00:00', ''),
(17, 1, 3, '2025-11-10 05:00:00', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `Roles` varchar(45) NOT NULL,
  `passwordd` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `telefono`, `direccion`, `estado`, `fecha_registro`, `Roles`, `passwordd`) VALUES
(1, 'Ana', 'kamilovilla554@hotmail.com', '3001112233', 'Calle 10 #12-45', 'activo', '2025-10-07 05:00:00', 'ADMINISTRADOR', '$2y$10$14kqyK.fbXVP7eM9qU.UTeQwHGzlDoTEOxrl7K7UC98rDccEuOcjS'),
(2, 'crisitian villa c', 'carlo5255s@example.com', 'fddfgfdgfg', 'Calle 10 #12-45', 'activo', '2025-10-05 00:00:00', 'CLIENTE', '$2y$10$lFY2MChnku/oW8MTRFeOYu81SmOlU39JK9en8zSTG8QtP4UcHzbJm'),
(25, 'rfddgfd', 'cjose5255s@example.com', 'fddfgfdgfg', 'dsdfdsdf', 'activo', '2025-11-03 01:57:22', 'cliente', '$2y$10$qpz.Upo0DZO9CRSNAlfJ6eFJxkspMt2BhG/AW9ubIwJd8I9mPt.Va'),
(29, 'juan', 'juan@hotmai.com', '315', 'Calle 10 #12-45', 'activo', '2025-11-09 15:07:57', 'cliente', '$2y$10$bjRUDSV.DaZ.LLtJs6uIK.MAntFAzeBftzHT5Z3s3CyaL5ExA0Yzi');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `isbn` (`isbn`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `libro_id` (`libro_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
