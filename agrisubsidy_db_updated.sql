-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2026 at 04:04 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agrisubsidy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `user_agent`, `created`, `updated`) VALUES
(1, 1, 'created', 'Farm', 182, 'Farm record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":2,\"farm_name\":\"Farm 1\",\"farmer_id\":218,\"farmer_name\":\"Farmer 1\",\"farm_size\":1,\"location\":\"NABBUAN, SANTIAGO CITY\",\"average_yield\":100}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 'created', 'Farm', 183, 'Farm record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":3,\"farm_name\":\"Farm 2\",\"farmer_id\":219,\"farmer_name\":\"Farmer 2\",\"farm_size\":0.5124,\"location\":\"CABULAY, SANTIAGO CITY\",\"average_yield\":120}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 'created', 'Farm', 184, 'Farm record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":4,\"farm_name\":\"Farm 3\",\"farmer_id\":220,\"farmer_name\":\"Farmer 3\",\"farm_size\":0.6666,\"location\":\"MABINI, SANTIAGO CITY\",\"average_yield\":89.5}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 1, 'created', 'Farm', 185, 'Farm record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":5,\"farm_name\":\"Farm 4\",\"farmer_id\":221,\"farmer_name\":\"Farmer 4\",\"farm_size\":1.5,\"location\":\"BALUARTE, SANTIAGO CITY\",\"average_yield\":140}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 1, 'created', 'Farm', 186, 'Farm record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":6,\"farm_name\":\"Farm 5\",\"farmer_id\":222,\"farmer_name\":\"Farmer 5\",\"farm_size\":0.95,\"location\":\"SAN ISIDRO, SANTIAGO CITY\",\"average_yield\":160}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:53:58', '0000-00-00 00:00:00'),
(9, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:55:56', '0000-00-00 00:00:00'),
(10, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:58:08', '0000-00-00 00:00:00'),
(11, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:58:13', '0000-00-00 00:00:00'),
(12, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:59:24', '0000-00-00 00:00:00'),
(13, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:00:22', '0000-00-00 00:00:00'),
(14, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:01:06', '0000-00-00 00:00:00'),
(15, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:01:10', '0000-00-00 00:00:00'),
(16, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:01:47', '0000-00-00 00:00:00'),
(17, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:02:58', '0000-00-00 00:00:00'),
(18, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:16:18', '0000-00-00 00:00:00'),
(19, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:16:24', '0000-00-00 00:00:00'),
(20, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:22:45', '0000-00-00 00:00:00'),
(21, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:24:59', '0000-00-00 00:00:00'),
(22, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Linux; Android 15; Pixel 9) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36', '2026-09-13 13:40:55', '0000-00-00 00:00:00'),
(23, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:42:46', '0000-00-00 00:00:00'),
(24, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:43:00', '0000-00-00 00:00:00'),
(25, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:43:53', '0000-00-00 00:00:00'),
(26, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 01:41:54', '0000-00-00 00:00:00'),
(27, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 01:42:09', '0000-00-00 00:00:00'),
(28, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 01:42:16', '0000-00-00 00:00:00'),
(29, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 01:59:42', '0000-00-00 00:00:00'),
(30, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:01:23', '0000-00-00 00:00:00'),
(31, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:02:54', '0000-00-00 00:00:00'),
(32, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:05:14', '0000-00-00 00:00:00'),
(33, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:05:25', '0000-00-00 00:00:00'),
(34, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:05:29', '0000-00-00 00:00:00'),
(35, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:08:44', '0000-00-00 00:00:00'),
(36, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:09:13', '0000-00-00 00:00:00'),
(37, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:10:19', '0000-00-00 00:00:00'),
(38, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:10:30', '0000-00-00 00:00:00'),
(40, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:12:46', '0000-00-00 00:00:00'),
(41, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:02', '0000-00-00 00:00:00'),
(42, 1, 'created', 'Record', 202, 'Seed Subsidy record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":2,\"farmer_id\":218,\"farmer_name\":\"Farmer 1\",\"schedule_id\":31,\"program_code\":\"SD-1\",\"schedule_start_date\":\"2026-09-11\",\"subsidy_item\":\"Seed Subsidy\",\"quantity\":\"130\",\"received_date\":\"2026-08-20\",\"status\":\"Received\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:12', '0000-00-00 00:00:00'),
(43, 1, 'created', 'Record', 203, 'Seed Subsidy record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":3,\"farmer_id\":222,\"farmer_name\":\"Farmer 5\",\"schedule_id\":31,\"program_code\":\"SD-1\",\"schedule_start_date\":\"2026-09-11\",\"subsidy_item\":\"Seed Subsidy\",\"quantity\":\"120\",\"received_date\":\"2026-08-21\",\"status\":\"Not Received\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:12', '0000-00-00 00:00:00'),
(44, 1, 'created', 'Record', 204, 'Seed Subsidy record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":4,\"farmer_id\":220,\"farmer_name\":\"Farmer 3\",\"schedule_id\":32,\"program_code\":\"SD-2\",\"schedule_start_date\":\"2026-09-12\",\"subsidy_item\":\"Seed Subsidy\",\"quantity\":\"150\",\"received_date\":\"2026-08-21\",\"status\":\"Received\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:12', '0000-00-00 00:00:00'),
(45, 1, 'created', 'Record', 205, 'Seed Subsidy record imported from Excel', '{\"source\":\"Excel import\",\"excel_row\":5,\"farmer_id\":221,\"farmer_name\":\"Farmer 4\",\"schedule_id\":32,\"program_code\":\"SD-2\",\"schedule_start_date\":\"2026-09-12\",\"subsidy_item\":\"Seed Subsidy\",\"quantity\":\"100\",\"received_date\":\"2026-08-21\",\"status\":\"Cancelled\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:12', '0000-00-00 00:00:00'),
(46, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:21', '0000-00-00 00:00:00'),
(47, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:35', '0000-00-00 00:00:00'),
(48, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:17:56', '0000-00-00 00:00:00'),
(49, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:18:00', '0000-00-00 00:00:00'),
(50, 1, 'Import Record', NULL, NULL, 'Imported Seed Subsidy record for farmer \'Farmer 1\' (Schedule ID: 31, Program Code: SD-1, Quantity: 130, Status: Received)', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:24:15', '0000-00-00 00:00:00'),
(51, 1, 'Import Record', NULL, NULL, 'Imported Seed Subsidy record for farmer \'Farmer 5\' (Schedule ID: 31, Program Code: SD-1, Quantity: 120, Status: Not Received)', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:25:30', '0000-00-00 00:00:00'),
(52, 1, 'Import Record', NULL, NULL, 'Imported Seed Subsidy record for farmer \'Farmer 3\' (Schedule ID: 32, Program Code: SD-2, Quantity: 150, Status: Received)', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:25:30', '0000-00-00 00:00:00'),
(53, 1, 'Import Record', NULL, NULL, 'Imported Seed Subsidy record for farmer \'Farmer 4\' (Schedule ID: 32, Program Code: SD-2, Quantity: 100, Status: Cancelled)', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:25:30', '0000-00-00 00:00:00'),
(54, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:30:18', '0000-00-00 00:00:00'),
(55, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:30:37', '0000-00-00 00:00:00'),
(56, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-14 02:31:17', '0000-00-00 00:00:00'),
(57, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:10:21', '0000-00-00 00:00:00'),
(58, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:10:56', '0000-00-00 00:00:00'),
(61, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:16:49', '0000-00-00 00:00:00'),
(62, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:27:36', '0000-00-00 00:00:00'),
(63, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 02:27:42', '0000-00-00 00:00:00'),
(64, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 03:14:43', '0000-00-00 00:00:00'),
(65, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 05:50:25', '0000-00-00 00:00:00'),
(66, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:26:23', '0000-00-00 00:00:00'),
(69, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:27:27', '0000-00-00 00:00:00'),
(70, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:32:22', '0000-00-00 00:00:00'),
(73, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:32:47', '0000-00-00 00:00:00'),
(74, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"admin\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:58:46', '0000-00-00 00:00:00'),
(75, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 06:58:51', '0000-00-00 00:00:00'),
(76, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-15 07:40:06', '0000-00-00 00:00:00'),
(77, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 00:24:31', '0000-00-00 00:00:00'),
(78, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 00:28:00', '0000-00-00 00:00:00'),
(79, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 00:28:16', '0000-00-00 00:00:00'),
(80, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 01:03:57', '0000-00-00 00:00:00'),
(83, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 01:05:21', '0000-00-00 00:00:00'),
(84, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 01:14:58', '0000-00-00 00:00:00'),
(85, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 01:17:24', '0000-00-00 00:00:00'),
(86, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 05:54:05', '0000-00-00 00:00:00'),
(87, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 07:59:24', '0000-00-00 00:00:00'),
(88, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 07:59:50', '0000-00-00 00:00:00'),
(89, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 08:26:22', '0000-00-00 00:00:00'),
(90, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 08:26:40', '0000-00-00 00:00:00'),
(91, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 08:29:24', '0000-00-00 00:00:00'),
(92, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-16 08:29:51', '0000-00-00 00:00:00'),
(93, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 06:06:51', '0000-00-00 00:00:00'),
(94, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Linux; Android 14; SM-A556B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36', '2026-09-17 06:55:47', '0000-00-00 00:00:00'),
(95, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Linux; Android 14; SM-A556B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Mobile Safari/537.36', '2026-09-17 06:56:03', '0000-00-00 00:00:00'),
(96, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:01:50', '0000-00-00 00:00:00'),
(97, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:02:19', '0000-00-00 00:00:00'),
(98, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:02:39', '0000-00-00 00:00:00'),
(99, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:04:38', '0000-00-00 00:00:00'),
(100, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:20:56', '0000-00-00 00:00:00'),
(101, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:24:51', '0000-00-00 00:00:00'),
(102, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:39:54', '0000-00-00 00:00:00'),
(103, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:43:11', '0000-00-00 00:00:00'),
(104, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:44:24', '0000-00-00 00:00:00'),
(105, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 07:44:36', '0000-00-00 00:00:00'),
(106, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:01:37', '0000-00-00 00:00:00'),
(107, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:02:24', '0000-00-00 00:00:00'),
(108, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:03:52', '0000-00-00 00:00:00'),
(109, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:04:07', '0000-00-00 00:00:00'),
(110, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:12:35', '0000-00-00 00:00:00'),
(111, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:12:53', '0000-00-00 00:00:00'),
(112, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:14:52', '0000-00-00 00:00:00'),
(113, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:15:35', '0000-00-00 00:00:00'),
(114, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:16:15', '0000-00-00 00:00:00'),
(115, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 08:16:49', '0000-00-00 00:00:00'),
(116, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 09:11:28', '0000-00-00 00:00:00'),
(117, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 09:21:06', '0000-00-00 00:00:00'),
(118, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 09:21:20', '0000-00-00 00:00:00'),
(119, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 09:21:46', '0000-00-00 00:00:00'),
(120, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 09:26:43', '0000-00-00 00:00:00'),
(121, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 10:47:39', '0000-00-00 00:00:00'),
(122, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 10:51:30', '0000-00-00 00:00:00'),
(123, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 10:53:10', '0000-00-00 00:00:00'),
(124, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 11:51:52', '0000-00-00 00:00:00'),
(125, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 11:52:08', '0000-00-00 00:00:00'),
(126, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 12:37:40', '0000-00-00 00:00:00'),
(127, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 12:41:25', '0000-00-00 00:00:00'),
(128, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 12:51:41', '0000-00-00 00:00:00'),
(129, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 12:55:39', '0000-00-00 00:00:00'),
(130, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:00:20', '0000-00-00 00:00:00'),
(131, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:09:28', '0000-00-00 00:00:00'),
(132, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:09:53', '0000-00-00 00:00:00'),
(133, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:15:03', '0000-00-00 00:00:00'),
(134, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:15:21', '0000-00-00 00:00:00'),
(135, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:21:14', '0000-00-00 00:00:00'),
(136, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:21:29', '0000-00-00 00:00:00'),
(137, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:22:18', '0000-00-00 00:00:00'),
(138, 163, 'login', 'User', 163, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:22:30', '0000-00-00 00:00:00'),
(139, 163, 'logout', 'User', 163, 'User logged out successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:22:31', '0000-00-00 00:00:00'),
(140, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:22:48', '0000-00-00 00:00:00'),
(141, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:23:03', '0000-00-00 00:00:00'),
(142, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:27:46', '0000-00-00 00:00:00'),
(143, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:58:35', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `average_yield` decimal(10,2) NOT NULL,
  `rice_type` enum('Hybrid','Inbred','','') NOT NULL,
  `crop_yield_after` decimal(10,2) NOT NULL,
  `subsidy_received` enum('Yes','No','','') NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `effectiveness_label` varchar(100) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `farm_id`, `average_yield`, `rice_type`, `crop_yield_after`, `subsidy_received`, `selling_price`, `effectiveness_label`, `feedback_id`, `farmer_id`, `schedule_id`, `created`, `modified`) VALUES
(170, 1, 25.80, 'Inbred', 28.50, 'Yes', 22.20, 'Effective', 132, 250, 31, '2026-09-17 11:51:09', '2026-09-17 11:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `farmer_no` varchar(50) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_no` varchar(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farmers`
--

INSERT INTO `farmers` (`id`, `farmer_no`, `first_name`, `last_name`, `middle_name`, `birthdate`, `gender`, `address`, `contact_no`, `user_id`, `created`, `modified`) VALUES
(250, '02-31-35-003-000414', 'Tres', 'Cruz', 'Dela', '1991-03-15', 'Male', 'BALINTOCATOC', '09123456750', 163, '2026-09-16 08:44:18', '2026-09-16 08:44:18'),
(251, '02-31-35-007-000046', 'Maria', 'Garcia', 'Santos', '1989-07-22', 'Male', 'BUENAVISTA', '09234567894', NULL, '2026-09-16 08:44:18', '2026-09-16 08:44:18'),
(252, '02-31-35-019-000078', 'John', 'Doe', 'Reyes', '1996-11-08', 'Male', 'MABINI', '09345678900', NULL, '2026-09-16 08:44:18', '2026-09-16 08:44:18');

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE `farms` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `farm_size` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`id`, `farmer_id`, `farm_size`, `location`, `created`, `modified`) VALUES
(1, 250, 4.30, 'SINSAYON', '2026-09-17 13:00:37', '2026-09-17 13:00:37');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` text NOT NULL,
  `feedback_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `farmer_id`, `rating`, `comment`, `feedback_date`) VALUES
(132, 250, 4.3, '', '2026-09-17 11:51:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `data` longtext DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `status`, `data`, `created`, `modified`) VALUES
(58, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"FRM-007\",\"first_name\":\"Rona\",\"middle_name\":\"Valdez\",\"last_name\":\"Mauro\",\"gender\":\"Female\",\"contact_no\":\"09876543216\",\"address\":\"BALINTOCATOC\",\"birthdate\":\"2005-07-10\"},\"user\":{\"username\":\"Farmer2\",\"email\":\"Example04@gmail.com\",\"password\":\"Farmer@02\",\"confirm_password\":\"Farmer@02\",\"role\":\"farmer\"}}', '2026-09-17 13:25:36', '2026-09-17 13:25:36');

-- --------------------------------------------------------

--
-- Table structure for table `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) DEFAULT NULL,
  `subsidy_item` varchar(150) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `received_date` datetime DEFAULT NULL,
  `status` enum('Pending','Received','Not Received','Cancelled','Re-Scheduled') NOT NULL,
  `schedule_id` int(11) DEFAULT NULL,
  `confirmed_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `program_code` varchar(100) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `barangay` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `program_code`, `program_name`, `description`, `barangay`, `start_date`, `end_date`, `start_time`, `end_time`) VALUES
(31, 'SD-1', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-11', '2026-09-11', '10:20:24', '16:30:00'),
(32, 'SD-2', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-12', '2026-09-12', '07:00:00', '17:00:00'),
(35, 'SD-3', 'Seed Subsidy Distribution', 'G-6', 'Balintocatoc', '2026-09-22', '2026-09-22', '07:00:00', '16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('farmer','admin','staff') NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `failed_attempts` int(11) DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created`, `modified`, `status`, `failed_attempts`, `locked_until`) VALUES
(1, 'DA-Agrisubsidy2026', '$2y$10$bcBgSJ4BCsCBKg9zlz7ozuNMOhzR/jZ3ie/4O7JfGu/jUhwCY81t6', 'example02@gmail.com', 'staff', '2026-06-17 05:17:00', '2026-09-17 13:58:35', 'pending', 0, NULL),
(17, 'DA-Admin2026', '$2y$10$b2QxJv4bUY01brrdj0PVsekb04C0d96.GRru.jI9/vcRdRXvrG38G', 'example01@gmail.com', 'admin', '2026-06-30 01:07:50', '2026-09-17 07:43:11', 'pending', 0, NULL),
(163, 'Farmer1', '$2y$10$2kJB0v3yPv5USF51k9fknub6u5htjgWzmkZ2iYdRnhGQSDg57LeNS', 'Example03@gmail.com', 'farmer', '2026-09-17 07:43:57', '2026-09-17 13:22:30', 'pending', 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `feedback_id` (`feedback_id`),
  ADD KEY `farm_id` (`farm_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `schedule_id_2` (`schedule_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `farms`
--
ALTER TABLE `farms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `farmer_id` (`farmer_id`),
  ADD KEY `schedule_id` (`schedule_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=253;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`);

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `farmers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `farms`
--
ALTER TABLE `farms`
  ADD CONSTRAINT `farms_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`);

--
-- Constraints for table `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`),
  ADD CONSTRAINT `records_ibfk_2` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
