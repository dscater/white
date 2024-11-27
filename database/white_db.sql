-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 27-11-2024 a las 23:26:49
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `white_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracions`
--

CREATE TABLE `configuracions` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre_sistema` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razon_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ciudad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracions`
--

INSERT INTO `configuracions` (`id`, `nombre_sistema`, `alias`, `razon_social`, `ciudad`, `dir`, `fono`, `correo`, `web`, `actividad`, `logo`, `created_at`, `updated_at`) VALUES
(1, 'WHITE', 'WT', 'WHITE S.A.', 'LA PAZ', 'ZONA LOS OLIVOS', '77777777', 'WHITE@GMAIL.COM', 'WHITE.COM', 'ACTIVIDAD', '1725897866_1.jpg', NULL, '2024-11-11 19:58:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamientos`
--

CREATE TABLE `entrenamientos` (
  `id` bigint UNSIGNED NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrenamientos`
--

INSERT INTO `entrenamientos` (`id`, `tipo`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'CARIES', '2024-11-27', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(2, 'SIN CARIES', '2024-11-27', '2024-11-27 21:12:32', '2024-11-27 21:12:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenamiento_imagens`
--

CREATE TABLE `entrenamiento_imagens` (
  `id` bigint UNSIGNED NOT NULL,
  `entrenamiento_id` bigint UNSIGNED NOT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `entrenamiento_imagens`
--

INSERT INTO `entrenamiento_imagens` (`id`, `entrenamiento_id`, `imagen`, `created_at`, `updated_at`) VALUES
(2, 1, 'imagen11732726052_1.jpg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(3, 1, 'imagen11732726052_2.jpeg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(4, 1, 'imagen11732726052_3.jpg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(5, 1, 'imagen11732726052_4.jpeg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(6, 1, 'imagen11732726052_5.jpg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(7, 1, 'imagen11732726052_6.jpeg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(8, 1, 'imagen11732726052_7.jpg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(9, 1, 'imagen11732726052_8.jpeg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(10, 1, 'imagen11732726052_9.jpg', '2024-11-27 20:47:32', '2024-11-27 20:47:32'),
(11, 1, 'imagen11732727495_4.jpg', '2024-11-27 21:11:35', '2024-11-27 21:11:35'),
(12, 2, 'imagen21732727552_0.jpg', '2024-11-27 21:12:32', '2024-11-27 21:12:32'),
(13, 2, 'imagen21732727552_1.jpg', '2024-11-27 21:12:32', '2024-11-27 21:12:32'),
(14, 2, 'imagen21732727552_2.jpg', '2024-11-27 21:12:32', '2024-11-27 21:12:32'),
(15, 2, 'imagen21732727552_3.jpg', '2024-11-27 21:12:32', '2024-11-27 21:12:32'),
(16, 2, 'imagen21732727552_4.jpeg', '2024-11-27 21:12:32', '2024-11-27 21:12:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_dentals`
--

CREATE TABLE `examen_dentals` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `dolencia_actual` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `imagen2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resultado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_registro` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examen_dentals`
--

INSERT INTO `examen_dentals` (`id`, `paciente_id`, `dolencia_actual`, `imagen1`, `imagen2`, `resultado`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(4, 1, 'DOLOR MUELAS', 'ExamenDental4_17327493671.jpg', '101732747928.jpg', 'SE ECONTRARON 2 CARIES DEL PACIENTE', '2024-11-27', '2024-11-28 03:16:07', '2024-11-28 03:24:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examen_detalles`
--

CREATE TABLE `examen_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `examen_dental_id` bigint UNSIGNED NOT NULL,
  `pieza` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diagnostico` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `examen_detalles`
--

INSERT INTO `examen_detalles` (`id`, `examen_dental_id`, `pieza`, `diagnostico`, `observaciones`, `created_at`, `updated_at`) VALUES
(4, 4, '11', 'CARIES LEVE', 'SIN URGENCIA', '2024-11-28 03:16:07', '2024-11-28 03:24:19'),
(9, 4, '22', 'CARIES GRAVE', 'PROCEDERE INMEDIATAMENTE', '2024-11-28 03:24:00', '2024-11-28 03:24:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_accions`
--

CREATE TABLE `historial_accions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `accion` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `datos_original` text COLLATE utf8mb4_unicode_ci,
  `datos_nuevo` text COLLATE utf8mb4_unicode_ci,
  `modulo` varchar(155) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_accions`
--

INSERT INTO `historial_accions` (`id`, `user_id`, `accion`, `descripcion`, `datos_original`, `datos_nuevo`, `modulo`, `fecha`, `hora`, `created_at`, `updated_at`) VALUES
(1, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN USUARIO', 'id: 2<br/>usuario: JPERES<br/>nombre: JUAN<br/>paterno: PERES<br/>materno: MAMANI<br/>ci: 1111<br/>ci_exp: LP<br/>dir: LOS OLIVOS<br/>email: JUAN@GMAIL.COM<br/>fono: 77777777<br/>password: $2y$12$f4zC3PDPMeeJo.YSpTEHZ.ZS/8XcUV2gGoLCDGHfMXoppwI7e50E2<br/>tipo: DOCTOR<br/>foto: 1732317953_JPERES.jpg<br/>fecha_registro: 2024-11-22<br/>acceso: 1<br/>created_at: 2024-11-22 23:25:53<br/>updated_at: 2024-11-22 23:25:53<br/>', NULL, 'USUARIOS', '2024-11-22', '23:25:53', '2024-11-23 03:25:53', '2024-11-23 03:25:53'),
(2, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN PACIENTE', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: SOLTERO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:47:54<br/>', NULL, 'PACIENTES', '2024-11-22', '23:47:54', '2024-11-23 03:47:54', '2024-11-23 03:47:54'),
(3, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PACIENTE', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: SOLTERO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:47:54<br/>', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZS<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:29<br/>', 'PACIENTES', '2024-11-22', '23:48:29', '2024-11-23 03:48:29', '2024-11-23 03:48:29'),
(4, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PACIENTE', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZS<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:29<br/>', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:39<br/>', 'PACIENTES', '2024-11-22', '23:48:39', '2024-11-23 03:48:39', '2024-11-23 03:48:39'),
(5, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PACIENTE', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319274_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:39<br/>', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319324_1.png<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:44<br/>', 'PACIENTES', '2024-11-22', '23:48:44', '2024-11-23 03:48:44', '2024-11-23 03:48:44'),
(6, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN PACIENTE', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732319324_1.png<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-22 23:48:44<br/>', 'id: 1<br/>nombre: MARIO<br/>paterno: CONDORI<br/>materno: SOLIZ<br/>ci: 3233322<br/>ci_exp: LP<br/>fecha_nac: 2000-01-01<br/>sexo: HOMBRE<br/>estado_civil: CASADO<br/>nacionalidad: BOLIVIANO<br/>lugar_nac: LA PAZ<br/>ocupacion: TRANSPORTISTA<br/>dir: LOS OLIVOS<br/>fono: 77777777<br/>correo: MARIO@GMAIL.COM<br/>nom_familiar: PEDRO CONDORI<br/>fono_familiar: 666666<br/>foto: 1732722395_1.jpg<br/>fecha_registro: 2024-11-22<br/>created_at: 2024-11-22 23:47:54<br/>updated_at: 2024-11-27 15:46:35<br/>', 'PACIENTES', '2024-11-27', '15:46:35', '2024-11-27 19:46:35', '2024-11-27 19:46:35'),
(7, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ENTRENAMIENTO DE IMAGEN', 'id: 1<br/>tipo: CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 16:47:32<br/>updated_at: 2024-11-27 16:47:32<br/>', NULL, 'ENTRENAMIENTO DE IMAGENES', '2024-11-27', '16:47:44', '2024-11-27 20:47:44', '2024-11-27 20:47:44'),
(8, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN ENTRENAMIENTO DE IMAGEN', 'id: 1<br/>tipo: CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 16:47:32<br/>updated_at: 2024-11-27 16:47:32<br/>', 'id: 1<br/>tipo: CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 16:47:32<br/>updated_at: 2024-11-27 16:47:32<br/>', 'ENTRENAMIENTO DE IMAGENES', '2024-11-27', '17:11:08', '2024-11-27 21:11:08', '2024-11-27 21:11:08'),
(9, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN ENTRENAMIENTO DE IMAGEN', 'id: 1<br/>tipo: CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 16:47:32<br/>updated_at: 2024-11-27 16:47:32<br/>', 'id: 1<br/>tipo: CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 16:47:32<br/>updated_at: 2024-11-27 16:47:32<br/>', 'ENTRENAMIENTO DE IMAGENES', '2024-11-27', '17:11:47', '2024-11-27 21:11:47', '2024-11-27 21:11:47'),
(10, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN ENTRENAMIENTO DE IMAGEN', 'id: 2<br/>tipo: SIN CARIES<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 17:12:32<br/>updated_at: 2024-11-27 17:12:32<br/>', NULL, 'ENTRENAMIENTO DE IMAGENES', '2024-11-27', '17:12:45', '2024-11-27 21:12:45', '2024-11-27 21:12:45'),
(11, 1, 'CREACIÓN', 'EL USUARIO admin REGISTRO UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.JPG<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:16:07<br/>', NULL, 'EXAMENES DENTALES', '2024-11-27', '23:16:07', '2024-11-28 03:16:07', '2024-11-28 03:16:07'),
(12, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:16:07<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:24<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:19:24', '2024-11-28 03:19:24', '2024-11-28 03:19:24'),
(13, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:16:07<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:24<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:19:24', '2024-11-28 03:19:24', '2024-11-28 03:19:24'),
(14, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:24<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:44<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:19:44', '2024-11-28 03:19:44', '2024-11-28 03:19:44'),
(15, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:24<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:44<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:19:44', '2024-11-28 03:19:44', '2024-11-28 03:19:44'),
(16, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:19:44<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:20:35<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:20:35', '2024-11-28 03:20:35', '2024-11-28 03:20:35'),
(17, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:20:35<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:21:28<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:21:28', '2024-11-28 03:21:28', '2024-11-28 03:21:28'),
(18, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:20:35<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:21:28<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:21:28', '2024-11-28 03:21:28', '2024-11-28 03:21:28'),
(19, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:21:28<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:23:04<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:23:04', '2024-11-28 03:23:04', '2024-11-28 03:23:04'),
(20, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:23:04<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:24:00<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:24:00', '2024-11-28 03:24:00', '2024-11-28 03:24:00'),
(21, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:24:00<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:24:09<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:24:09', '2024-11-28 03:24:09', '2024-11-28 03:24:09'),
(22, 1, 'MODIFICACIÓN', 'EL USUARIO admin MODIFICÓ UN EXAMEN DENTAL', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:24:09<br/>', 'id: 4<br/>paciente_id: 1<br/>dolencia_actual: DOLOR MUELAS<br/>imagen1: ExamenDental4_17327493671.jpg<br/>imagen2: 101732747928.jpg<br/>resultado: SE ECONTRARON 2 CARIES DEL PACIENTE<br/>fecha_registro: 2024-11-27<br/>created_at: 2024-11-27 23:16:07<br/>updated_at: 2024-11-27 23:24:19<br/>', 'EXAMENES DENTALES', '2024-11-27', '23:24:19', '2024-11-28 03:24:19', '2024-11-28 03:24:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2024_01_31_165641_create_configuracions_table', 1),
(3, '2024_02_02_205431_create_historial_accions_table', 1),
(4, '2024_11_18_214257_create_pacientes_table', 2),
(5, '2024_11_18_214325_create_examen_dentals_table', 2),
(6, '2024_11_18_214358_create_examen_detalles_table', 2),
(7, '2024_11_18_214407_create_seguimientos_table', 2),
(8, '2024_11_18_214419_create_entrenamientos_table', 2),
(9, '2024_11_18_214424_create_entrenamiento_imagens_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `sexo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_civil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionalidad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lugar_nac` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ocupacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_familiar` varchar(400) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fono_familiar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `fecha_nac`, `sexo`, `estado_civil`, `nacionalidad`, `lugar_nac`, `ocupacion`, `dir`, `fono`, `correo`, `nom_familiar`, `fono_familiar`, `foto`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 'MARIO', 'CONDORI', 'SOLIZ', '3233322', 'LP', '2000-01-01', 'HOMBRE', 'CASADO', 'BOLIVIANO', 'LA PAZ', 'TRANSPORTISTA', 'LOS OLIVOS', '77777777', 'MARIO@GMAIL.COM', 'PEDRO CONDORI', '666666', '1732722395_1.jpg', '2024-11-22', '2024-11-23 03:47:54', '2024-11-27 19:46:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimientos`
--

CREATE TABLE `seguimientos` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `examen_dental_id` bigint UNSIGNED NOT NULL,
  `examen_detalle_id` bigint UNSIGNED NOT NULL,
  `pieza` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `observacion` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seguimientos`
--

INSERT INTO `seguimientos` (`id`, `paciente_id`, `examen_dental_id`, `examen_detalle_id`, `pieza`, `estado`, `observacion`, `fecha_registro`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 4, '11', 'PENDIENTE', '', NULL, '2024-11-28 03:16:07', '2024-11-28 03:24:19'),
(3, 1, 4, 9, '22', 'PENDIENTE', '', NULL, '2024-11-28 03:24:00', '2024-11-28 03:24:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `usuario` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paterno` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `materno` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ci` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ci_exp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fono` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_registro` date NOT NULL,
  `acceso` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `usuario`, `nombre`, `paterno`, `materno`, `ci`, `ci_exp`, `dir`, `email`, `fono`, `password`, `tipo`, `foto`, `fecha_registro`, `acceso`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', NULL, '0', '', '', 'admin@admin.com', '', '$2y$12$65d4fgZsvBV5Lc/AxNKh4eoUdbGyaczQ4sSco20feSQANshNLuxSC', 'DOCTOR', NULL, '2024-11-09', 1, NULL, NULL),
(2, 'JPERES', 'JUAN', 'PERES', 'MAMANI', '1111', 'LP', 'LOS OLIVOS', 'JUAN@GMAIL.COM', '77777777', '$2y$12$f4zC3PDPMeeJo.YSpTEHZ.ZS/8XcUV2gGoLCDGHfMXoppwI7e50E2', 'DOCTOR', '1732317953_JPERES.jpg', '2024-11-22', 1, '2024-11-23 03:25:53', '2024-11-23 03:25:53');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `entrenamiento_imagens`
--
ALTER TABLE `entrenamiento_imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrenamiento_imagens_entrenamiento_id_foreign` (`entrenamiento_id`);

--
-- Indices de la tabla `examen_dentals`
--
ALTER TABLE `examen_dentals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_dentals_paciente_id_foreign` (`paciente_id`);

--
-- Indices de la tabla `examen_detalles`
--
ALTER TABLE `examen_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_detalles_examen_dental_id_foreign` (`examen_dental_id`);

--
-- Indices de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seguimientos_paciente_id_foreign` (`paciente_id`),
  ADD KEY `seguimientos_examen_dental_id_foreign` (`examen_dental_id`),
  ADD KEY `seguimientos_examen_detalle_id_foreign` (`examen_detalle_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_usuario_unique` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `configuracions`
--
ALTER TABLE `configuracions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `entrenamientos`
--
ALTER TABLE `entrenamientos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `entrenamiento_imagens`
--
ALTER TABLE `entrenamiento_imagens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `examen_dentals`
--
ALTER TABLE `examen_dentals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `examen_detalles`
--
ALTER TABLE `examen_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `historial_accions`
--
ALTER TABLE `historial_accions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `entrenamiento_imagens`
--
ALTER TABLE `entrenamiento_imagens`
  ADD CONSTRAINT `entrenamiento_imagens_entrenamiento_id_foreign` FOREIGN KEY (`entrenamiento_id`) REFERENCES `entrenamientos` (`id`);

--
-- Filtros para la tabla `examen_dentals`
--
ALTER TABLE `examen_dentals`
  ADD CONSTRAINT `examen_dentals_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Filtros para la tabla `examen_detalles`
--
ALTER TABLE `examen_detalles`
  ADD CONSTRAINT `examen_detalles_examen_dental_id_foreign` FOREIGN KEY (`examen_dental_id`) REFERENCES `examen_dentals` (`id`);

--
-- Filtros para la tabla `seguimientos`
--
ALTER TABLE `seguimientos`
  ADD CONSTRAINT `seguimientos_examen_dental_id_foreign` FOREIGN KEY (`examen_dental_id`) REFERENCES `examen_dentals` (`id`),
  ADD CONSTRAINT `seguimientos_examen_detalle_id_foreign` FOREIGN KEY (`examen_detalle_id`) REFERENCES `examen_detalles` (`id`),
  ADD CONSTRAINT `seguimientos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
