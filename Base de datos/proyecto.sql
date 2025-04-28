-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-04-2025 a las 17:57:42
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
-- Base de datos: `proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id` int(11) NOT NULL,
  `Nombre` varchar(60) NOT NULL,
  `Apellido` varchar(60) NOT NULL,
  `Matricula` varchar(60),
  `Cedula` varchar(60),
  `Sexo` enum('F','M') NOT NULL,
  `Telefono` varchar(40) NOT NULL,
  `Email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id`, `Nombre`, `Apellido`, `Matricula`, `Cedula`, `Sexo`, `Telefono`, `Email`) VALUES
(1, 'Juan', 'Pérez', '2023001', '001-1234567-8', 'M', '809-555-1234', 'juan.perez@email.com'),
(2, 'María', 'Gómez', '2023002', '002-7654321-9', 'F', '829-444-5678', 'maria.gomez@email.com'),
(3, 'Carlos', 'Ramírez', '2023003', '003-9876543-2', 'M', '849-333-9012', 'carlos.ramirez@email.com'),
(4, 'Ana', 'Martínez', '2023004', '004-5432198-7', 'F', '809-222-3456', 'ana.martinez@email.com'),
(5, 'Pedro', 'Fernández', '2023005', '005-6789123-5', 'M', '829-111-7890', 'pedro.fernandez@email.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro`
--

CREATE TABLE `registro` (
  `id` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `apellido` varchar(60) NOT NULL,
  `cedula` varchar(60),
  `matricula` varchar(60),
  `tipo_de_solicitud` enum('Realizar Tarea','Uso de Computador','Solicitud de Libro','Devolucion de Libro','Uso de Cubiculo','Visitas','Proceso de Admision','Seleccion de Asignatura','Orientacion en el Uso de Plataformas de Estudio','Otros') DEFAULT NULL,
  `sexo` enum('F','M') NOT NULL,
  `telefono` varchar(40) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_de_ingreso` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tipo_de_persona` enum('Estudiante','Visitante','Administrativo','Maestros') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registro`
--

INSERT INTO `registro` (`id`, `nombre`, `apellido`, `cedula`, `matricula`, `tipo_de_solicitud`, `sexo`, `telefono`, `email`, `fecha_de_ingreso`, `tipo_de_persona`) VALUES
(1, 'Juan', 'Pérez', '001-1234567-8', '2001-01', 'Realizar Tarea', 'M', '809-555-1234', 'juan.perez@email.com', '2025-03-01 18:00:00', 'Estudiante'),
(2, 'María', 'Gómez', '002-7654321-9', '2002-02', 'Uso de Computador', 'F', '829-444-5678', 'maria.gomez@email.com', '2025-03-02 19:30:00', 'Estudiante'),
(3, 'Carlos', 'Ramírez', '003-9876543-2', '2003-03', 'Proceso de Admision', 'M', '849-333-9012', 'carlos.ramirez@email.com', '2025-03-03 23:45:00', 'Visitante'),
(4, 'Ana', 'Martínez', '004-5432198-7', '2004-04', 'Otros', 'F', '809-222-3456', 'ana.martinez@email.com', '2025-03-04 17:20:00', 'Maestros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariologin`
--

CREATE TABLE `usuariologin` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuariologin`
--

INSERT INTO `usuariologin` (`id`, `usuario`, `contrasena`) VALUES
(1, 'Andre123', '1234');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `registro`
--
ALTER TABLE `registro`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuariologin`
--
ALTER TABLE `usuariologin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `registro`
--
ALTER TABLE `registro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuariologin`
--
ALTER TABLE `usuariologin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Eliminar el registro con ID 3 y recalcular los IDs
--
DELETE FROM registro WHERE id = 3;

SET @new_id = 0;
UPDATE registro SET id = (@new_id := @new_id + 1);

ALTER TABLE registro AUTO_INCREMENT = 1;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
