-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2026 at 02:20 PM
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
  `subsidy_type` varchar(255) NOT NULL,
  `farm_id` int(11) NOT NULL,
  `crop_yield_after` decimal(10,2) NOT NULL,
  `pest_id` int(11) NOT NULL,
  `calamity` varchar(100) NOT NULL,
  `effectiveness_label` varchar(100) NOT NULL,
  `feedback_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `subsidy_type`, `farm_id`, `crop_yield_after`, `pest_id`, `calamity`, `effectiveness_label`, `feedback_id`, `farmer_id`, `created`, `modified`) VALUES
(124, 'Seed Subsidy', 2, 5.90, 5, 'None', 'Effective', 89, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(125, 'Fertilizer Subsidy', 2, 6.00, 8, 'La Niña', 'Not Effective', 90, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(126, 'Seed Subsidy', 1, 2.30, 5, 'Typhoon', 'Not Effective', 91, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(127, 'Seed Subsidy', 1, 5.20, 4, 'El Niño', 'Moderately Effective', 92, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(128, 'Fertilizer Subsidy', 2, 4.45, 7, 'Landslide', 'Moderately Effective', 93, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(129, 'Fertilizer Subsidy', 1, 6.00, 5, 'Typhoon', 'Effective', 94, 153, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `id` int(11) NOT NULL,
  `farmer_no` int(20) NOT NULL,
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
(2, 2334535, 'Marc', 'sardinia', 'Gomez', '2008-07-11', 'Female', 'balintocatoc', '09876543219', 10, '2026-07-14 14:54:19', '2026-08-11 11:57:42'),
(152, 2147483647, 'jane', 'mauro', 'Valdez', '2008-07-15', 'Female', 'balintocatoc', '09876654321', NULL, '2026-07-15 13:09:07', '2026-07-15 13:09:07'),
(153, 4234535, 'Jayson', 'Nudo', 'Rovillos', '2008-08-10', 'Male', 'Dub West Santiago City', '09876543210', 26, '2026-07-15 13:10:21', '2026-08-11 11:58:24'),
(156, 7645244, 'Rona', 'Padilla', 'Mauro', '2026-07-10', 'Male', 'Dub West Santiago City', '09123456789', NULL, '2026-07-15 13:42:48', '2026-07-15 13:42:48'),
(159, 1423345345, 'Rona', 'sardinia', 'Tolentino', '2026-07-01', 'Male', 'Dub West Santiago City', '09876543213', NULL, '2026-07-16 03:16:54', '2026-07-16 03:16:54'),
(162, 24435456, 'Caezar', 'Abalos', 'Baltazar', '2008-07-08', 'Male', 'balintocatoc ,santiago city', '0986543216', 30, '2026-07-18 12:20:37', '2026-07-18 12:20:37'),
(163, 1423345345, 'jane', 'Nudo', 'Mauro', '2005-02-09', 'Male', 'Fortune, Aurora, Alicia, Isabela', '09123456789', 36, '2026-07-23 07:33:54', '2026-07-23 07:33:54'),
(164, 112, 'Emma', 'Tan', 'Yee', '2005-04-11', 'Female', '#367, Purok 6 Dubinan West, Santiago City Isabela', '09125478999', 37, '2026-07-31 07:37:17', '2026-07-31 07:37:17'),
(165, 1423345345, 'Marc', 'Padilla', 'Tolentino', '2005-06-01', 'Male', 'balintocatoc', '09876543221', NULL, '2026-08-06 12:26:24', '2026-08-11 11:58:46');

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
  `crop_yield` decimal(10,2) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `farms`
--

INSERT INTO `farms` (`id`, `farmer_id`, `farm_name`, `farm_size`, `location`, `crop_yield`, `created`, `modified`) VALUES
(1, 153, 'North Farm', 2.50, 'Fortune, Aurora, Alicia, Isabela', 5.50, '2026-08-02 15:59:40', '2026-08-02 15:59:40'),
(2, 153, 'South Farm', 1.75, 'Fortune, Aurora, Alicia, Isabela', 4.50, '2026-08-02 15:59:40', '2026-08-02 15:59:40');

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
(89, 153, 5.0, 'The subsidy was very helpful and improved the farm production.', '2026-08-25 19:37:05'),
(90, 153, 2.0, 'The subsidy was received, but the results were affected by environmental conditions.', '2026-08-25 19:37:05'),
(91, 153, 2.0, 'The subsidy provided limited benefit because of pest and weather problems.', '2026-08-25 19:37:05'),
(92, 153, 3.0, 'The subsidy was helpful, but the results were only moderate.', '2026-08-25 19:37:05'),
(93, 153, 3.0, 'The assistance helped the farm, although some problems affected production.', '2026-08-25 19:37:05'),
(94, 153, 5.0, 'The subsidy significantly helped improve the farm production.', '2026-08-25 19:37:05');

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
(45, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"4234535\",\"first_name\":\"Marc\",\"middle_name\":\"Gomez\",\"last_name\":\"Nudo\",\"gender\":\"Male\",\"contact_no\":\"09123456789\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-02\"},\"user\":{\"username\":\"Marck\",\"password\":\"Marc01@2\",\"confirm_password\":\"Marc01@2\",\"role\":\"farmer\"}}', '2026-07-23 07:35:44', '2026-07-23 07:35:44'),
(46, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'declined', '{\"farmer\":{\"farmer_no\":\"7866868\",\"first_name\":\"jane\",\"middle_name\":\"mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"21474836474\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"jane\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 05:52:27', '2026-07-30 05:53:25'),
(48, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"2147483647\",\"first_name\":\"jane\",\"middle_name\":\"mauro\",\"last_name\":\"Valdez\",\"gender\":\"Female\",\"contact_no\":\"09876654321\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"jane\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 06:05:07', '2026-07-30 06:05:07'),
(49, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"2147483647\",\"first_name\":\"jane\",\"middle_name\":\"Valdez\",\"last_name\":\"mauro\",\"gender\":\"Female\",\"contact_no\":\"09876654321\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-15\"},\"user\":{\"username\":\"janee\",\"password\":\"1234567M@\",\"confirm_password\":\"1234567M@\",\"role\":\"farmer\"}}', '2026-07-30 06:07:41', '2026-07-30 06:07:41'),
(50, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'pending', '{\"farmer\":{\"farmer_no\":\"4234535\",\"first_name\":\"Marc\",\"middle_name\":\"Tolentino\",\"last_name\":\"Padilla\",\"gender\":\"Male\",\"contact_no\":\"09123456789\",\"address\":\"balintocatoc\",\"birthdate\":\"2008-07-29\"},\"user\":{\"username\":\"Marc\",\"password\":\"1234567@M\",\"confirm_password\":\"1234567@M\",\"role\":\"farmer\"}}', '2026-07-31 06:16:27', '2026-07-31 06:16:27'),
(51, NULL, 'New Farmer Registration', 'A new farmer registration requires approval.', 'registration', 'approved', '{\"farmer\":{\"farmer_no\":\"0112\",\"first_name\":\"Emma\",\"middle_name\":\"Yee\",\"last_name\":\"Tan\",\"gender\":\"Female\",\"contact_no\":\"09125478999\",\"address\":\"#367, Purok 6 Dubinan West, Santiago City Isabela\",\"birthdate\":\"2005-04-11\"},\"user\":{\"username\":\"Emma T\",\"password\":\"Emma@*1234\",\"confirm_password\":\"Emma@*1234\",\"role\":\"farmer\"}}', '2026-07-31 07:34:14', '2026-07-31 07:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `pests`
--

CREATE TABLE `pests` (
  `id` int(11) NOT NULL,
  `pest_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created` datetime DEFAULT current_timestamp(),
  `modified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pests`
--

INSERT INTO `pests` (`id`, `pest_name`, `description`, `created`, `modified`) VALUES
(1, 'Rice Bug', 'Feeds on rice grains during the milking stage.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(2, 'Brown Planthopper', 'Sucks sap from rice plants causing hopper burn.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(3, 'Stem Borer', 'Damages rice stems and causes dead hearts.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(4, 'Leaf Folder', 'Folds and feeds on rice leaves.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(5, 'Armyworm', 'Feeds on leaves and stems of crops.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(6, 'Cutworm', 'Cuts seedlings at the base.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(7, 'Corn Earworm', 'Feeds on developing corn ears.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(8, 'Fall Armyworm', 'Major pest of corn and other crops.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(9, 'Aphids', 'Sap-sucking insects affecting many crops.', '2026-08-01 14:52:37', '2026-08-01 14:52:37'),
(10, 'None', 'No pest infestation observed.', '2026-08-01 14:52:37', '2026-08-01 14:52:37');

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
(186, 152, 'Seed Subsidy', 10.00, '2026-08-20 09:00:00', 'Received', 26, '2026-08-20 08:30:00', '2026-08-25 19:21:53', '2026-08-25 19:21:53'),
(187, 2, 'Seed Subsidy', 15.00, '2026-08-21 10:00:00', 'Received', 26, '2026-08-21 09:30:00', '2026-08-25 19:21:53', '2026-08-25 19:21:53'),
(188, 153, 'Seed Subsidy', 20.00, '2026-08-21 09:30:00', 'Re-Scheduled', 26, '2026-08-21 09:30:00', '2026-08-25 19:21:53', '2026-08-25 19:21:53'),
(189, 156, 'Seed Subsidy', 10.00, '2026-08-21 09:30:00', 'Cancelled', 26, '2026-08-21 09:30:00', '2026-08-25 19:21:53', '2026-08-25 19:21:53');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `program_name` varchar(255) NOT NULL,
  `subsidy_type` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `program_name`, `subsidy_type`, `description`, `start_date`, `end_date`, `start_time`, `end_time`) VALUES
(24, 'Subsidy distribution', 'Rice and Corn', 'haloooo', '2026-08-13', '2026-08-13', '06:26:32', '23:26:37'),
(26, 'Subsidy Distribution', 'Seed Subsidy', 'A subsidy distribution', '2026-08-20', '2026-08-20', '07:00:00', '17:00:00'),
(27, 'Subsidy Meeting', 'Fertilizer Subsidy', 'We Have a meeting for the changes of the Subsidy Programs', '2026-08-25', '2026-08-25', '08:00:00', '12:00:00'),
(28, 'Subsidy Distribution', 'Seed Subsidy', 'Ito na ang pinakahihintay nyo', '2026-08-24', '2026-08-24', '09:00:00', '16:00:00'),
(29, 'Subsidy distribution', 'Seed Subsidy', 'Rice seed subsidy', '2026-08-23', '2026-08-23', '08:00:00', '16:00:00');

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
(1, 'admin', '$2y$10$mOsQfa7o.YJXIKkzMQa/iOCMpMay0vaZWOmXDE2Z2oT.r7Yl8kRP2', 'admin', '2026-06-17 05:17:00', '2026-08-29 12:06:19', 'pending', 0, NULL),
(10, 'Maki', '$2y$10$sH3pyTnexvxspeuivmd5dujFsFqb6NKASs2QLaUCAlfXlIAVMu2fK', 'farmer', '2026-06-19 06:48:48', '2026-07-15 02:26:14', 'pending', 0, NULL),
(17, 'superadmin', '$2y$10$6LlYegtiDE46XLuhzPnJoe8ii32u7yYbHyf1GSkjY3kK.3eHf8tOK', 'staff', '2026-06-30 01:07:50', '2026-08-20 08:01:17', 'pending', 0, NULL),
(26, 'Jayson', '$2y$10$vqHrBjft1A3bcmaUiWM4TOz23sna4xw1Coh3iVYjXouKWMycF/FpK', 'farmer', '2026-07-15 13:10:21', '2026-08-22 12:39:15', 'pending', 0, NULL),
(29, 'Lxi', '$2y$10$96pJIUC6Z9/AkPKQKHpRi.zys0cVS5T0n0.5MT7eLe3n7TluPMR8K', 'farmer', '2026-07-15 13:47:03', '2026-07-15 13:47:03', 'pending', 0, NULL),
(30, 'Jha', '$2y$10$DaDUC5VjD0bvq7EQ/nVwbuNuabd6I3vPkCgu4gdbxK0KQN/MXSsIi', 'farmer', '2026-07-16 03:01:05', '2026-08-04 12:56:28', 'pending', 0, NULL),
(36, 'Marlon L. Castro', '$2y$10$ZkqyY3JOeD2anhrKF.o78.MugtjnMrrdIZ63LB1aklo0gn7kdi/2C', 'farmer', '2026-07-23 07:33:54', '2026-07-23 07:33:54', 'pending', 0, NULL),
(37, 'Emma T', '$2y$10$0McY01GqzPr.ZzrV/aphUOWWTU96mJzYLAnV0tf7NH8miQSSdHryu', 'farmer', '2026-07-31 07:37:17', '2026-07-31 07:37:17', 'pending', 0, NULL);

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
  ADD KEY `pest_id` (`pest_id`),
  ADD KEY `farm_id` (`farm_id`);

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
-- Indexes for table `pests`
--
ALTER TABLE `pests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pest_name` (`pest_name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `farmers`
--
ALTER TABLE `farmers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `farms`
--
ALTER TABLE `farms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `pests`
--
ALTER TABLE `pests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`pest_id`) REFERENCES `pests` (`id`);

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
