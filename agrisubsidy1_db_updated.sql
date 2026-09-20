-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2026 at 04:03 PM
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
-- Database: `agrisubsidy1_db`
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
(143, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 13:58:35', '0000-00-00 00:00:00'),
(144, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:34:05', '0000-00-00 00:00:00'),
(145, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:34:19', '0000-00-00 00:00:00'),
(146, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:43:38', '0000-00-00 00:00:00'),
(147, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:44:45', '0000-00-00 00:00:00'),
(148, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:48:27', '0000-00-00 00:00:00'),
(149, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 14:51:11', '0000-00-00 00:00:00'),
(150, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:00:11', '0000-00-00 00:00:00'),
(151, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '112.211.32.52', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-17 15:09:38', '0000-00-00 00:00:00'),
(152, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4452:3b4:6600:4079:6ede:e7ee:25f8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:10:27', '0000-00-00 00:00:00'),
(153, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:10:31', '0000-00-00 00:00:00'),
(154, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:10:55', '0000-00-00 00:00:00'),
(155, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4452:3b4:6600:4079:6ede:e7ee:25f8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:12:29', '0000-00-00 00:00:00'),
(156, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4452:3b4:6600:4079:6ede:e7ee:25f8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:12:55', '0000-00-00 00:00:00'),
(157, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.19.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:13:56', '0000-00-00 00:00:00'),
(158, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '180.191.19.38', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:14:12', '0000-00-00 00:00:00'),
(159, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '49.151.252.137', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:17:35', '0000-00-00 00:00:00'),
(160, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:18:13', '0000-00-00 00:00:00'),
(161, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:19:35', '0000-00-00 00:00:00'),
(162, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4452:3b4:6600:4079:6ede:e7ee:25f8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:21:17', '0000-00-00 00:00:00'),
(163, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:22:29', '0000-00-00 00:00:00'),
(164, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4452:3b4:6600:4079:6ede:e7ee:25f8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-17 15:25:49', '0000-00-00 00:00:00'),
(165, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:26:18', '0000-00-00 00:00:00'),
(166, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:27:45', '0000-00-00 00:00:00'),
(167, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:31:07', '0000-00-00 00:00:00'),
(168, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:4cc8:ed0c:ffca:81ef', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-17 15:44:41', '0000-00-00 00:00:00'),
(169, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:39:50', '0000-00-00 00:00:00'),
(170, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:40:26', '0000-00-00 00:00:00'),
(171, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:41:14', '0000-00-00 00:00:00'),
(172, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:42:28', '0000-00-00 00:00:00'),
(173, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:42:48', '0000-00-00 00:00:00'),
(174, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:43:42', '0000-00-00 00:00:00'),
(175, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 05:44:08', '0000-00-00 00:00:00'),
(176, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:17:36', '0000-00-00 00:00:00'),
(177, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:19:21', '0000-00-00 00:00:00'),
(178, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:22:12', '0000-00-00 00:00:00');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `user_agent`, `created`, `updated`) VALUES
(179, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:22:35', '0000-00-00 00:00:00'),
(180, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:49:58', '0000-00-00 00:00:00'),
(181, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 06:50:22', '0000-00-00 00:00:00'),
(182, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 07:09:23', '0000-00-00 00:00:00'),
(183, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.211.17', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 07:13:42', '0000-00-00 00:00:00'),
(184, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '110.54.154.138', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-18 09:10:41', '0000-00-00 00:00:00'),
(185, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.52.59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 11:49:21', '0000-00-00 00:00:00'),
(186, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 12:00:09', '0000-00-00 00:00:00'),
(187, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 12:00:23', '0000-00-00 00:00:00'),
(188, 1, 'Cancelled Schedule', NULL, NULL, 'Schedules', '{\"program_code\":\"SD-4\",\"program_name\":\"Seed Subsidy Distribution\",\"status\":\"Cancelled\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:00:40', '0000-00-00 00:00:00'),
(189, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:00:56', '0000-00-00 00:00:00'),
(190, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:01:35', '0000-00-00 00:00:00'),
(191, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:22:01', '0000-00-00 00:00:00'),
(192, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:22:23', '0000-00-00 00:00:00'),
(193, 1, 'Cancelled Schedule', NULL, NULL, 'Schedules', '{\"program_code\":\"SD-4\",\"program_name\":\"Seed Subsidy Distribution\",\"status\":\"Cancelled\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:22:54', '0000-00-00 00:00:00'),
(194, 1, 'Cancelled Schedule', NULL, NULL, 'Schedules', '{\"program_code\":\"SD-4\",\"program_name\":\"Seed Subsidy Distribution\",\"old_status\":\"Scheduled\",\"new_status\":\"Cancelled\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:42:49', '0000-00-00 00:00:00'),
(195, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:43:20', '0000-00-00 00:00:00'),
(196, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:43:38', '0000-00-00 00:00:00'),
(197, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:44:00', '0000-00-00 00:00:00'),
(198, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.77.158', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 13:47:58', '0000-00-00 00:00:00'),
(199, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:04:45', '0000-00-00 00:00:00'),
(200, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '180.191.126.73', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:04:59', '0000-00-00 00:00:00'),
(201, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:06:39', '0000-00-00 00:00:00'),
(202, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:06:52', '0000-00-00 00:00:00'),
(203, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:10:53', '0000-00-00 00:00:00'),
(204, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:15:31', '0000-00-00 00:00:00'),
(205, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:16:19', '0000-00-00 00:00:00'),
(206, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:20:36', '0000-00-00 00:00:00'),
(207, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:24:12', '0000-00-00 00:00:00'),
(208, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:27:06', '0000-00-00 00:00:00'),
(209, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:39:18', '0000-00-00 00:00:00'),
(210, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:41:05', '0000-00-00 00:00:00'),
(211, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:50:57', '0000-00-00 00:00:00'),
(212, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:8db5:da33:910a:ab5e', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-18 14:51:29', '0000-00-00 00:00:00'),
(213, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '124.217.76.2', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-18 21:38:12', '0000-00-00 00:00:00'),
(214, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '124.217.70.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-18 21:39:41', '0000-00-00 00:00:00'),
(215, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-18 21:40:09', '0000-00-00 00:00:00'),
(216, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-19 02:15:48', '0000-00-00 00:00:00'),
(217, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-19 02:16:04', '0000-00-00 00:00:00'),
(218, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '49.151.197.21', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-19 02:16:17', '0000-00-00 00:00:00'),
(219, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '49.151.197.21', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 03:24:29', '0000-00-00 00:00:00'),
(220, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:51:37', '0000-00-00 00:00:00'),
(221, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:53:30', '0000-00-00 00:00:00'),
(222, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:54:18', '0000-00-00 00:00:00'),
(223, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:55:30', '0000-00-00 00:00:00'),
(224, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:58:44', '0000-00-00 00:00:00'),
(225, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:59:01', '0000-00-00 00:00:00'),
(226, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 07:59:19', '0000-00-00 00:00:00'),
(227, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:12:00', '0000-00-00 00:00:00'),
(228, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:12:27', '0000-00-00 00:00:00'),
(229, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:23:54', '0000-00-00 00:00:00'),
(230, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:24:46', '0000-00-00 00:00:00'),
(231, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:29:16', '0000-00-00 00:00:00'),
(232, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:a044:94a7:92ec:a5b7', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 08:29:45', '0000-00-00 00:00:00'),
(233, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '216.247.89.244', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Mobile Safari/537.36', '2026-09-19 08:36:04', '0000-00-00 00:00:00'),
(234, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '122.2.116.193', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 11:54:30', '0000-00-00 00:00:00'),
(235, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '112.202.33.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:03:46', '0000-00-00 00:00:00'),
(236, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:06:16', '0000-00-00 00:00:00'),
(237, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.52.59', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:09:21', '0000-00-00 00:00:00'),
(238, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.83.79.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:11:18', '0000-00-00 00:00:00'),
(239, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '112.202.33.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:14:32', '0000-00-00 00:00:00'),
(240, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 12:16:51', '0000-00-00 00:00:00'),
(241, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:32:21', '0000-00-00 00:00:00'),
(242, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:39:49', '0000-00-00 00:00:00'),
(243, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:40:04', '0000-00-00 00:00:00'),
(244, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:40:08', '0000-00-00 00:00:00'),
(245, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:40:20', '0000-00-00 00:00:00'),
(246, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:42:59', '0000-00-00 00:00:00'),
(247, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:43:10', '0000-00-00 00:00:00'),
(248, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:48:18', '0000-00-00 00:00:00'),
(249, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:50:56', '0000-00-00 00:00:00'),
(250, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 13:59:48', '0000-00-00 00:00:00'),
(251, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:01:19', '0000-00-00 00:00:00'),
(252, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:02:47', '0000-00-00 00:00:00'),
(253, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:05:30', '0000-00-00 00:00:00'),
(254, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:25:39', '0000-00-00 00:00:00'),
(255, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:31:34', '0000-00-00 00:00:00'),
(256, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:36:28', '0000-00-00 00:00:00'),
(257, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:36:46', '0000-00-00 00:00:00'),
(258, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:39:36', '0000-00-00 00:00:00'),
(259, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '122.2.116.193', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:43:56', '0000-00-00 00:00:00'),
(260, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '122.2.116.193', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:44:09', '0000-00-00 00:00:00'),
(261, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:47:19', '0000-00-00 00:00:00'),
(262, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.217.70.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:47:28', '0000-00-00 00:00:00'),
(263, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:50:16', '0000-00-00 00:00:00'),
(264, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:51:05', '0000-00-00 00:00:00'),
(265, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:856:c0a8:1adc:a346', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-19 14:52:11', '0000-00-00 00:00:00'),
(266, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.83.79.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 01:35:20', '0000-00-00 00:00:00'),
(267, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 03:10:37', '0000-00-00 00:00:00'),
(268, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.83.79.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 05:57:52', '0000-00-00 00:00:00'),
(269, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.83.79.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 06:47:01', '0000-00-00 00:00:00'),
(270, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '124.83.79.120', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 06:47:29', '0000-00-00 00:00:00'),
(271, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 07:52:50', '0000-00-00 00:00:00'),
(272, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:6535:3774:541c:a0ad', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:11:55', '0000-00-00 00:00:00'),
(273, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:19:18', '0000-00-00 00:00:00'),
(274, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:38:42', '0000-00-00 00:00:00'),
(275, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:38:44', '0000-00-00 00:00:00'),
(276, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:39:05', '0000-00-00 00:00:00'),
(277, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:39:26', '0000-00-00 00:00:00'),
(278, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:41:58', '0000-00-00 00:00:00'),
(279, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:42:41', '0000-00-00 00:00:00'),
(280, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 09:46:39', '0000-00-00 00:00:00'),
(281, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:6535:3774:541c:a0ad', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:20:42', '0000-00-00 00:00:00'),
(282, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:6535:3774:541c:a0ad', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:21:30', '0000-00-00 00:00:00'),
(283, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:6535:3774:541c:a0ad', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:23:11', '0000-00-00 00:00:00'),
(284, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '2001:4450:836f:a600:6535:3774:541c:a0ad', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:24:49', '0000-00-00 00:00:00'),
(285, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:28:55', '0000-00-00 00:00:00'),
(286, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:29:11', '0000-00-00 00:00:00'),
(287, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '2001:4450:836f:a600:adb3:5c14:71a2:9c56', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:29:51', '0000-00-00 00:00:00'),
(288, 166, 'login', 'User', 166, 'User logged in successfully', '{\"username\":\"@caebltzr123\",\"role\":\"farmer\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:30:52', '0000-00-00 00:00:00'),
(289, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '180.191.32.11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 10:53:47', '0000-00-00 00:00:00'),
(290, 166, 'login', 'User', 166, 'User logged in successfully', '{\"username\":\"@caebltzr123\",\"role\":\"farmer\"}', '2001:4450:836f:a600:b5d8:437d:12d5:f540', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-20 11:00:29', '0000-00-00 00:00:00'),
(291, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:24:59', '0000-00-00 00:00:00'),
(292, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:25:26', '0000-00-00 00:00:00'),
(293, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:25:43', '0000-00-00 00:00:00'),
(294, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:26:03', '0000-00-00 00:00:00'),
(295, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:26:21', '0000-00-00 00:00:00'),
(296, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:27:09', '0000-00-00 00:00:00'),
(297, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:27:19', '0000-00-00 00:00:00'),
(298, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 11:55:18', '0000-00-00 00:00:00'),
(299, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:16:38', '0000-00-00 00:00:00'),
(300, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:17:08', '0000-00-00 00:00:00'),
(301, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:18:15', '0000-00-00 00:00:00'),
(302, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:18:31', '0000-00-00 00:00:00'),
(303, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:19:26', '0000-00-00 00:00:00'),
(304, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:20:38', '0000-00-00 00:00:00'),
(305, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:21:06', '0000-00-00 00:00:00'),
(306, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:21:56', '0000-00-00 00:00:00'),
(307, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:22:09', '0000-00-00 00:00:00'),
(308, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:22:50', '0000-00-00 00:00:00'),
(309, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:23:59', '0000-00-00 00:00:00'),
(310, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:24:44', '0000-00-00 00:00:00'),
(311, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:24:58', '0000-00-00 00:00:00'),
(312, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:26:36', '0000-00-00 00:00:00'),
(313, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:26:55', '0000-00-00 00:00:00'),
(314, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:27:47', '0000-00-00 00:00:00'),
(315, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:28:07', '0000-00-00 00:00:00'),
(316, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:44:42', '0000-00-00 00:00:00'),
(317, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:44:59', '0000-00-00 00:00:00'),
(318, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:45:05', '0000-00-00 00:00:00'),
(319, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:45:18', '0000-00-00 00:00:00'),
(320, 17, 'logout', 'User', 17, 'User logged out successfully', '{\"username\":\"DA-Admin2026\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:46:16', '0000-00-00 00:00:00'),
(321, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 12:48:26', '0000-00-00 00:00:00'),
(322, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:04:27', '0000-00-00 00:00:00'),
(323, 165, 'logout', 'User', 165, 'User logged out successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:07:36', '0000-00-00 00:00:00'),
(324, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:17:43', '0000-00-00 00:00:00'),
(325, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:19:14', '0000-00-00 00:00:00'),
(326, 1, 'login', 'User', 1, 'User logged in successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:30:01', '0000-00-00 00:00:00'),
(327, 1, 'logout', 'User', 1, 'User logged out successfully', '{\"username\":\"DA-Agrisubsidy2026\",\"role\":\"staff\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:42:02', '0000-00-00 00:00:00'),
(328, 165, 'login', 'User', 165, 'User logged in successfully', '{\"username\":\"Farmer2\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36 Edg/153.0.0.0', '2026-09-20 13:57:38', '0000-00-00 00:00:00');

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
(172, 190, 0.00, 'Hybrid', 16.50, 'Yes', 32.20, 'Effective', 134, 251, 37, '2026-09-20 12:24:41', '2026-09-20 12:24:41');

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
(251, '02-31-35-007-000046', 'Maria', 'Garcia', 'Santos', '1989-07-22', 'Male', 'BUENAVISTA', '09234567894', 165, '2026-09-16 08:44:18', '2026-09-16 08:44:18'),
(252, '02-31-35-019-000078', 'John', 'Doe', 'Reyes', '1996-11-08', 'Male', 'MABINI', '09345678900', NULL, '2026-09-16 08:44:18', '2026-09-16 08:44:18'),
(693, '01', 'Caesar', 'Abalos', 'Baltazar', '0005-04-11', 'Male', 'Dubinan West', '09279929570', 166, '2026-09-20 10:30:45', '2026-09-20 10:30:45');

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
(1, 250, 4.30, 'SINSAYON', '2026-09-17 13:00:37', '2026-09-17 13:00:37'),
(190, 251, 2.80, 'BALINTOCATOC', '2026-09-20 12:20:09', '2026-09-20 12:20:09');

-- --------------------------------------------------------

--
-- Table structure for table `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `comment` text NOT NULL,
  `feedback_date` datetime NOT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `farmer_id`, `rating`, `comment`, `feedback_date`, `answer`) VALUES
(133, 251, 3.8, '', '2026-09-20 12:21:53', '{\"q1\":5,\"q2\":4,\"q3\":4,\"q4\":4,\"q5\":3,\"q6\":3}'),
(134, 251, 3.2, '', '2026-09-20 12:24:41', '{\"q1\":3,\"q2\":3,\"q3\":2,\"q4\":4,\"q5\":4,\"q6\":3}'),
(135, 251, 2.2, '', '2026-09-20 12:27:42', '{\"q1\":2,\"q2\":2,\"q3\":3,\"q4\":3,\"q5\":1,\"q6\":2}');

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
(58, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"FRM-007\",\"first_name\":\"Rona\",\"middle_name\":\"Valdez\",\"last_name\":\"Mauro\",\"gender\":\"Female\",\"contact_no\":\"09876543216\",\"address\":\"BALINTOCATOC\",\"birthdate\":\"2005-07-10\"},\"user\":{\"username\":\"Farmer2\",\"email\":\"Example04@gmail.com\",\"password\":\"Farmer@02\",\"confirm_password\":\"Farmer@02\",\"role\":\"farmer\"}}', '2026-09-17 13:25:36', '2026-09-17 14:42:58'),
(59, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"FRM-007\",\"first_name\":\"Rona\",\"middle_name\":\"Mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"09876543212\",\"address\":\"BALINTOCATOC\",\"birthdate\":\"2005-06-14\"},\"user\":{\"username\":\"Farmer2\",\"email\":\"Example04@gmail.com\",\"password\":\"Farmer@01\",\"confirm_password\":\"Farmer@01\",\"role\":\"farmer\"}}', '2026-09-17 14:44:22', '2026-09-17 14:44:50'),
(60, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"FRM-007\",\"first_name\":\"Rona\",\"middle_name\":\"Mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"09876543212\",\"address\":\"BALINTOCATOC\",\"birthdate\":\"2005-06-08\"},\"user\":{\"username\":\"Farmer2\",\"email\":\"Example04@gmail.com\",\"password\":\"Farmer@02\",\"confirm_password\":\"Farmer@02\",\"role\":\"farmer\"}}', '2026-09-17 14:51:03', '2026-09-17 14:51:18'),
(61, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"02-31-35-019-000078\",\"first_name\":\"John\",\"middle_name\":\"Reyes\",\"last_name\":\"Doe\",\"gender\":\"Male\",\"contact_no\":\"09345678900\",\"address\":\"MABINI\",\"birthdate\":\"2004-07-15\"},\"user\":{\"username\":\"Farmer7\",\"email\":\"example7@gmail.com\",\"password\":\"Farmer@7\",\"confirm_password\":\"Farmer@7\",\"role\":\"farmer\"}}', '2026-09-18 07:12:52', '2026-09-18 07:12:52'),
(62, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'declined', '{\"farmer\":{\"farmer_no\":\"6\",\"first_name\":\"mark\",\"middle_name\":\"o.\",\"last_name\":\"bentura\",\"gender\":\"Male\",\"contact_no\":\"09311233685\",\"address\":\"rosario\",\"birthdate\":\"2008-09-17\"},\"user\":{\"username\":\"marky\",\"email\":\"anthonyvinya4@gmai.com\",\"password\":\"Markanthonyvinuya_\",\"confirm_password\":\"Markanthonyvinuya_\",\"role\":\"farmer\"}}', '2026-09-19 07:57:48', '2026-09-19 12:27:57'),
(63, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"FRM-08089\",\"first_name\":\"Angelo\",\"middle_name\":\"Barrientos\",\"last_name\":\"Pajuelas\",\"gender\":\"Male\",\"contact_no\":\"09876543216\",\"address\":\"CALAO EAST\",\"birthdate\":\"2005-06-19\"},\"user\":{\"username\":\"Farmer08\",\"email\":\"exemail08@gmail.com\",\"password\":\"Farmer-08\",\"confirm_password\":\"Farmer-08\",\"role\":\"farmer\"}}', '2026-09-19 12:06:02', '2026-09-19 12:06:02'),
(64, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"FRM-080801\",\"first_name\":\"Mark Angelo\",\"middle_name\":\"Nudo\",\"last_name\":\"Gomez\",\"gender\":\"Male\",\"contact_no\":\"09876543219\",\"address\":\"CALAO WEST\",\"birthdate\":\"2003-06-19\"},\"user\":{\"username\":\"Farmer9\",\"email\":\"Farmer9@gmail.com\",\"password\":\"Farmer@09\",\"confirm_password\":\"Farmer@09\",\"role\":\"farmer\"}}', '2026-09-19 12:11:06', '2026-09-19 12:11:06'),
(65, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"FRM-080802\",\"first_name\":\"Mark Jayson\",\"middle_name\":\"Gomez\",\"last_name\":\"Guivarra\",\"gender\":\"Male\",\"contact_no\":\"09876543215\",\"address\":\"PATUL\",\"birthdate\":\"2000-06-14\"},\"user\":{\"username\":\"Farmer10\",\"email\":\"Farmer10@gmail.com\",\"password\":\"Farmer10@\",\"confirm_password\":\"Farmer10@\",\"role\":\"farmer\"}}', '2026-09-19 12:16:39', '2026-09-19 12:16:39'),
(66, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"7\",\"first_name\":\"anthony\",\"middle_name\":\"obra\",\"last_name\":\"vinuya\",\"gender\":\"Male\",\"contact_no\":\"09311233685\",\"address\":\"rosario\",\"birthdate\":\"2008-09-18\"},\"user\":{\"username\":\"anthony\",\"email\":\"anthonyvinya4@gmai.com\",\"password\":\"markAnthonyvinuya_\",\"confirm_password\":\"markAnthonyvinuya_\",\"role\":\"farmer\"}}', '2026-09-19 14:29:15', '2026-09-19 14:29:15'),
(67, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"01-1234-\",\"first_name\":\"Cae\",\"middle_name\":\"Baltazar\",\"last_name\":\"Abalos\",\"gender\":\"Male\",\"contact_no\":\"09587956988\",\"address\":\"Dubinan West\",\"birthdate\":\"2005-04-11\"},\"user\":{\"username\":\"@caebltzr123\",\"email\":\"caesarbaltazar11@gmail.com\",\"password\":\"Asdf1234@\",\"confirm_password\":\"Asdf1234@\",\"role\":\"farmer\"}}', '2026-09-20 10:21:16', '2026-09-20 10:21:16'),
(68, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"01\",\"first_name\":\"Caesar\",\"middle_name\":\"Baltazar\",\"last_name\":\"Abalos\",\"gender\":\"Male\",\"contact_no\":\"09279929570\",\"address\":\"Dubinan West\",\"birthdate\":\"0005-04-11\"},\"user\":{\"username\":\"@caebltzr123\",\"email\":\"caesarbaltazar11@gmail.com\",\"password\":\"Caebltzr@1234\",\"confirm_password\":\"Caebltzr@1234\",\"role\":\"farmer\"}}', '2026-09-20 10:28:06', '2026-09-20 10:30:45');

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

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `farmer_id`, `subsidy_item`, `quantity`, `received_date`, `status`, `schedule_id`, `confirmed_at`, `created_at`, `modified_at`) VALUES
(212, 251, 'Seed Subsidy', 20.50, '2026-09-11 16:30:09', 'Received', 31, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
  `end_time` time NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Scheduled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `program_code`, `program_name`, `description`, `barangay`, `start_date`, `end_date`, `start_time`, `end_time`, `status`) VALUES
(31, 'SD-1', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-11', '2026-09-11', '10:20:24', '16:30:00', 'Scheduled'),
(32, 'SD-2', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-12', '2026-09-12', '07:00:00', '17:00:00', 'Scheduled'),
(35, 'SD-3', 'Seed Subsidy Distribution', 'G-5', 'Balintocatoc', '2026-09-22', '2026-09-22', '07:00:00', '16:00:00', 'Scheduled'),
(36, 'SD-4', 'Seed Subsidy Distribution', 'G-9', 'Rizal', '2026-09-19', '2026-09-19', '08:00:00', '17:00:00', 'Cancelled'),
(37, 'SD-5', 'Seed Subsidy Distribution', 'G-7', 'BALINTOCATOC', '2026-09-20', '2026-09-21', '07:00:00', '16:00:00', 'Scheduled');

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
(1, 'DA-Agrisubsidy2026', '$2y$10$bcBgSJ4BCsCBKg9zlz7ozuNMOhzR/jZ3ie/4O7JfGu/jUhwCY81t6', 'example02@gmail.com', 'staff', '2026-06-17 05:17:00', '2026-09-20 13:30:01', 'pending', 0, NULL),
(17, 'DA-Admin2026', '$2y$10$b2QxJv4bUY01brrdj0PVsekb04C0d96.GRru.jI9/vcRdRXvrG38G', 'example01@gmail.com', 'admin', '2026-06-30 01:07:50', '2026-09-20 12:45:18', 'pending', 0, NULL),
(163, 'Farmer1', '$2y$10$2kJB0v3yPv5USF51k9fknub6u5htjgWzmkZ2iYdRnhGQSDg57LeNS', 'Example03@gmail.com', 'farmer', '2026-09-17 07:43:57', '2026-09-17 15:31:54', 'pending', 0, '2026-09-17 15:31:59'),
(165, 'Farmer2', '$2y$10$KL8.Dwf3i9km6Td16pfMhuDVXLcIVHTYuUcOzToCbvEGZbP9OHRly', 'Example04@gmail.com', 'farmer', '2026-09-17 14:51:18', '2026-09-20 13:57:38', 'pending', 0, NULL),
(166, '@caebltzr123', '$2y$10$5MVxPrr29Ej64XzFEe/1F.Wt60TK1xGAUZJIu2.qqDOCITMniSt7K', 'caesarbaltazar11@gmail.com', 'farmer', '2026-09-20 10:30:44', '2026-09-20 11:00:29', 'pending', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=329;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=174;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=694;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

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
