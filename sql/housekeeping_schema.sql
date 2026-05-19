-- =====================================================
-- COMPLETE HOUSEKEEPING DATABASE
-- Run this in phpMyAdmin
-- =====================================================

DROP DATABASE IF EXISTS hotelbookingsystem;
CREATE DATABASE hotelbookingsystem;
USE hotelbookingsystem;

-- Users table
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `id_number` varchar(50) NOT NULL,
  `role` enum('guest','receptionist','housekeeping','admin') NOT NULL DEFAULT 'guest',
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert housekeeping (password: house123)
INSERT INTO `users` (`name`, `email`, `password_hash`, `phone`, `nationality`, `id_number`, `role`) VALUES
('Robert Wilson', 'housekeeping@hotel.com', 'house123', '+8801711111111', 'American', 'HK001', 'housekeeping');

-- Rooms table
CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_number` varchar(20) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) DEFAULT NULL,
  `floor` int(11) DEFAULT NULL,
  `amidity` text DEFAULT NULL,
  `room_description` text DEFAULT NULL,
  `status` enum('available','occupied','dirty','cleaning','maintenance') DEFAULT 'available',
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_number` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rooms` (`room_number`, `room_type`, `price`, `capacity`, `floor`, `status`) VALUES
('101', 'Standard', 2500, 2, 1, 'available'),
('102', 'Standard', 2500, 2, 1, 'dirty'),
('103', 'Standard', 2500, 2, 1, 'occupied'),
('201', 'Deluxe', 4000, 3, 2, 'available'),
('202', 'Deluxe', 4000, 3, 2, 'dirty'),
('203', 'Deluxe', 4000, 3, 2, 'maintenance'),
('301', 'Suite', 6500, 4, 3, 'available'),
('302', 'Suite', 6500, 4, 3, 'cleaning'),
('303', 'Suite', 6500, 4, 3, 'occupied');

-- Bookings table
CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL AUTO_INCREMENT,
  `guest_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `num_guests` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Housekeeping tasks table
CREATE TABLE `housekeeping_tasks` (
  `task_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `task_type` enum('cleaning','deep_cleaning','inspection','maintenance') NOT NULL,
  `priority` enum('normal','urgent','emergency') DEFAULT 'normal',
  `status` enum('pending','in_progress','completed') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `scheduled_date` date NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`task_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `housekeeping_tasks` (`room_id`, `task_type`, `priority`, `status`, `scheduled_date`) VALUES
(102, 'cleaning', 'normal', 'pending', CURDATE()),
(202, 'cleaning', 'urgent', 'pending', CURDATE()),
(302, 'cleaning', 'normal', 'in_progress', CURDATE());

-- Maintenance reports table
CREATE TABLE `maintenance_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) NOT NULL,
  `reported_by` int(11) NOT NULL,
  `description` text NOT NULL,
  `severity` enum('low','medium','high','critical') DEFAULT 'medium',
  `status` enum('open','in_progress','resolved') DEFAULT 'open',
  `reported_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` datetime DEFAULT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `maintenance_reports` (`room_id`, `reported_by`, `description`, `severity`) VALUES
(203, 1, 'AC not working properly', 'high');

SELECT '✅ DATABASE READY! Login: housekeeping@hotel.com / house123' AS Status;