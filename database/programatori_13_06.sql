 -- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 13, 2024 at 03:16 PM
-- Server version: 8.0.37-0ubuntu0.20.04.3
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `programatori`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `log_id` int NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `worker_id` int DEFAULT NULL,
  `referer` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(60) COLLATE utf8mb4_general_ci NOT NULL,
  `device_type` set('computer','tablet','phone') COLLATE utf8mb4_general_ci NOT NULL,
  `date_accessed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_logged_in` tinyint NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_logs`
--

INSERT INTO `access_logs` (`log_id`, `user_agent`, `worker_id`, `referer`, `ip_address`, `device_type`, `date_accessed`, `is_logged_in`, `note`) VALUES
(25, 'PostmanRuntime/7.39.0', 19, 'Postman', '87.116.133.188', 'computer', '2024-05-26 08:37:02', 1, NULL),
(26, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'http://localhost:3000/', '93.87.121.146', 'computer', '2024-05-26 14:13:23', 1, NULL),
(27, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'http://localhost:3000/', '93.87.121.146', 'computer', '2024-05-26 14:13:39', 1, NULL),
(28, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'http://localhost:3000/', '93.87.121.146', 'computer', '2024-05-26 14:35:32', 1, NULL),
(29, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'http://localhost:3000/', '93.87.121.146', 'computer', '2024-05-26 15:56:57', 1, NULL),
(30, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'http://localhost:3000/', '93.87.121.146', 'computer', '2024-05-26 16:46:44', 1, NULL),
(31, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://inventory-system-frontend-seven.vercel.app/', '87.116.133.188', 'computer', '2024-05-26 17:15:33', 1, NULL),
(32, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:15:42', 1, NULL),
(33, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:16:02', 1, NULL),
(34, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:16:24', 1, NULL),
(35, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:17:33', 1, NULL),
(36, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'phone', '2024-05-26 17:18:11', 1, NULL),
(37, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 19, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:21:02', 1, NULL),
(38, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:23:16', 1, NULL),
(39, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:23:49', 1, NULL),
(40, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:39:15', 1, NULL),
(41, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:41:12', 1, NULL),
(42, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 17:52:49', 1, NULL),
(43, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://inventory-system-frontend-seven.vercel.app/', '87.116.133.188', 'computer', '2024-05-26 17:53:49', 1, NULL),
(44, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'phone', '2024-05-26 17:58:07', 1, NULL),
(45, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://programatori.stud.vts.su.ac.rs/public/swagger/', '87.116.133.188', 'computer', '2024-05-26 18:08:37', 0, 'Wrong credentials, please try again!'),
(46, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://inventory-system-frontend-seven.vercel.app/', '87.116.133.188', 'computer', '2024-05-26 18:09:02', 0, 'Wrong credentials, please try again!'),
(47, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://inventory-system-frontend-seven.vercel.app/', '87.116.133.188', 'computer', '2024-05-26 18:32:50', 0, 'Wrong credentials, please try again!'),
(48, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://inventory-system-frontend-seven.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 19:10:35', 1, NULL),
(49, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 19:40:53', 1, NULL),
(50, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 19:41:31', 1, NULL),
(51, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 19:43:24', 1, NULL),
(52, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 19, 'https://imsystem.vercel.app/', '87.116.133.188', 'computer', '2024-05-26 19:43:24', 1, NULL),
(53, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'computer', '2024-05-26 19:43:52', 1, NULL),
(54, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'phone', '2024-05-26 20:00:10', 1, NULL),
(55, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'phone', '2024-05-26 20:12:26', 1, NULL),
(56, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.146', 'phone', '2024-05-26 22:33:13', 1, NULL),
(57, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 06:23:42', 1, NULL),
(58, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 06:24:35', 1, NULL),
(59, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 06:54:21', 0, 'Wrong credentials, please try again!'),
(60, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 06:54:27', 1, NULL),
(61, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 08:41:31', 1, NULL),
(62, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 08:41:42', 0, 'Wrong credentials, please try again!'),
(63, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 08:41:50', 1, NULL),
(64, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-27 09:07:18', 1, NULL),
(65, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '178.221.4.114', 'computer', '2024-05-27 16:03:31', 1, NULL),
(66, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '178.221.4.114', 'computer', '2024-05-27 17:38:34', 1, NULL),
(67, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '178.221.4.114', 'computer', '2024-05-27 18:06:39', 1, NULL),
(68, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '178.221.4.114', 'computer', '2024-05-27 19:17:24', 1, NULL),
(69, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-29 08:51:59', 1, NULL),
(70, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-29 08:55:00', 1, NULL),
(71, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-29 08:55:28', 1, NULL),
(72, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'phone', '2024-05-29 08:59:49', 1, NULL),
(73, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-29 13:36:25', 1, NULL),
(74, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '147.91.199.142', 'computer', '2024-05-30 10:17:38', 1, NULL),
(75, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '147.91.199.142', 'computer', '2024-05-30 10:23:42', 1, NULL),
(76, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '147.91.199.142', 'computer', '2024-05-30 10:30:19', 1, NULL),
(77, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-05-30 13:41:27', 1, NULL),
(78, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.38', 'computer', '2024-06-07 19:53:21', 1, NULL),
(79, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.38', 'computer', '2024-06-09 16:40:36', 1, NULL),
(80, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.87.121.38', 'computer', '2024-06-09 16:49:26', 1, NULL),
(81, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:09:46', 1, NULL),
(82, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:10:38', 0, 'Wrong credentials, please try again!'),
(83, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:10:47', 0, 'Wrong credentials, please try again!'),
(84, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:11:24', 0, 'Wrong credentials, please try again!'),
(85, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:11:30', 0, 'Wrong credentials, please try again!'),
(86, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:12:16', 1, NULL),
(87, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:14:27', 0, 'Wrong credentials, please try again!'),
(88, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', NULL, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:36:42', 0, 'Wrong credentials, please try again!'),
(89, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-12 13:42:00', 1, NULL),
(90, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'phone', '2024-06-12 13:42:47', 1, NULL),
(91, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:126.0) Gecko/20100101 Firefox/126.0', 29, 'https://programatori.stud.vts.su.ac.rs/public/swagger/', '87.116.133.209', 'computer', '2024-06-12 19:43:24', 1, NULL),
(92, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.86.237.143', 'computer', '2024-06-12 19:50:45', 1, NULL),
(93, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.86.237.143', 'computer', '2024-06-12 19:56:08', 1, NULL),
(94, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.86.237.143', 'computer', '2024-06-12 19:56:58', 1, NULL),
(95, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.86.237.143', 'computer', '2024-06-12 20:21:12', 1, NULL),
(96, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 07:45:41', 1, NULL),
(97, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 07:47:32', 1, NULL),
(98, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 07:51:20', 1, NULL),
(99, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 10:00:18', 1, NULL),
(100, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 10:04:11', 1, NULL),
(101, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 10:10:46', 1, NULL),
(102, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 12:51:45', 1, NULL),
(103, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 13:13:56', 1, NULL),
(104, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 13:15:46', 1, NULL),
(105, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36', 30, 'https://imsystem.vercel.app/', '46.40.7.116', 'computer', '2024-06-13 13:17:15', 1, NULL),
(106, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '109.245.33.200', 'phone', '2024-06-13 13:59:27', 1, NULL),
(107, 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36', 30, 'https://imsystem.vercel.app/', '93.86.237.4', 'phone', '2024-06-13 14:10:01', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int NOT NULL,
  `admin_username` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `admin_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int NOT NULL,
  `company_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `company_mail` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
  `company_state` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `company_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isActive` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`, `company_mail`, `company_state`, `company_address`, `isActive`) VALUES
(1, 'Test', 'company@email.com', 'Serbia', 'New Address 90', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_archive`
--

CREATE TABLE `inventory_archive` (
  `archive_id` int NOT NULL,
  `worker_id` int NOT NULL,
  `employer_id` int NOT NULL,
  `item_id` int NOT NULL,
  `room_id` int NOT NULL,
  `date_time()` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Complete arhive on invertory system';

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `item_name` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `item_quantity` int DEFAULT NULL,
  `country_of_origin` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `picture_id` int NOT NULL,
  `picture_type_id` int NOT NULL,
  `picture_name` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
  `picture_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mime_type` varchar(8) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `picture_types`
--

CREATE TABLE `picture_types` (
  `picture_type_id` int NOT NULL,
  `picture_type_name` varchar(12) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `picture_types`
--

INSERT INTO `picture_types` (`picture_type_id`, `picture_type_name`) VALUES
(1, 'User'),
(2, 'QRCode'),
(3, 'ItemReport');

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `qr_code_id` int NOT NULL,
  `file_name` varchar(90) COLLATE utf8mb4_general_ci NOT NULL,
  `title` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `item_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int NOT NULL,
  `room_name` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_number` int DEFAULT NULL,
  `room_description` text COLLATE utf8mb4_general_ci,
  `isActive_inventory` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scanned_items`
--

CREATE TABLE `scanned_items` (
  `scanned_item_id` int NOT NULL,
  `item_id` int NOT NULL,
  `worker_id` int NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `picture_id` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_scanned` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for storing items gone trough inventory system';

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int NOT NULL,
  `team_id` int NOT NULL,
  `room_id` int NOT NULL,
  `worker_id` int NOT NULL,
  `start_date` date NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isActive` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='For empolyer to create task and give it to the team';

-- --------------------------------------------------------

--
-- Table structure for table `task_response`
--

CREATE TABLE `task_response` (
  `task_response_id` int NOT NULL,
  `task_id` int NOT NULL,
  `task_summary` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `date_ended` datetime DEFAULT NULL,
  `status` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_id` int NOT NULL,
  `team_name` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `teammember_id` int NOT NULL,
  `team_id` int NOT NULL,
  `worker_id` int NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Agregate table between workers and teams entities';

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `worker_id` int NOT NULL,
  `worker_fname` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `worker_lname` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(32) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `worker_email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `worker_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `picture_id` int DEFAULT NULL,
  `company_id` int DEFAULT NULL,
  `role` set('worker','employer') COLLATE utf8mb4_general_ci NOT NULL,
  `registration_token` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `forgoten_password_token` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `forgoten_password_expires` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `worker_fname`, `worker_lname`, `phone_number`, `worker_email`, `worker_password`, `picture_id`, `company_id`, `role`, `registration_token`, `registration_expires`, `forgoten_password_token`, `forgoten_password_expires`, `date_created`, `isActive`) VALUES
(19, 'Stefan', 'Buljic', '2147483647', 'buljic77@gmail.com', '$2y$10$iXb/1MO8UV1DP4.ePtqG0OnjORAYFqLLpf47MTRs2NPbnjiDJ396K', NULL, NULL, 'worker', NULL, NULL, NULL, NULL, '2024-04-20 10:59:23', 1),
(29, 'Stefan', 'Buljic', '+381616458781', 'stefanbvts@gmail.com', '$2y$10$WV0Jgg5uFe.jO8BEVAgG6u8g6Ld4E0e4eZWKvC38e0wKQlmel6y3a', NULL, NULL, 'worker', NULL, NULL, NULL, NULL, '2024-05-25 21:12:23', 1),
(30, 'Filip', 'Kujundzic', '+153145134132', 'filipkujundzic3@gmail.com', '$2y$10$t6aPsp5KAkbhUObhwI60.ufbYIxvWbsyFtdAisfFlOu/6burJo0yy', NULL, 1, 'employer', NULL, NULL, NULL, NULL, '2024-05-26 14:13:03', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `FK_Logs_Workers` (`worker_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `inventory_archive`
--
ALTER TABLE `inventory_archive`
  ADD PRIMARY KEY (`archive_id`),
  ADD KEY `FK_Archive_Worker` (`worker_id`),
  ADD KEY `FK_Archive_Item` (`item_id`),
  ADD KEY `FK_Archive_Employee` (`employer_id`),
  ADD KEY `FK_Archive_Room` (`room_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `FK_Item_Room` (`room_id`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`picture_id`),
  ADD KEY `FK_Picture_PictureType` (`picture_type_id`);

--
-- Indexes for table `picture_types`
--
ALTER TABLE `picture_types`
  ADD PRIMARY KEY (`picture_type_id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`qr_code_id`),
  ADD KEY `FK_QRCode_Item` (`item_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `scanned_items`
--
ALTER TABLE `scanned_items`
  ADD PRIMARY KEY (`scanned_item_id`),
  ADD KEY `FK_ScannedItem_Worker` (`worker_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `FK_Task_Worker` (`worker_id`);

--
-- Indexes for table `task_response`
--
ALTER TABLE `task_response`
  ADD PRIMARY KEY (`task_response_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`teammember_id`),
  ADD KEY `FK_Members_Team` (`team_id`),
  ADD KEY `FK_Members_Worker` (`worker_id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `FK_Worker_Company` (`company_id`),
  ADD KEY `FK_Worker_Picture` (`picture_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_archive`
--
ALTER TABLE `inventory_archive`
  MODIFY `archive_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pictures`
--
ALTER TABLE `pictures`
  MODIFY `picture_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `picture_types`
--
ALTER TABLE `picture_types`
  MODIFY `picture_type_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `qr_code_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scanned_items`
--
ALTER TABLE `scanned_items`
  MODIFY `scanned_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_response`
--
ALTER TABLE `task_response`
  MODIFY `task_response_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `team_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `teammember_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workers`
--
ALTER TABLE `workers`
  MODIFY `worker_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD CONSTRAINT `FK_Logs_Workers` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `inventory_archive`
--
ALTER TABLE `inventory_archive`
  ADD CONSTRAINT `FK_Archive_Item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`),
  ADD CONSTRAINT `FK_Archive_Room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `FK_Archive_Worker` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `FK_Item_Room` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `FK_Picture_PictureType` FOREIGN KEY (`picture_type_id`) REFERENCES `picture_types` (`picture_type_id`);

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `FK_QRCode_Item` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);

--
-- Constraints for table `scanned_items`
--
ALTER TABLE `scanned_items`
  ADD CONSTRAINT `FK_ScannedItem_Worker` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `FK_Task_Worker` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `task_response`
--
ALTER TABLE `task_response`
  ADD CONSTRAINT `FK_TaskResponse_Task` FOREIGN KEY (`task_response_id`) REFERENCES `tasks` (`task_id`);

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `FK_Members_Team` FOREIGN KEY (`team_id`) REFERENCES `teams` (`team_id`),
  ADD CONSTRAINT `FK_Members_Worker` FOREIGN KEY (`worker_id`) REFERENCES `workers` (`worker_id`);

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `FK_Worker_Company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`),
  ADD CONSTRAINT `FK_Worker_Picture` FOREIGN KEY (`picture_id`) REFERENCES `pictures` (`picture_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
