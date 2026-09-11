-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 11, 2026 at 04:26 AM
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
  `action_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(2, '2334535', 'Marc', 'sardinia', 'Gomez', '2008-07-11', 'Female', 'balintocatoc', '09876543219', 10, '2026-07-14 14:54:19', '2026-08-11 11:57:42'),
(152, '2147483647', 'jane', 'mauro', 'Valdez', '2008-07-15', 'Female', 'balintocatoc', '09876654321', NULL, '2026-07-15 13:09:07', '2026-07-15 13:09:07'),
(153, '4234535', 'Jayson', 'Nudo', 'Rovillos', '2008-08-10', 'Male', 'Dub West Santiago City', '09876543210', 26, '2026-07-15 13:10:21', '2026-08-11 11:58:24'),
(156, '7645244', 'Rona', 'Padilla', 'Mauro', '2026-07-10', 'Male', 'Dub West Santiago City', '09123456789', NULL, '2026-07-15 13:42:48', '2026-07-15 13:42:48'),
(159, '1423345345', 'Rona', 'sardinia', 'Tolentino', '2026-07-01', 'Male', 'Dub West Santiago City', '09876543213', NULL, '2026-07-16 03:16:54', '2026-07-16 03:16:54'),
(162, '24435456', 'Caezar', 'Abalos', 'Baltazar', '2008-07-08', 'Male', 'balintocatoc ,santiago city', '0986543216', 30, '2026-07-18 12:20:37', '2026-07-18 12:20:37'),
(163, '1423345345', 'jane', 'Nudo', 'Mauro', '2005-02-09', 'Male', 'Fortune, Aurora, Alicia, Isabela', '09123456789', 36, '2026-07-23 07:33:54', '2026-07-23 07:33:54'),
(164, '112', 'Emma', 'Tan', 'Yee', '2005-04-11', 'Male', '#367, Purok 6 Dubinan West, Santiago City Isabela', '09125478999', 37, '2026-07-31 07:37:17', '2026-08-31 05:27:37'),
(165, '1423345345', 'Marc', 'Padilla', 'Tolentino', '2005-06-01', 'Male', 'balintocatoc', '09876543221', NULL, '2026-08-06 12:26:24', '2026-08-11 11:58:46'),
(166, '4234535', 'Marc', 'Nudo', 'Gomez', '2008-07-02', 'Male', 'balintocatoc', '09123456789', 38, '2026-08-29 13:12:04', '2026-08-29 13:12:04'),
(207, 'FMR-0001', 'Juan', 'Cruz', 'Dela', '1990-03-15', 'Male', 'Alicia, Isabela', '9123456789', NULL, '2026-08-30 11:38:50', '2026-08-30 11:38:50'),
(208, 'FMR-0002', 'Maria', 'Garcia', 'Santos', '1988-07-22', 'Female', 'Santiago City, Isabela', '9234567890', NULL, '2026-08-30 11:38:50', '2026-08-30 11:38:50'),
(209, 'FMR-0003', 'Pedro', 'Dela Cruz', 'Reyes', '1995-11-08', 'Male', 'Echague, Isabela', '9345678901', NULL, '2026-08-30 11:38:50', '2026-08-30 11:38:50'),
(210, 'FRM-007', 'Zhay', 'Guillermo', 'Gomez', '2005-02-14', 'Male', 'Alicia,isabela', '09876543216', NULL, '2026-08-31 02:33:44', '2026-08-31 02:33:44'),
(211, 'FRM-007', 'Zhay', 'Nudo', 'Gomez', '2004-02-17', 'Male', 'Fortune, Aurora, Alicia, Isabela', '09876543222', NULL, '2026-08-31 02:34:36', '2026-08-31 02:34:36'),
(212, 'FMR-0009', 'Dos', 'Cruz', 'Dela', '1990-03-15', 'Male', 'Alicia, Isabela', '9123456750', NULL, '2026-08-31 02:45:36', '2026-08-31 02:45:36'),
(213, 'FMR-0008', 'Mario', 'Garcia', 'Santos', '1988-07-22', 'Male', 'Santiago City, Isabela', '9234567894', NULL, '2026-08-31 02:45:36', '2026-08-31 02:45:36'),
(214, 'FMR-007', 'Peter', 'Dela Cruz', 'Reyes', '1995-11-08', 'Male', 'Echague, Isabela', '9345678900', NULL, '2026-08-31 02:45:36', '2026-08-31 02:45:36'),
(215, 'FRM-00008', 'Michael', 'Jordan', 'Pacquiao', '1989-02-21', 'Male', '#367, Purok 6 Dubinan West, Santiago City Isabela', '09876775543', NULL, '2026-08-31 05:37:15', '2026-08-31 05:37:15'),
(216, 'FRM-0004', 'Lizian', 'Gomez', 'Lei', '1997-02-06', 'Male', '#367, Purok 6 Dubinan West, Santiago City Isabela', '09876543323', NULL, '2026-09-06 07:36:04', '2026-09-06 07:36:04'),
(217, 'FRM-0017', 'Roi', 'Nudo', 'Gomez', '2005-01-11', 'Male', '#367, Purok 6 Dubinan West, Santiago City Isabela', '09876543218', NULL, '2026-09-06 07:40:43', '2026-09-06 07:40:43');

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
(162, 153, 'Farm 1', 1.30, 'Rizal, Santiago City', 500.00, '2026-09-10 03:54:42', '2026-09-10 03:54:42');

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
(125, 153, 4.3, '', '2026-09-10 01:56:32');

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
(44, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"1423345345\",\"first_name\":\"jane\",\"middle_name\":\"Mauro\",\"last_name\":\"Nudo\",\"gender\":\"Male\",\"contact_no\":\"09123456789\",\"address\":\"Fortune, Aurora, Alicia, Isabela\",\"birthdate\":\"2005-02-09\"},\"user\":{\"username\":\"Marlon L. Castro\",\"password\":\"12345678@a\",\"confirm_password\":\"12345678@a\",\"role\":\"farmer\"}}', '2026-07-16 06:42:20', '2026-07-23 07:33:54'),
(45, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"4234535\",\"first_name\":\"Marc\",\"middle_name\":\"Gomez\",\"last_name\":\"Nudo\",\"gender\":\"Male\",\"contact_no\":\"09123456789\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-02\"},\"user\":{\"username\":\"Marck\",\"password\":\"Marc01@2\",\"confirm_password\":\"Marc01@2\",\"role\":\"farmer\"}}', '2026-07-23 07:35:44', '2026-08-29 13:12:04'),
(46, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'declined', '{\"farmer\":{\"farmer_no\":\"7866868\",\"first_name\":\"jane\",\"middle_name\":\"mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"21474836474\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"jane\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 05:52:27', '2026-07-30 05:53:25'),
(48, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"2147483647\",\"first_name\":\"jane\",\"middle_name\":\"mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"09876654321\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"jane\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 06:05:07', '2026-07-30 06:05:07'),
(49, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"2147483647\",\"first_name\":\"jane\",\"middle_name\":\"Valdez\",\"last_name\":\"mauro\",\"gender\":\"Female\",\"contact_no\":\"09876654321\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"janee\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 06:07:41', '2026-07-30 06:07:41'),
(50, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"4234535\",\"first_name\":\"Marc\",\"middle_name\":\"Tolentino\",\"last_name\":\"Padilla\",\"gender\":\"Male\",\"contact_no\":\"09123456789\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-29\"},\"user\":{\"username\":\"Marc\",\"password\":\"1234567@M\",\"confirm_password\":\"1234567@M\",\"role\":\"farmer\"}}', '2026-07-31 06:16:27', '2026-07-31 06:16:27'),
(51, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"0112\",\"first_name\":\"Emma\",\"middle_name\":\"Yee\",\"last_name\":\"Tan\",\"gender\":\"Female\",\"contact_no\":\"09125478999\",\"address\":\"#367, Purok 6 Dubinan West, Santiago City Isabela\",\"birthdate\":\"2005-04-11\"},\"user\":{\"username\":\"Emma T\",\"password\":\"Emma@*1234\",\"confirm_password\":\"Emma@*1234\",\"role\":\"farmer\"}}', '2026-07-31 07:34:14', '2026-07-31 07:37:17'),
(52, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"4234535\",\"first_name\":\"Marc\",\"middle_name\":\"Tolentino\",\"last_name\":\"mauro\",\"gender\":\"Female\",\"contact_no\":\"09876543212\",\"address\":\"Alicia,isabela\",\"birthdate\":\"2008-08-06\"},\"user\":{\"username\":\"maki01\",\"password\":\"marc01@M\",\"confirm_password\":\"marc01@M\",\"role\":\"farmer\"}}', '2026-08-29 13:10:12', '2026-08-29 13:10:12'),
(53, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"FRM-0037\",\"first_name\":\"Rona\",\"middle_name\":\"Valdez\",\"last_name\":\"Abalos\",\"gender\":\"Male\",\"contact_no\":\"09876543289\",\"address\":\"Santiago city\",\"birthdate\":\"2005-07-10\"},\"user\":{\"username\":\"Onami\",\"password\":\"Onamu01@\",\"confirm_password\":\"Onamu01@\",\"role\":\"farmer\"}}', '2026-09-06 07:46:19', '2026-09-06 07:46:19');

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

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `program_code` varchar(100) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `baranggay` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `program_code`, `program_name`, `description`, `baranggay`, `start_date`, `end_date`, `start_time`, `end_time`) VALUES
(31, 'SD-1', 'Seed Subsidy Distribution', 'G-5', 'Rizal', '2026-09-11', '2026-09-11', '10:20:24', '16:30:00');

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
(1, 'admin', '$2y$10$mOsQfa7o.YJXIKkzMQa/iOCMpMay0vaZWOmXDE2Z2oT.r7Yl8kRP2', 'admin', '2026-06-17 05:17:00', '2026-09-11 02:09:27', 'pending', 0, NULL),
(10, 'Maki', '$2y$10$sH3pyTnexvxspeuivmd5dujFsFqb6NKASs2QLaUCAlfXlIAVMu2fK', 'farmer', '2026-06-19 06:48:48', '2026-07-15 02:26:14', 'pending', 0, NULL),
(17, 'superadmin', '$2y$10$6LlYegtiDE46XLuhzPnJoe8ii32u7yYbHyf1GSkjY3kK.3eHf8tOK', 'staff', '2026-06-30 01:07:50', '2026-09-10 11:17:20', 'pending', 0, NULL),
(26, 'Jayson', '$2y$10$vqHrBjft1A3bcmaUiWM4TOz23sna4xw1Coh3iVYjXouKWMycF/FpK', 'farmer', '2026-07-15 13:10:21', '2026-09-11 02:22:12', 'pending', 0, NULL),
(29, 'Lxi', '$2y$10$96pJIUC6Z9/AkPKQKHpRi.zys0cVS5T0n0.5MT7eLe3n7TluPMR8K', 'farmer', '2026-07-15 13:47:03', '2026-07-15 13:47:03', 'pending', 0, NULL),
(30, 'Jha', '$2y$10$DaDUC5VjD0bvq7EQ/nVwbuNuabd6I3vPkCgu4gdbxK0KQN/MXSsIi', 'farmer', '2026-07-16 03:01:05', '2026-09-08 12:49:18', 'pending', 0, NULL),
(36, 'Marlon L. Castro', '$2y$10$ZkqyY3JOeD2anhrKF.o78.MugtjnMrrdIZ63LB1aklo0gn7kdi/2C', 'farmer', '2026-07-23 07:33:54', '2026-07-23 07:33:54', 'pending', 0, NULL),
(37, 'Emma T', '$2y$10$CET5WzN0Mfe8lFcKYQIWzOcqzEgi4Ps/bug3h6ef2Juril7r4cW4e', 'farmer', '2026-07-31 07:37:17', '2026-09-06 07:12:41', 'pending', 0, NULL),
(38, 'Marck', '$2y$10$BYWj6REijUN844Wx929RAeSYpHzuUCT9zfwcSvp9CHPho83M1oedW', 'farmer', '2026-08-29 13:12:04', '2026-08-29 13:12:04', 'pending', 0, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
