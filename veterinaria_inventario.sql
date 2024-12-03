-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-11-2024 a las 16:04:43
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
-- Base de datos: `veterinaria_inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alerta_stock`
--

CREATE TABLE `alerta_stock` (
  `ID_Alerta` int(11) NOT NULL,
  `ID_Producto` int(11) DEFAULT NULL,
  `Fecha_Alerta` datetime NOT NULL,
  `Estado` enum('Pendiente','Atendida') NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Tipo_Alerta` enum('Stock Bajo','Producto Vencido','Otro') NOT NULL,
  `ID_Usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_stock`
--

CREATE TABLE `movimiento_stock` (
  `ID_Movimiento` int(11) NOT NULL,
  `ID_Producto` int(11) DEFAULT NULL,
  `Fecha_Movimiento` datetime NOT NULL,
  `Tipo_Movimiento` enum('Entrada','Salida','Ajuste') NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Observaciones` text DEFAULT NULL,
  `ID_Usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimiento_stock`
--

INSERT INTO `movimiento_stock` (`ID_Movimiento`, `ID_Producto`, `Fecha_Movimiento`, `Tipo_Movimiento`, `Cantidad`, `Observaciones`, `ID_Usuario`) VALUES
(2, 2, '2024-10-15 18:54:28', '', 3, NULL, NULL),
(5, 2, '2024-10-15 19:01:32', 'Entrada', 2, 'Compra registrada', NULL),
(6, 2, '2024-10-15 19:01:37', 'Entrada', 2, 'Compra registrada', NULL),
(7, 2, '2024-10-15 19:01:56', 'Entrada', 3, 'Compra registrada', NULL),
(8, 2, '2024-10-15 19:02:27', 'Entrada', 3, 'Compra registrada', NULL),
(9, 2, '2024-10-15 19:03:08', 'Entrada', 1, 'Compra registrada', NULL),
(10, 2, '2024-10-15 19:03:50', 'Entrada', 1, 'Compra registrada', NULL),
(11, 4, '2024-10-15 19:15:04', '', 3, NULL, NULL),
(12, 4, '2024-10-15 19:15:44', 'Entrada', 1, 'Compra registrada', NULL),
(13, 4, '2024-10-15 20:41:13', '', 1, NULL, NULL),
(14, 4, '2024-10-16 11:05:01', 'Entrada', 3, 'Compra registrada', NULL),
(15, 5, '2024-10-16 11:34:04', '', 1, NULL, NULL),
(16, 5, '2024-10-16 11:34:38', 'Entrada', 10, 'Compra registrada', NULL),
(17, 5, '2024-10-16 12:46:19', '', 10, NULL, NULL),
(18, 2, '2024-10-16 12:46:38', 'Entrada', 10, 'Compra registrada', NULL),
(19, 5, '2024-11-27 01:07:57', '', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `Categoria` varchar(50) DEFAULT NULL,
  `Precio_Compra` decimal(10,2) NOT NULL,
  `Precio_Venta` decimal(10,2) NOT NULL,
  `Stock_Actual` int(11) NOT NULL,
  `Stock_Minimo` int(11) NOT NULL,
  `Codigo_Producto` varchar(50) DEFAULT NULL,
  `Fecha_Vencimiento` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`ID_Producto`, `Nombre`, `Descripcion`, `Categoria`, `Precio_Compra`, `Precio_Venta`, `Stock_Actual`, `Stock_Minimo`, `Codigo_Producto`, `Fecha_Vencimiento`) VALUES
(2, 'clona', 'sirve para dormir MASAMS', 'pastilla', 20.00, 23.00, 54, 3, 'ap159', '2024-12-10'),
(4, 'Silex', 'jarabe', 'jarabe', 30.00, 40.00, 10, 1, 'AP45', '2025-08-13'),
(5, 'Correa', 'sogade3m', 'util', 10.00, 20.00, 9, 1, 'co200', '3000-12-19'),
(8, 'Consuelo', 'afafa', 'juguete', 10.00, 124.00, 100, 1, 'ap214', '1111-11-11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `ID_Proveedor` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Direccion` text DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`ID_Proveedor`, `Nombre`, `Direccion`, `Telefono`, `Email`) VALUES
(1, 'Proveedor ABC', 'Calle Falsa 123', '555-1234', 'contacto@proveedorabc.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor_producto`
--

CREATE TABLE `proveedor_producto` (
  `ID_Proveedor` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Rol` enum('Administrador','Empleado') NOT NULL,
  `Fecha_Creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ID_Usuario`, `Nombre`, `Email`, `Contraseña`, `Rol`, `Fecha_Creacion`) VALUES
(1, 'Administrador', 'admin@veterinaria.com', 'password123', 'Administrador', '2024-10-10 10:35:03'),
(7, 'Administrador', 'admin@example.com', 'admin123', 'Administrador', '2024-10-15 16:51:58'),
(8, 'Empleado1', 'empleado1@example.com', 'empleado123', 'Empleado', '2024-10-15 16:51:58'),
(9, 'Empleado2', 'empleado2@example.com', 'empleado123', 'Empleado', '2024-10-15 16:51:58'),
(10, 'Empleado3', 'empleado3@example.com', 'empleado123', 'Empleado', '2024-10-15 16:51:58'),
(11, 'Prueba', 'prueba@example.com', 'prueba123', 'Empleado', '2024-10-15 16:51:58');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alerta_stock`
--
ALTER TABLE `alerta_stock`
  ADD PRIMARY KEY (`ID_Alerta`),
  ADD KEY `ID_Producto` (`ID_Producto`),
  ADD KEY `ID_Usuario` (`ID_Usuario`);

--
-- Indices de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  ADD PRIMARY KEY (`ID_Movimiento`),
  ADD KEY `ID_Producto` (`ID_Producto`),
  ADD KEY `ID_Usuario` (`ID_Usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`ID_Producto`),
  ADD UNIQUE KEY `Codigo_Producto` (`Codigo_Producto`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`ID_Proveedor`);

--
-- Indices de la tabla `proveedor_producto`
--
ALTER TABLE `proveedor_producto`
  ADD PRIMARY KEY (`ID_Proveedor`,`ID_Producto`),
  ADD KEY `ID_Producto` (`ID_Producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alerta_stock`
--
ALTER TABLE `alerta_stock`
  MODIFY `ID_Alerta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  MODIFY `ID_Movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `ID_Proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alerta_stock`
--
ALTER TABLE `alerta_stock`
  ADD CONSTRAINT `alerta_stock_ibfk_1` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `alerta_stock_ibfk_2` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `movimiento_stock`
--
ALTER TABLE `movimiento_stock`
  ADD CONSTRAINT `movimiento_stock_ibfk_1` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `movimiento_stock_ibfk_2` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `proveedor_producto`
--
ALTER TABLE `proveedor_producto`
  ADD CONSTRAINT `proveedor_producto_ibfk_1` FOREIGN KEY (`ID_Proveedor`) REFERENCES `proveedor` (`ID_Proveedor`) ON DELETE CASCADE,
  ADD CONSTRAINT `proveedor_producto_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `producto` (`ID_Producto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
