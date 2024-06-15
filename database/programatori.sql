-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 22, 2024 at 07:22 PM
-- Server version: 8.0.36-0ubuntu0.20.04.1
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
  `worker_id` int NOT NULL,
  `ip_address` varchar(42) COLLATE utf8mb4_general_ci NOT NULL,
  `device_type` set('computer','tablet','phone') COLLATE utf8mb4_general_ci NOT NULL,
  `proxy` tinyint NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `mobile` tinyint NOT NULL,
  `date_accessed` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `picture_extension` set('jpg','png','jpeg','webp') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `picture_types`
--

CREATE TABLE `picture_types` (
  `picture_type_id` int NOT NULL,
  `picture_type_name` varchar(12) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `phone_number` int DEFAULT NULL,
  `worker_email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `worker_password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `picture_id` int DEFAULT NULL,
  `company_id` int NOT NULL,
  `role` set('worker','employer') COLLATE utf8mb4_general_ci NOT NULL,
  `registration_token` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `registration_expires` datetime DEFAULT NULL,
  `forgoten_password_token` varchar(40) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `forgoten_password_expires` datetime DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `isActive` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`worker_id`, `worker_fname`, `worker_lname`, `phone_number`, `worker_email`, `worker_password`, `picture_id`, `company_id`, `role`, `registration_token`, `registration_expires`, `forgoten_password_token`, `forgoten_password_expires`, `date_created`, `isActive`) VALUES
(2, 'Stefan', 'Buljic', 616458781, 'buljic77@gmail.com', '$2a$12$pzIOAV7O08Xf3cYOTRBLze60XUDGqwgmcx9FOjTy.FfFul4xizW7a', NULL, 1, 'worker', NULL, NULL, NULL, NULL, '2024-03-10 10:23:30', 1);

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
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `picture_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `picture_types`
--
ALTER TABLE `picture_types`
  MODIFY `picture_type_id` int NOT NULL AUTO_INCREMENT;

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
  MODIFY `worker_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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