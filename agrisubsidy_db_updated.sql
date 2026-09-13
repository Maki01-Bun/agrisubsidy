-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2026 at 03:45 PM
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
(6, 39, 'login', 'User', 39, 'User logged in successfully', '{\"username\":\"Farmer1\",\"role\":\"farmer\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 12:52:46', '0000-00-00 00:00:00'),
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
(25, 17, 'login', 'User', 17, 'User logged in successfully', '{\"username\":\"superadmin\",\"role\":\"admin\"}', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', '2026-09-13 13:43:53', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `farm_id` int(11) NOT NULL,
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
(218, '02-31-35-003-000414', 'Farmer', '1', 'Beneficiary', '1991-03-15', 'Male', 'BALINTOCATOC, SANTIAGO CITY', '9123456750', 39, '2026-09-12 12:14:37', '2026-09-12 12:14:37'),
(219, '02-31-35-007-000046', 'Farmer', '2', 'Beneficiary', '1989-07-22', 'Male', 'BUENAVISTA, SANTIAGO CITY', '9234567894', 40, '2026-09-12 12:14:37', '2026-09-12 12:15:17'),
(220, '02-31-35-019-000078', 'Farmer', '3', 'Beneficiary', '1996-11-08', 'Male', 'MABINI, SANTIAGO CITY', '09345678900', 41, '2026-09-12 12:14:37', '2026-09-12 14:10:54'),
(221, '02-31-35-008-000081', 'Farmer', '4', 'Beneficiary', '1998-06-06', 'Female', 'RIZAL, SANTIAGO CITY', '9876543217', 42, '2026-09-12 12:14:37', '2026-09-12 12:14:58'),
(222, '02-31-35-002-000069', 'Farmer', '5', 'Beneficiary', '1989-09-05', 'Female', 'BATAL, SANTIAGO CITY', '9876543218', 43, '2026-09-12 12:14:37', '2026-09-12 14:10:37'),
(223, '02-31-35-028-000048', 'Farmer', '6', 'Beneficiary', '1998-06-23', 'Male', 'SALVADOR, SANTIAGO CITY', '09876654321', NULL, '2026-09-12 14:05:15', '2026-09-12 14:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `farms`
--

CREATE TABLE `farms` (
  `id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `farm_name` varchar(150) NOT NULL,
  `farm_size` decimal(10,2) NOT NULL,
  `location` varchar(255) NOT NULL,
  `average_yield` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`id`, `farmer_id`, `farm_name`, `farm_size`, `location`, `average_yield`, `created`, `modified`) VALUES
(182, 218, 'Farm 1', 1.00, 'NABBUAN, SANTIAGO CITY', 100.00, '2026-09-13 12:27:30', '2026-09-13 12:27:30'),
(183, 219, 'Farm 2', 0.51, 'CABULAY, SANTIAGO CITY', 120.00, '2026-09-13 12:27:30', '2026-09-13 12:27:30'),
(184, 220, 'Farm 3', 0.67, 'MABINI, SANTIAGO CITY', 89.50, '2026-09-13 12:27:30', '2026-09-13 12:27:30'),
(185, 221, 'Farm 4', 1.50, 'BALUARTE, SANTIAGO CITY', 140.00, '2026-09-13 12:27:30', '2026-09-13 12:27:30'),
(186, 222, 'Farm 5', 0.95, 'SAN ISIDRO, SANTIAGO CITY', 160.00, '2026-09-13 12:27:30', '2026-09-13 12:27:30');

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
(54, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"02-31-35-028-000048\",\"first_name\":\"Farmer\",\"middle_name\":\"Beneficiary\",\"last_name\":\"6\",\"gender\":\"Male\",\"contact_no\":\"09876654321\",\"address\":\"SALVADOR, SANTIAGO CITY\",\"birthdate\":\"1998-06-23\"},\"user\":{\"username\":\"Farmer6\",\"password\":\"Farmer@06\",\"confirm_password\":\"Farmer@06\",\"role\":\"farmer\"}}', '2026-09-12 14:08:10', '2026-09-12 14:08:10');

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
  `received_date` datetime NOT NULL,
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
(201, 219, 'Seed Subsidy', 120.00, '2026-09-12 16:48:32', 'Received', 32, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

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
(32, 'SD-2', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-12', '2026-09-12', '07:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
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

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created`, `modified`, `status`, `failed_attempts`, `locked_until`) VALUES
(1, 'admin', '$2y$10$mOsQfa7o.YJXIKkzMQa/iOCMpMay0vaZWOmXDE2Z2oT.r7Yl8kRP2', 'staff', '2026-06-17 05:17:00', '2026-09-13 13:42:46', 'pending', 0, NULL),
(17, 'superadmin', '$2y$10$6LlYegtiDE46XLuhzPnJoe8ii32u7yYbHyf1GSkjY3kK.3eHf8tOK', 'admin', '2026-06-30 01:07:50', '2026-09-13 13:43:53', 'pending', 0, NULL),
(39, 'Farmer1', '$2y$10$My45YbsX7Mr9IybpQhSkRuW0cdYOrfbhIJBA2dxyk1JlYyYjOlQla', 'farmer', '2026-09-12 12:49:59', '2026-09-13 12:52:46', 'pending', 0, NULL),
(40, 'Farmer2', '$2y$10$bi0ADL4UdCJFdqEFYn4GTuzUorWkZ5T2K5OF6BdBCdRiKUN0bWvz2', 'farmer', '2026-09-12 12:51:07', '2026-09-12 12:56:45', 'pending', 0, NULL),
(41, 'Farmer3', '$2y$10$.OdBgKjEyYClQBRt1.j3W.Bo3C7WD78XEdKldHKFbc/df43zaXm/i', 'farmer', '2026-09-12 12:51:44', '2026-09-12 12:57:11', 'pending', 0, NULL),
(42, 'Farmer4', '$2y$10$JQ7RcaTbRWKzpNHbT2qtuuvJDyEECbHpqiLPL88H1CX0P9a863BAW', 'farmer', '2026-09-12 12:52:10', '2026-09-12 12:57:45', 'pending', 0, NULL),
(43, 'Farmer5', '$2y$10$g1.zgMTXn9e2SBBNaOo8yeRKXfqvwGMDK2cPOkX61hybCWMaoBvkW', 'farmer', '2026-09-12 12:52:47', '2026-09-12 12:58:00', 'pending', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
