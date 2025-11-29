-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 04:13 AM
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
-- Database: `capstone1`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `nurse_id` int(11) DEFAULT NULL,
  `appointment_time` datetime NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('Pending','Confirmed','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_emergency` tinyint(1) NOT NULL DEFAULT 0,
  `is_seen` tinyint(1) DEFAULT 0,
  `reschedule_status` enum('Pending','Accepted','Declined') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `student_id`, `nurse_id`, `appointment_time`, `reason`, `status`, `created_at`, `updated_at`, `is_emergency`, `is_seen`, `reschedule_status`) VALUES
(98, 10, NULL, '2025-09-28 14:26:00', 'kit a ko', 'Confirmed', '2025-09-28 14:26:46', '2025-09-28 14:26:57', 0, 0, NULL),
(99, 10, NULL, '2025-09-24 17:59:00', 'sakit utok', 'Confirmed', '2025-09-28 14:59:52', '2025-09-28 15:03:46', 0, 0, NULL),
(100, 10, NULL, '2025-09-09 15:07:00', 'tummy sakit', 'Confirmed', '2025-09-28 15:05:34', '2025-09-28 15:09:52', 0, 0, NULL),
(101, 10, NULL, '2025-09-18 15:09:00', 'sakit ngipon', 'Confirmed', '2025-09-28 15:09:07', '2025-09-28 15:09:59', 0, 0, NULL),
(102, 10, NULL, '2025-09-29 15:28:00', 'saddf', 'Confirmed', '2025-09-28 15:28:27', '2025-09-28 15:28:41', 0, 0, NULL),
(103, 10, NULL, '2025-09-29 15:28:00', 'sadasdasdasd', 'Confirmed', '2025-09-28 15:36:28', '2025-09-28 15:37:31', 0, 0, NULL),
(104, 10, NULL, '2025-09-09 15:39:00', 'sadasda', '', '2025-09-28 15:39:34', '2025-09-28 15:39:46', 0, 0, NULL),
(105, 10, NULL, '2025-09-29 15:40:00', 'tummy ache', '', '2025-09-28 15:40:19', '2025-09-28 15:40:50', 0, 0, NULL),
(106, 10, NULL, '2025-10-01 18:41:00', 'aray', '', '2025-09-28 15:41:29', '2025-09-28 15:41:57', 0, 0, NULL),
(107, 10, NULL, '2025-09-05 16:02:00', 'dsadasd', '', '2025-09-28 16:02:40', '2025-09-28 16:03:02', 0, 0, NULL),
(108, 10, NULL, '2025-09-30 16:02:00', 'dsadawqew', 'Completed', '2025-09-28 16:13:12', '2025-11-18 09:35:38', 0, 0, NULL),
(109, 10, NULL, '2025-09-28 16:15:00', 'adddsss', '', '2025-09-28 16:15:41', '2025-09-28 16:15:56', 0, 0, NULL),
(110, 10, NULL, '2025-09-28 22:43:00', 'cakit', '', '2025-09-28 19:25:15', '2025-09-28 19:43:10', 0, 0, NULL),
(111, 10, NULL, '2025-09-28 13:43:00', 'mememememem', '', '2025-09-28 19:44:00', '2025-09-28 19:44:13', 0, 0, NULL),
(112, 10, NULL, '2025-09-28 14:43:00', 'mmnm', '', '2025-09-28 19:49:42', '2025-09-28 19:49:58', 0, 0, NULL),
(113, 10, NULL, '2025-09-28 22:04:00', 'mmmmmm', '', '2025-09-28 20:04:31', '2025-09-28 20:04:44', 0, 0, NULL),
(114, 10, NULL, '2025-09-28 15:04:00', 'arayy koooooo', 'Cancelled', '2025-09-28 20:09:26', '2025-09-28 20:50:38', 0, 0, 'Pending'),
(115, 10, NULL, '2025-09-28 17:04:00', 'ako si ako', '', '2025-09-28 20:50:28', '2025-09-29 21:19:41', 0, 0, 'Accepted'),
(116, 10, NULL, '2025-09-28 18:04:00', 'memkk', '', '2025-09-28 21:02:26', '2025-09-28 21:02:43', 0, 0, NULL),
(117, 10, NULL, '2025-09-28 22:08:00', 'akoooosssss', 'Confirmed', '2025-09-28 22:08:42', '2025-09-28 22:09:01', 0, 0, NULL),
(118, 10, NULL, '2025-09-29 20:27:00', 'sfsf', '', '2025-09-28 22:41:20', '2025-09-29 22:52:12', 0, 0, NULL),
(119, 10, NULL, '2025-09-29 23:25:00', 'ako si ch', '', '2025-09-29 00:02:44', '2025-09-29 23:35:37', 0, 0, 'Accepted'),
(120, 10, NULL, '2025-09-29 20:24:00', 'KOJOSD', '', '2025-09-29 20:25:01', '2025-09-29 22:52:04', 0, 0, NULL),
(121, 10, NULL, '2025-09-29 20:30:00', 'kkkkn', 'Confirmed', '2025-09-29 20:31:03', '2025-09-29 22:55:28', 0, 0, NULL),
(122, 10, NULL, '2025-09-29 21:24:00', 'mmmkkk', 'Completed', '2025-09-29 21:24:51', '2025-11-19 13:49:28', 0, 0, NULL),
(123, 10, NULL, '2025-09-29 21:38:00', 'charlene', 'Confirmed', '2025-09-29 21:38:59', '2025-09-29 23:09:45', 0, 0, NULL),
(124, 10, NULL, '2025-09-29 21:57:00', 'janpaul', 'Confirmed', '2025-09-29 21:57:31', '2025-09-29 23:14:24', 0, 0, NULL),
(125, 10, NULL, '2025-09-29 22:04:00', 'ako si cha', 'Confirmed', '2025-09-29 22:04:16', '2025-09-29 23:15:18', 0, 0, NULL),
(126, 10, NULL, '2025-09-29 13:18:00', 'janpaul gwa', '', '2025-09-29 22:14:23', '2025-09-29 23:18:31', 0, 0, NULL),
(127, 10, NULL, '2025-09-29 22:21:00', 'jonnel inoc', '', '2025-09-29 22:21:27', '2025-09-29 22:46:21', 0, 0, NULL),
(128, 10, NULL, '2025-09-29 22:28:00', 'charlene inc', '', '2025-09-29 22:28:48', '2025-09-29 22:46:18', 0, 0, NULL),
(129, 10, NULL, '2025-09-29 23:25:00', 'dodong', '', '2025-09-29 23:15:43', '2025-09-29 23:26:05', 0, 0, 'Accepted'),
(130, 10, NULL, '2025-09-29 23:25:00', 'gwapo ko', '', '2025-09-29 23:41:18', '2025-09-29 23:44:19', 0, 0, 'Accepted'),
(131, 10, NULL, '2025-09-29 23:25:00', 'gwapo ko', '', '2025-09-29 23:53:13', '2025-09-29 23:53:59', 0, 0, NULL),
(132, 10, NULL, '2025-09-29 23:25:00', 'hai gwapo', '', '2025-09-30 00:12:30', '2025-09-30 00:13:17', 0, 0, NULL),
(133, 10, NULL, '2025-09-30 01:19:00', 'mommy', '', '2025-09-30 00:19:46', '2025-09-30 00:20:03', 0, 0, NULL),
(134, 10, NULL, '2025-09-30 01:23:00', 'dady', '', '2025-09-30 00:23:25', '2025-09-30 00:23:37', 0, 0, NULL),
(135, 10, NULL, '2025-09-30 00:30:00', 'meme', '', '2025-09-30 00:28:24', '2025-09-30 00:29:21', 0, 0, NULL),
(136, 10, NULL, '2025-09-19 00:39:00', 'kapoy na', '', '2025-09-30 00:38:33', '2025-09-30 00:39:07', 0, 0, NULL),
(137, 10, NULL, '2025-09-19 00:39:00', 'hohahay', '', '2025-09-30 00:38:53', '2025-09-30 00:40:29', 0, 0, NULL),
(138, 10, NULL, '2025-09-30 02:43:00', 'tabangg', '', '2025-09-30 00:43:22', '2025-09-30 00:43:42', 0, 0, NULL),
(139, 10, NULL, '2025-09-30 02:19:00', 'hahayahy', '', '2025-09-30 01:18:52', '2025-09-30 01:19:04', 0, 0, NULL),
(140, 10, NULL, '2025-09-30 02:40:00', 'run na ywaw', '', '2025-09-30 01:39:59', '2025-09-30 01:40:09', 0, 0, NULL),
(141, 10, NULL, '2025-09-30 02:48:00', 'tabang pa', '', '2025-09-30 01:48:03', '2025-09-30 01:48:18', 0, 0, NULL),
(142, 10, NULL, '2025-09-30 02:53:00', 'wqewqew', '', '2025-09-30 01:52:47', '2025-09-30 01:53:21', 0, 0, NULL),
(143, 10, NULL, '2025-09-06 01:55:00', 'ddd', '', '2025-09-30 01:53:05', '2025-09-30 01:55:53', 0, 0, NULL),
(144, 10, NULL, '2025-09-30 05:00:00', 'tabangggggg', '', '2025-09-30 02:00:06', '2025-09-30 02:00:17', 0, 0, NULL),
(145, 10, NULL, '2025-09-30 04:03:00', 'tabanggg paaa', '', '2025-09-30 02:03:08', '2025-09-30 02:03:18', 0, 0, NULL),
(146, 10, NULL, '2025-09-06 02:05:00', 'jonnel gwapo 111', '', '2025-09-30 02:05:30', '2025-09-30 02:05:40', 0, 0, NULL),
(147, 10, NULL, '2025-09-30 02:10:00', 'kappoy naa kaayu', '', '2025-09-30 02:08:28', '2025-09-30 02:08:54', 0, 0, NULL),
(148, 10, NULL, '2025-09-06 02:12:00', 'kayapa', '', '2025-09-30 02:08:43', '2025-09-30 02:12:13', 0, 0, NULL),
(149, 10, NULL, '2025-09-30 02:13:00', 'i love you', 'Confirmed', '2025-09-30 02:13:54', '2025-09-30 02:14:22', 0, 0, NULL),
(150, 10, NULL, '2025-09-05 02:16:00', 'love ou more', '', '2025-09-30 02:14:12', '2025-09-30 02:16:21', 0, 0, NULL),
(151, 10, NULL, '2025-09-05 02:24:00', 'gawwwwwddd', '', '2025-09-30 02:24:53', '2025-09-30 02:53:25', 0, 0, NULL),
(152, 10, NULL, '2025-09-30 03:15:00', 'manaa kkooo', '', '2025-09-30 03:15:17', '2025-09-30 03:15:25', 0, 0, NULL),
(153, 10, NULL, '2025-09-30 03:21:00', 'pancit canton', '', '2025-09-30 03:21:34', '2025-09-30 03:21:41', 0, 0, NULL),
(154, 10, NULL, '2025-09-30 03:31:00', 'me ako', '', '2025-09-30 03:32:02', '2025-09-30 03:32:28', 0, 0, NULL),
(155, 10, NULL, '2025-09-05 03:32:00', 'kiss ko be', '', '2025-09-30 03:32:20', '2025-09-30 03:32:37', 0, 0, NULL),
(156, 10, NULL, '2025-09-30 03:46:00', 'sleep nata', '', '2025-09-30 03:46:57', '2025-09-30 03:47:05', 0, 0, NULL),
(157, 10, NULL, '2025-09-30 03:51:00', 'amen tabang lord', 'Confirmed', '2025-09-30 03:52:00', '2025-09-30 03:52:31', 0, 0, NULL),
(158, 10, NULL, '2025-09-30 03:55:00', 'bagay kung errpr', 'Confirmed', '2025-09-30 03:55:15', '2025-09-30 03:55:23', 0, 0, NULL),
(159, 10, NULL, '2025-09-30 04:00:00', 'tabang lord ehehehe', 'Confirmed', '2025-09-30 04:00:22', '2025-09-30 04:00:39', 0, 0, NULL),
(160, 10, NULL, '2025-09-30 04:02:00', 'mana na unta ni', 'Confirmed', '2025-09-30 04:02:13', '2025-09-30 04:02:39', 0, 0, NULL),
(161, 10, NULL, '2025-09-04 04:03:00', 'happy nako kung mana ni', 'Confirmed', '2025-09-30 04:02:31', '2025-09-30 04:03:43', 0, 0, NULL),
(162, 10, NULL, '2025-09-06 04:25:00', 'goodniight', 'Confirmed', '2025-09-30 04:24:11', '2025-09-30 04:25:34', 0, 0, NULL),
(163, 10, NULL, '2025-09-04 04:50:00', 'thank you loard', 'Confirmed', '2025-09-30 04:48:46', '2025-09-30 04:50:31', 0, 0, NULL),
(164, 10, NULL, '2025-09-30 06:09:00', 'salamat lorf', 'Confirmed', '2025-09-30 05:09:24', '2025-09-30 05:09:51', 0, 0, NULL),
(165, 10, NULL, '2025-09-30 06:09:00', 'gana na please', 'Confirmed', '2025-09-30 05:12:14', '2025-09-30 05:12:55', 0, 0, NULL),
(166, 10, NULL, '2025-09-30 07:15:00', 'please lang malluy ka', 'Completed', '2025-09-30 05:12:31', '2025-11-19 15:09:46', 0, 0, NULL),
(167, 10, NULL, '2025-09-06 07:15:00', 'hello love goodbye', 'Confirmed', '2025-09-30 05:32:54', '2025-09-30 05:33:39', 0, 0, NULL),
(168, 10, NULL, '2025-09-18 05:34:00', 'aguyy', 'Confirmed', '2025-09-30 05:34:29', '2025-09-30 05:35:07', 0, 0, NULL),
(169, 10, NULL, '2025-10-18 13:51:00', 'friedn', 'Completed', '2025-10-01 13:51:19', '2025-11-13 21:10:12', 0, 0, NULL),
(170, 10, NULL, '2025-11-01 13:58:00', 'hwqhqhdiwhmeeee', 'Completed', '2025-10-01 13:58:49', '2025-10-19 14:57:19', 0, 0, NULL),
(171, 10, NULL, '2025-10-01 14:18:00', '122233', 'Completed', '2025-10-01 14:18:25', '2025-11-18 09:35:33', 0, 0, NULL),
(172, 10, NULL, '2025-10-01 14:21:00', '1122', 'Completed', '2025-10-01 14:21:06', '2025-11-18 09:32:38', 0, 0, NULL),
(173, 10, NULL, '2025-10-01 14:31:00', 'dukaa', 'Completed', '2025-10-01 14:31:33', '2025-11-18 09:31:48', 0, 0, NULL),
(174, 10, NULL, '2025-10-01 16:34:00', 'sleepwell', 'Completed', '2025-10-01 14:34:27', '2025-10-20 21:36:51', 0, 0, NULL),
(175, 10, NULL, '2025-10-02 14:36:00', 'GAYYYY', '', '2025-10-01 14:35:24', '2025-11-18 09:24:03', 0, 0, NULL),
(176, 10, NULL, '2025-10-01 14:35:00', 'meee11', 'Completed', '2025-10-01 14:35:53', '2025-11-18 09:26:40', 0, 0, NULL),
(177, 10, NULL, '2025-10-24 14:37:00', 'salaamt', 'Completed', '2025-10-01 14:37:24', '2025-10-19 14:57:35', 0, 0, NULL),
(178, 10, NULL, '2025-10-01 14:40:00', 'mana jusdttt', 'Completed', '2025-10-01 14:37:39', '2025-11-18 09:26:15', 0, 0, NULL),
(179, 10, NULL, '2025-10-01 14:41:00', 'agayyy', 'Completed', '2025-10-01 14:39:52', '2025-11-18 09:26:05', 0, 0, NULL),
(180, 10, NULL, '2025-10-03 13:31:00', 'aray123123', '', '2025-10-03 13:31:12', '2025-11-18 09:19:54', 0, 0, NULL),
(181, 10, NULL, '2025-10-03 15:39:00', '123123', 'Completed', '2025-10-03 13:39:35', '2025-11-18 09:01:11', 0, 0, NULL),
(182, 10, NULL, '2025-10-03 13:46:00', '1234', '', '2025-10-03 13:46:13', '2025-11-18 09:10:13', 0, 0, NULL),
(183, 10, NULL, '2025-10-03 17:48:00', '12345', 'Completed', '2025-10-03 13:48:16', '2025-11-18 08:49:40', 0, 0, NULL),
(184, 10, NULL, '2025-10-03 14:51:00', '12345', '', '2025-10-03 14:51:07', '2025-10-03 14:51:38', 0, 0, NULL),
(185, 10, NULL, '2025-10-04 13:19:00', 'MANA', 'Completed', '2025-10-04 22:19:08', '2025-11-18 08:49:16', 0, 0, NULL),
(186, 10, NULL, '2025-10-05 14:09:00', 'arayy koooooo11222', '', '2025-10-05 14:09:36', '2025-10-05 14:09:45', 0, 0, NULL),
(187, 10, NULL, '2025-10-11 09:27:00', 'sakit ngipon', 'Completed', '2025-10-11 09:27:41', '2025-11-18 08:44:10', 0, 0, NULL),
(188, 10, NULL, '2025-10-14 17:17:00', 'bleed', 'Completed', '2025-10-14 17:18:03', '2025-11-18 08:43:30', 0, 0, NULL),
(189, 10, NULL, '2025-10-14 17:38:00', 'bleeding', 'Completed', '2025-10-14 17:39:03', '2025-11-13 21:28:20', 0, 0, NULL),
(190, 10, NULL, '2025-10-14 17:49:00', 'alarm', 'Completed', '2025-10-14 17:47:18', '2025-11-13 21:19:24', 0, 0, NULL),
(191, 10, NULL, '2025-10-22 15:24:00', 'my head hurt', 'Completed', '2025-10-22 14:24:14', '2025-10-22 14:25:15', 0, 0, NULL),
(192, 10, NULL, '2025-10-22 18:24:00', 'hurt143', 'Completed', '2025-10-22 14:25:36', '2025-11-13 21:01:58', 0, 0, NULL),
(193, 10, NULL, '2025-10-22 17:41:00', 'sakit ulo', 'Completed', '2025-10-22 14:40:47', '2025-10-22 15:22:45', 0, 0, NULL),
(194, 21, NULL, '2025-10-22 17:04:00', 'ngiponn', 'Completed', '2025-10-22 15:04:27', '2025-10-22 15:22:26', 0, 0, NULL),
(195, 21, NULL, '2025-10-22 15:15:00', 'head spinnng', 'Completed', '2025-10-22 15:15:59', '2025-10-22 15:22:28', 0, 0, NULL),
(196, 10, NULL, '2025-10-22 15:36:00', 'pasar', 'Completed', '2025-10-22 15:34:37', '2025-11-13 21:07:07', 0, 0, NULL),
(197, 21, NULL, '2025-10-22 21:35:00', 'yehey', 'Completed', '2025-10-22 15:35:40', '2025-10-27 22:09:47', 0, 0, NULL),
(198, 10, NULL, '2025-10-24 16:35:00', 'Toothache', '', '2025-10-23 13:35:15', '2025-10-23 13:36:10', 0, 0, NULL),
(199, 10, NULL, '2025-10-23 16:45:00', 'sakit ngipon2', 'Completed', '2025-10-23 13:45:13', '2025-10-27 20:47:51', 0, 0, NULL),
(200, 10, NULL, '2025-10-23 14:01:00', 'meeting', 'Completed', '2025-10-23 14:01:33', '2025-10-27 22:02:38', 0, 0, NULL),
(201, 10, NULL, '2025-10-24 00:02:00', 'weqwe', 'Completed', '2025-10-23 23:58:27', '2025-10-27 21:00:42', 0, 0, NULL),
(202, 10, NULL, '2025-10-23 23:59:00', '123123123', 'Completed', '2025-10-23 23:59:10', '2025-10-24 00:05:32', 0, 0, NULL),
(203, 10, NULL, '2025-10-24 00:20:00', 'tummy sakit', 'Completed', '2025-10-24 00:21:06', '2025-10-27 21:51:06', 0, 0, NULL),
(204, 10, NULL, '2025-10-26 19:58:00', 'HEADache', 'Completed', '2025-10-26 19:56:56', '2025-10-27 20:44:31', 0, 0, NULL),
(205, 10, NULL, '2025-10-26 13:03:00', 'headache', 'Completed', '2025-10-27 23:30:01', '2025-10-27 23:30:01', 0, 0, NULL),
(206, 10, NULL, '2025-10-26 13:03:00', 'headache', 'Completed', '2025-10-27 23:31:20', '2025-10-27 23:31:20', 0, 0, NULL),
(207, 10, NULL, '2025-10-26 13:03:00', 'headache', 'Completed', '2025-10-27 23:31:28', '2025-10-27 23:31:28', 0, 0, NULL),
(208, 10, NULL, '2025-10-26 13:03:00', 'headache', 'Completed', '2025-10-27 23:31:30', '2025-10-27 23:31:30', 0, 0, NULL),
(209, 21, NULL, '2025-10-27 14:40:00', 'arayy koooooo', 'Completed', '2025-10-27 23:35:57', '2025-10-27 23:35:57', 0, 0, NULL),
(210, 10, NULL, '2025-10-27 15:36:00', 'done', 'Completed', '2025-10-27 23:36:56', '2025-10-27 23:36:56', 0, 0, NULL),
(211, 10, NULL, '2025-11-18 08:49:00', 'adadasd', 'Completed', '2025-11-18 08:49:48', '2025-11-18 09:00:04', 0, 0, NULL),
(212, 10, NULL, '2025-11-18 09:00:00', 'ewrwer', 'Completed', '2025-11-18 09:00:11', '2025-11-18 09:01:06', 0, 0, NULL),
(213, 10, NULL, '2025-11-18 09:01:00', 'dasdsddd', '', '2025-11-18 09:01:19', '2025-11-18 09:05:52', 0, 0, NULL),
(214, 10, NULL, '2025-11-18 09:09:00', 'for medication', 'Completed', '2025-11-18 09:09:49', '2025-11-18 09:10:25', 0, 0, NULL),
(215, 10, NULL, '2025-11-18 09:09:00', 'for medicationw', '', '2025-11-18 09:10:31', '2025-11-18 09:19:54', 0, 0, NULL),
(216, 10, NULL, '2025-11-18 03:20:00', 'dasdass', 'Completed', '2025-11-18 09:20:03', '2025-11-18 09:20:45', 0, 0, NULL),
(217, 10, NULL, '2025-11-08 09:22:00', 'adsad', 'Completed', '2025-11-18 09:22:09', '2025-11-18 09:24:18', 0, 0, NULL),
(218, 10, NULL, '2025-11-18 09:24:00', 'wdwww', 'Completed', '2025-11-18 09:24:25', '2025-11-18 09:26:11', 0, 0, NULL),
(219, 10, NULL, '2025-11-20 13:49:00', 'Nayayay', '', '2025-11-19 13:49:47', '2025-11-19 13:57:18', 0, 0, NULL),
(220, 10, NULL, '2025-11-20 15:09:00', 'Bayad utang', 'Completed', '2025-11-19 15:10:01', '2025-11-19 15:11:50', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_logs`
--

CREATE TABLE `appointment_logs` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `student_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_logs`
--

INSERT INTO `appointment_logs` (`id`, `appointment_id`, `student_name`, `date`, `time`, `reason`, `status`, `logged_at`) VALUES
(1, 170, 'charlene Sinagpulo', '2025-11-01', '13:58:00', 'hwqhqhdiwhmeeee', 'Completed', '2025-10-19 06:57:19'),
(2, 177, 'charlene Sinagpulo', '2025-10-24', '14:37:00', 'salaamt', 'Completed', '2025-10-19 06:57:35'),
(3, 170, 'charlene Sinagpulo', '2025-11-01', '13:58:00', 'hwqhqhdiwhmeeee', 'Completed', '2025-10-19 07:08:07'),
(4, 174, 'charlene Sinagpulo', '2025-10-01', '16:34:00', 'sleepwell', 'Completed', '2025-10-20 13:36:51'),
(5, 191, 'charlene Sinagpulo', '2025-10-22', '15:24:00', 'my head hurt', 'Completed', '2025-10-22 06:25:15'),
(6, 194, 'Jonnel Inoc', '2025-10-22', '17:04:00', 'ngiponn', 'Completed', '2025-10-22 07:22:26'),
(7, 195, 'Jonnel Inoc', '2025-10-22', '15:15:00', 'head spinnng', 'Completed', '2025-10-22 07:22:28'),
(8, 193, 'charlene Sinagpulo', '2025-10-22', '17:41:00', 'sakit ulo', 'Completed', '2025-10-22 07:22:45'),
(9, 202, 'charlene Sinagpulo', '2025-10-23', '23:59:00', '123123123', 'Completed', '2025-10-23 16:05:32'),
(10, 204, 'charlene Sinagpulo', '2025-10-26', '19:58:00', 'HEADache', 'Completed', '2025-10-27 12:44:31'),
(11, 199, 'charlene Sinagpulo', '2025-10-23', '16:45:00', 'sakit ngipon2', 'Completed', '2025-10-27 12:47:51'),
(12, 201, 'charlene Sinagpulo', '2025-10-24', '00:02:00', 'weqwe', 'Completed', '2025-10-27 13:00:42'),
(13, 203, 'charlene Sinagpulo', '2025-10-24', '00:20:00', 'tummy sakit', 'Completed', '2025-10-27 13:51:06'),
(14, 200, 'charlene Sinagpulo', '2025-10-23', '14:01:00', 'meeting', 'Completed', '2025-10-27 14:02:38'),
(15, 197, 'Jonnel Inoc', '2025-10-22', '21:35:00', 'yehey', 'Completed', '2025-10-27 14:09:47'),
(16, 206, 'charlene Sinagpulo', '2025-10-26', '13:03:00', 'headache', 'Completed', '2025-10-27 15:31:20'),
(17, 207, 'charlene Sinagpulo', '2025-10-26', '13:03:00', 'headache', 'Completed', '2025-10-27 15:31:28'),
(18, 208, 'charlene Sinagpulo', '2025-10-26', '13:03:00', 'headache', 'Completed', '2025-10-27 15:31:30'),
(19, 209, 'Jonnel Inoc', '2025-10-27', '14:40:00', 'arayy koooooo', 'Completed', '2025-10-27 15:35:57'),
(20, 210, 'charlene Sinagpulo', '2025-10-27', '15:36:00', 'done', 'Completed', '2025-10-27 15:36:56'),
(21, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:01:58'),
(22, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:01:59'),
(23, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:01:59'),
(24, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:01:59'),
(25, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:01:59'),
(26, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:01'),
(27, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:01'),
(28, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:01'),
(29, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:01'),
(30, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(31, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(32, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(33, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(34, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(35, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:02'),
(36, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:03'),
(37, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:03'),
(38, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:03'),
(39, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:03'),
(40, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:04'),
(41, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:04'),
(42, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:06'),
(43, 192, 'charlene Sinagpulo', '2025-10-22', '18:24:00', 'hurt143', 'Completed', '2025-11-13 13:02:06'),
(44, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:07'),
(45, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:07'),
(46, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:08'),
(47, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:08'),
(48, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:08'),
(49, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:08'),
(50, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:09'),
(51, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:09'),
(52, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:10'),
(53, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:11'),
(54, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:11'),
(55, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:11'),
(56, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:11'),
(57, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:11'),
(58, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:24'),
(59, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:24'),
(60, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:07:24'),
(61, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:08:03'),
(62, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:08:03'),
(63, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:08:04'),
(64, 196, 'charlene Sinagpulo', '2025-10-22', '15:36:00', 'pasar', 'Completed', '2025-11-13 13:08:04'),
(65, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:12'),
(66, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:12'),
(67, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:13'),
(68, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:13'),
(69, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:14'),
(70, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:14'),
(71, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:14'),
(72, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:14'),
(73, 169, 'charlene Sinagpulo', '2025-10-18', '13:51:00', 'friedn', 'Completed', '2025-11-13 13:10:14'),
(74, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:24'),
(75, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:25'),
(76, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:26'),
(77, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:26'),
(78, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:26'),
(79, 190, 'charlene Sinagpulo', '2025-10-14', '17:49:00', 'alarm', 'Completed', '2025-11-13 13:19:32'),
(80, 189, 'charlene Sinagpulo', '2025-10-14', '17:38:00', 'bleeding', 'Completed', '2025-11-13 13:28:20'),
(81, 189, 'charlene Sinagpulo', '2025-10-14', '17:38:00', 'bleeding', 'Completed', '2025-11-13 13:28:21'),
(82, 189, 'charlene Sinagpulo', '2025-10-14', '17:38:00', 'bleeding', 'Completed', '2025-11-13 13:28:21'),
(83, 189, 'charlene Sinagpulo', '2025-10-14', '17:38:00', 'bleeding', 'Completed', '2025-11-13 13:28:21'),
(84, 189, 'charlene Sinagpulo', '2025-10-14', '17:38:00', 'bleeding', 'Completed', '2025-11-13 13:28:26'),
(85, 188, 'charlene Sinagpulo', '2025-10-14', '17:17:00', 'bleed', 'Completed', '2025-11-18 00:43:30'),
(86, 187, 'charlene Sinagpulo', '2025-10-11', '09:27:00', 'sakit ngipon', 'Completed', '2025-11-18 00:44:10'),
(87, 185, 'charlene Sinagpulo', '2025-10-04', '13:19:00', 'MANA', 'Completed', '2025-11-18 00:49:16'),
(88, 183, 'charlene Sinagpulo', '2025-10-03', '17:48:00', '12345', 'Completed', '2025-11-18 00:49:40'),
(89, 211, 'charlene Sinagpulo', '2025-11-18', '08:49:00', 'adadasd', 'Completed', '2025-11-18 01:00:04'),
(90, 212, 'charlene Sinagpulo', '2025-11-18', '09:00:00', 'ewrwer', 'Completed', '2025-11-18 01:01:06'),
(91, 181, 'charlene Sinagpulo', '2025-10-03', '15:39:00', '123123', 'Completed', '2025-11-18 01:01:11'),
(92, 182, 'charlene Sinagpulo', '2025-10-03', '13:46:00', '1234', 'Completed', '2025-11-18 01:09:40'),
(93, 214, 'charlene Sinagpulo', '2025-11-18', '09:09:00', 'for medication', 'Completed', '2025-11-18 01:10:25'),
(94, 180, 'charlene Sinagpulo', '2025-10-03', '13:31:00', 'aray123123', 'Completed', '2025-11-18 01:10:28'),
(95, 175, 'charlene Sinagpulo', '2025-10-02', '14:36:00', 'GAYYYY', 'Completed', '2025-11-18 01:19:57'),
(96, 216, 'charlene Sinagpulo', '2025-11-18', '03:20:00', 'dasdass', 'Completed', '2025-11-18 01:20:45'),
(97, 175, 'charlene Sinagpulo', '2025-10-02', '14:36:00', 'GAYYYY', 'Completed', '2025-11-18 01:22:01'),
(98, 217, 'charlene Sinagpulo', '2025-11-08', '09:22:00', 'adsad', 'Completed', '2025-11-18 01:24:18'),
(99, 179, 'charlene Sinagpulo', '2025-10-01', '14:41:00', 'agayyy', 'Completed', '2025-11-18 01:26:05'),
(100, 218, 'charlene Sinagpulo', '2025-11-18', '09:24:00', 'wdwww', 'Completed', '2025-11-18 01:26:11'),
(101, 178, 'charlene Sinagpulo', '2025-10-01', '14:40:00', 'mana jusdttt', 'Completed', '2025-11-18 01:26:15'),
(102, 176, 'charlene Sinagpulo', '2025-10-01', '14:35:00', 'meee11', 'Completed', '2025-11-18 01:26:40'),
(103, 173, 'charlene Sinagpulo', '2025-10-01', '14:31:00', 'dukaa', 'Completed', '2025-11-18 01:31:48'),
(104, 172, 'charlene Sinagpulo', '2025-10-01', '14:21:00', '1122', 'Completed', '2025-11-18 01:32:38'),
(105, 171, 'charlene Sinagpulo', '2025-10-01', '14:18:00', '122233', 'Completed', '2025-11-18 01:35:33'),
(106, 108, 'charlene Sinagpulo', '2025-09-30', '16:02:00', 'dsadawqew', 'Completed', '2025-11-18 01:35:38'),
(107, 122, 'charlene Sinagpulo', '2025-09-29', '21:24:00', 'mmmkkk', 'Completed', '2025-11-19 05:49:28'),
(108, 166, 'charlene Sinagpulo', '2025-09-30', '07:15:00', 'please lang malluy ka', 'Completed', '2025-11-19 07:09:46'),
(109, 220, 'charlene Sinagpulo', '2025-11-20', '15:09:00', 'Bayad utang', 'Completed', '2025-11-19 07:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_medications`
--

CREATE TABLE `appointment_medications` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `action_taken` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_medications`
--

INSERT INTO `appointment_medications` (`id`, `appointment_id`, `medicine_name`, `dosage`, `quantity`, `action_taken`, `created_at`) VALUES
(1, 203, 'parokcetamol', '35grm', 1, 'giving this medicine for his head', '2025-10-27 12:55:29'),
(2, 201, 'parokcetamol', '10gram', 1, 'for goog', '2025-10-27 12:56:08'),
(3, 203, 'biogesic', '5oogm', 2, 'give medication and already assists', '2025-10-27 13:04:14'),
(4, 200, 'paracetamol1', '20grm', 1, 'giving medicine', '2025-10-27 13:32:02'),
(5, 197, 'paracetamol', '20grm', 1, 'for good', '2025-10-27 14:09:40'),
(6, 205, 'paracetamol12', '10gram', 1, 'giving medicine', '2025-10-27 15:41:07'),
(7, 206, 'paracetamol12', '10gram', 1, 'giving medicine', '2025-10-27 15:41:07'),
(8, 207, 'paracetamol12', '10gram', 1, 'giving medicine', '2025-10-27 15:41:07'),
(9, 208, 'paracetamol12', '10gram', 1, 'giving medicine', '2025-10-27 15:41:07'),
(10, 209, 'paracetamol', '10gram', 1, 'giving medicine', '2025-10-27 15:41:07'),
(11, 210, 'paracetamol12', '12grm', 1, 'giving medicine', '2025-10-27 15:41:07'),
(12, 192, 'biogesic', '5oogm', 1, 'for hurt', '2025-11-13 13:01:52'),
(13, 196, 'paracetamol', '20grm', 1, 'qd', '2025-11-13 13:07:03');

-- --------------------------------------------------------

--
-- Table structure for table `clinic_utilization`
--

CREATE TABLE `clinic_utilization` (
  `id` int(11) NOT NULL,
  `year` year(4) NOT NULL,
  `total_visits` int(11) DEFAULT 0,
  `return_visits` int(11) DEFAULT 0,
  `emergency_cases` int(11) DEFAULT 0,
  `health_concerns` int(11) DEFAULT 0,
  `date_generated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clinic_utilization`
--

INSERT INTO `clinic_utilization` (`id`, `year`, `total_visits`, `return_visits`, `emergency_cases`, `health_concerns`, `date_generated`) VALUES
(1, '2025', 1, 1, 35, 51, '2025-10-26 21:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_responders`
--

CREATE TABLE `emergency_responders` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `status` enum('Active','On Duty','Off Duty') NOT NULL DEFAULT 'Off Duty',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_active` timestamp NULL DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_responders`
--

INSERT INTO `emergency_responders` (`id`, `student_id`, `name`, `contact_number`, `status`, `created_at`, `updated_at`, `last_active`, `phone`) VALUES
(1, 16, 'kenjie suan', NULL, 'Off Duty', '2025-10-03 14:26:41', '2025-10-21 21:43:00', '2025-10-05 07:20:24', '09950377517'),
(2, 7, 'Jonnel Inoc', NULL, 'Off Duty', '2025-10-04 14:48:35', '2025-10-23 23:16:35', '2025-10-23 05:58:00', '09611528474'),
(3, 1, 'janpaul Yngco', NULL, 'Off Duty', '2025-10-04 15:23:53', '2025-10-21 21:43:00', '2025-10-04 07:51:08', '09272143851'),
(4, 20, 'izell inoc', NULL, 'Off Duty', '2025-10-04 16:33:17', '2025-10-21 21:43:00', '2025-10-04 08:33:38', '09264643929'),
(5, NULL, 'Jonnel Inoc', NULL, 'Off Duty', '2025-10-22 14:45:47', '2025-10-23 23:16:35', '2025-10-23 05:58:00', '09611528474');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `allDay` tinyint(1) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `reminder_minutes` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `appointment_id`, `title`, `start`, `end`, `allDay`, `note`, `reminder_minutes`) VALUES
(41, 203, 'Appointment: charlene Sinagpulo - tummy sakit', '2025-10-24 00:20:00', '2025-10-24 01:20:00', NULL, 'Confirmed appointment', 0),
(45, 175, 'Appointment: charlene Sinagpulo - GAYYYY', '2025-10-02 14:36:00', '2025-10-02 15:36:00', NULL, 'Confirmed appointment', 0),
(46, 175, 'Appointment: charlene Sinagpulo - GAYYYY', '2025-10-02 14:36:00', '2025-10-02 15:36:00', NULL, 'Confirmed appointment', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `medication_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `medications` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`medications`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medications`
--

CREATE TABLE `medications` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dosage` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `expiration_date` date DEFAULT NULL,
  `instructions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medications`
--

INSERT INTO `medications` (`id`, `name`, `description`, `created_at`, `updated_at`, `dosage`, `quantity`, `expiration_date`, `instructions`) VALUES
(1, 'paracetamol', '123', '2025-08-28 14:55:45', '2025-10-15 15:09:02', '35grm', 101, '2025-11-27', '213'),
(2, 'paracetamol', NULL, '2025-08-28 15:00:00', '2025-10-15 15:09:13', '10gram', 15, '2026-02-20', '3'),
(3, '', NULL, '2025-08-28 16:01:16', '2025-10-27 21:18:27', '5oogm', 3, NULL, NULL),
(4, 'charlene', NULL, '2025-09-28 15:32:56', '2025-10-15 15:09:22', '35grm', 19, '2026-02-12', 'dasdasd');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `expiration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `note_date` date NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `action_taken` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `created_at`, `is_read`, `action_taken`) VALUES
(19, 2022001, 'Student accepted the reschedule for appointment #145. Please confirm.', '2025-09-29 18:03:24', 1, NULL),
(20, 2022001, 'Student accepted the reschedule for appointment #146. Please confirm.', '2025-09-29 18:05:48', 1, NULL),
(21, 2022001, 'Student accepted the reschedule for appointment #147. Please confirm.', '2025-09-29 18:09:02', 1, NULL),
(22, 2022001, 'Student accepted the reschedule for appointment #150. Please confirm.', '2025-09-29 18:16:31', 1, NULL),
(23, 2022001, 'Student accepted the reschedule for appointment #151. Please confirm.', '2025-09-29 18:25:09', 1, NULL),
(24, 2022001, 'Student accepted the reschedule for appointment #155. Please confirm.', '2025-09-29 19:32:44', 1, NULL),
(25, 2022001, 'Student accepted the reschedule for appointment #161. Please confirm.', '2025-09-29 20:03:35', 1, NULL),
(26, 2022001, 'Student accepted the reschedule for appointment #162. Please confirm.', '2025-09-29 20:25:27', 1, NULL),
(27, 2022001, 'Student accepted the reschedule for appointment #163. Please confirm.', '2025-09-29 20:50:24', 1, NULL),
(28, 2022001, 'Student accepted the reschedule for appointment #164. Please confirm.', '2025-09-29 21:09:45', 1, NULL),
(29, 2022001, 'Student accepted the reschedule for appointment #165. Please confirm.', '2025-09-29 21:12:44', 1, NULL),
(30, 2022001, 'Student accepted the reschedule for appointment #166. Please confirm.', '2025-09-29 21:15:14', 1, NULL),
(31, 2022001, 'Student accepted the reschedule for appointment #167. Please confirm.', '2025-09-29 21:33:31', 1, NULL),
(32, 2022001, 'Student accepted the reschedule for appointment #168. Please confirm.', '2025-09-29 21:34:49', 1, NULL),
(33, 2022001, 'Student accepted the reschedule for appointment #169. Please confirm.', '2025-10-01 06:14:54', 1, NULL),
(34, 2022001, 'Student accepted the reschedule for appointment #175. Please confirm.', '2025-10-01 06:36:42', 1, NULL),
(35, 2022001, 'Student accepted the reschedule for appointment #178. Please confirm.', '2025-10-01 06:38:46', 1, NULL),
(36, 2022001, 'Student accepted the reschedule for appointment #179. Please confirm.', '2025-10-01 06:40:22', 1, NULL),
(37, 2022001, 'Student accepted the reschedule for appointment #181. Please confirm.', '2025-10-03 05:40:00', 1, NULL),
(38, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:32:40', 1, NULL),
(39, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:33:30', 1, NULL),
(40, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:34:00', 1, NULL),
(41, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:41:31', 1, NULL),
(42, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:45:09', 1, NULL),
(43, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:46:57', 1, NULL),
(44, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:48:48', 1, NULL),
(45, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 06:49:06', 1, NULL),
(46, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 07:05:57', 1, NULL),
(47, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 07:08:19', 1, NULL),
(48, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 07:10:06', 1, NULL),
(49, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 07:17:40', 1, NULL),
(50, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-04 07:25:06', 1, NULL),
(51, 2022001, 'Emergency reported by charlene Sinagpulo: arayy kooo at Floor 4floor, Room 401.', '2025-10-04 07:25:57', 1, NULL),
(52, 2022001, 'Emergency reported by charlene Sinagpulo: paul na send? at Floor 4floor, Room 401.', '2025-10-04 07:35:35', 1, NULL),
(53, 2022001, 'Emergency reported by charlene Sinagpulo: paul na send? at Floor 4floor, Room 401.', '2025-10-04 07:45:57', 1, NULL),
(54, 2022001, 'Emergency reported by charlene Sinagpulo: notification nalang at Floor 4floor, Room 401.', '2025-10-04 08:36:48', 1, NULL),
(55, 2022001, 'Emergency reported by charlene Sinagpulo: notification nalang at Floor 4floor, Room 401.', '2025-10-04 08:36:54', 1, NULL),
(56, 2022001, 'Emergency reported by charlene Sinagpulo: notification nalang at Floor 4floor, Room 401.', '2025-10-04 08:37:01', 1, NULL),
(57, 2022001, 'Student accepted the reschedule for appointment #185. Please confirm.', '2025-10-04 14:19:23', 1, NULL),
(58, 2022001, 'Emergency reported by charlene Sinagpulo: paul na send? at Floor 4floor, Room 401.', '2025-10-05 04:38:27', 1, NULL),
(59, 2022001, 'Emergency reported by charlene Sinagpulo: paul na send? at Floor 4floor, Room 401.', '2025-10-05 04:42:46', 1, NULL),
(60, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 04:43:13', 1, NULL),
(61, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 04:59:45', 1, NULL),
(62, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 05:00:18', 1, 'aid'),
(63, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 05:00:34', 1, NULL),
(64, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 05:04:54', 1, NULL),
(66, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-05 05:09:18', 1, NULL),
(68, 2022001, 'Emergency reported by kenjie suan: bleeding at Floor 4floor, Room 401.', '2025-10-05 05:22:53', 1, NULL),
(70, 2022001, 'Emergency reported by charlene Sinagpulo: goo at Floor 4floor, Room 401.', '2025-10-05 07:09:43', 1, NULL),
(71, 2022001, 'Emergency reported by charlene Sinagpulo: goo at Floor 4floor, Room 401.', '2025-10-05 07:17:33', 1, NULL),
(72, 2022001, 'Emergency reported by charlene Sinagpulo: goo at Floor 4floor, Room 401.', '2025-10-05 07:17:44', 1, NULL),
(74, 2022001, 'Emergency reported by charlene Sinagpulo: goo123 at Floor 4floor, Room 402.', '2025-10-05 07:20:58', 1, 'dasdwqdwdsf'),
(76, 2022001, 'Emergency reported by charlene Sinagpulo: goo123 at Floor 4floor, Room 402.', '2025-10-05 07:21:53', 1, 'first aid sfsfsfwmnsjksbfjksjkfbewkrnlkfnksdfnwqfsgdfhg'),
(77, 2022001, 'Low stock alert: paracetamol has only 1 items left.', '2025-10-14 07:07:16', 1, NULL),
(78, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-15.', '2025-10-14 07:07:16', 1, NULL),
(79, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:07:16', 1, NULL),
(80, 2022001, 'Expiration alert: biogisic has expired on 2025-09-17.', '2025-10-14 07:07:16', 1, NULL),
(81, 2022001, 'Low stock alert: charlene has only 1 items left.', '2025-10-14 07:07:16', 1, NULL),
(82, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:07:16', 1, NULL),
(83, 2022001, 'Low stock alert: paracetamol has only 1 items left.', '2025-10-14 07:14:10', 1, NULL),
(84, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-15.', '2025-10-14 07:14:10', 1, NULL),
(85, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:10', 1, NULL),
(86, 2022001, 'Expiration alert: biogisic has expired on 2025-09-17.', '2025-10-14 07:14:10', 1, NULL),
(87, 2022001, 'Low stock alert: charlene has only 1 items left.', '2025-10-14 07:14:10', 1, NULL),
(88, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:14:10', 1, NULL),
(89, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-15.', '2025-10-14 07:14:16', 1, NULL),
(90, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:16', 1, NULL),
(91, 2022001, 'Expiration alert: biogisic has expired on 2025-09-17.', '2025-10-14 07:14:16', 1, NULL),
(92, 2022001, 'Low stock alert: charlene has only 1 items left.', '2025-10-14 07:14:16', 1, NULL),
(93, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:14:16', 1, NULL),
(94, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-15.', '2025-10-14 07:14:24', 1, NULL),
(95, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:24', 1, NULL),
(96, 2022001, 'Expiration alert: biogisic has expired on 2025-09-17.', '2025-10-14 07:14:24', 1, NULL),
(97, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:14:24', 1, NULL),
(98, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 07:14:38', 1, NULL),
(99, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:38', 1, NULL),
(100, 2022001, 'Expiration alert: biogisic has expired on 2025-09-17.', '2025-10-14 07:14:38', 1, NULL),
(101, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:14:38', 1, NULL),
(102, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 07:14:47', 1, NULL),
(103, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:47', 1, NULL),
(104, 2022001, 'Expiration alert: charlene has expired on 2025-09-29.', '2025-10-14 07:14:47', 1, NULL),
(105, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 07:14:58', 1, NULL),
(106, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:14:58', 1, NULL),
(107, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-14 07:14:58', 1, NULL),
(108, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 07:19:10', 1, NULL),
(109, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:19:10', 1, NULL),
(110, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-14 07:19:10', 1, NULL),
(111, 2022001, 'Low stock alert: paracetamol has only 10 items left.', '2025-10-14 07:19:17', 1, NULL),
(112, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 07:19:17', 1, NULL),
(113, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 07:19:17', 1, NULL),
(114, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-14 07:19:17', 1, NULL),
(115, 2022001, 'Low stock alert: paracetamol has only 10 items left.', '2025-10-14 09:32:58', 1, NULL),
(116, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-14 09:32:58', 1, NULL),
(117, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-14 09:32:58', 1, NULL),
(118, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-14 09:32:58', 1, NULL),
(119, 2022001, 'Low stock alert: paracetamol has only 10 items left.', '2025-10-15 07:08:49', 1, NULL),
(120, 2022001, 'Expiration alert: paracetamol has expired on 2025-09-25.', '2025-10-15 07:08:49', 1, NULL),
(121, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-15 07:08:49', 1, NULL),
(122, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-15 07:08:49', 1, NULL),
(123, 2022001, 'Expiration alert: paracetamol has expired on 2025-08-14.', '2025-10-15 07:09:02', 1, NULL),
(124, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-15 07:09:02', 1, NULL),
(125, 2022001, 'Expiration alert: charlene has expired on 2025-10-14.', '2025-10-15 07:09:13', 1, NULL),
(126, 2022001, 'Student accepted the reschedule for appointment #191. Please confirm.', '2025-10-22 06:24:50', 1, NULL),
(127, 2022001, 'Student accepted the reschedule for appointment #192. Please confirm.', '2025-10-22 06:39:44', 1, NULL),
(128, 2022001, 'Student accepted the reschedule for appointment #193. Please confirm.', '2025-10-22 06:41:55', 1, NULL),
(130, 2022001, 'Emergency reported by charlene Sinagpulo: bleeding at Floor 4floor, Room 401.', '2025-10-22 06:48:55', 1, 'done having first aid qeqeqfsfdhhewrwfsfwafe'),
(131, 2022001, 'Student accepted the reschedule for appointment #194. Please confirm.', '2025-10-22 07:04:54', 1, NULL),
(132, 2022001, 'Student accepted the reschedule for appointment #196. Please confirm.', '2025-10-22 07:34:58', 1, NULL),
(133, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 05:27:26', 1, NULL),
(134, 2022001, 'Emergency reported by kenjie suan: bleeding at Floor 4floor, Room 402.', '2025-10-23 05:38:40', 1, 'ang student kay na assists na sya tapos na tagaan na ug tambal'),
(135, 2022001, 'Student declined the reschedule for appointment #199.', '2025-10-23 05:45:34', 1, NULL),
(136, 2022001, 'Student declined the reschedule for appointment #199.', '2025-10-23 05:45:36', 1, NULL),
(137, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 15:41:43', 1, NULL),
(138, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 15:43:31', 1, NULL),
(139, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 15:54:51', 1, NULL),
(140, 2022001, 'Student accepted the reschedule for appointment #201. Please confirm.', '2025-10-23 16:06:40', 1, NULL),
(141, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 16:16:26', 1, NULL),
(142, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 16:16:52', 1, NULL),
(143, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 16:21:22', 1, NULL),
(144, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-23 16:22:46', 1, NULL),
(145, 2022001, 'Expiration alert: biogisic has expired on 2025-10-23.', '2025-10-26 12:21:10', 1, NULL),
(146, 2022001, 'Low stock alert:  has only 3 items left.', '2025-10-27 13:18:27', 1, NULL),
(147, 2022001, 'Expiration alert:  has expired on .', '2025-10-27 13:18:27', 1, NULL),
(148, 2022001, 'Low stock alert:  has only 3 items left.', '2025-10-27 13:36:47', 1, NULL),
(149, 2022001, 'Expiration alert:  has expired on .', '2025-10-27 13:36:47', 1, NULL),
(153, 2022001, 'Follow-up appointment scheduled for appointment #182', '2025-11-18 01:09:49', 1, NULL),
(154, 2022001, 'Follow-up appointment scheduled for appointment #180', '2025-11-18 01:10:31', 1, NULL),
(155, 2022001, 'Follow-up appointment scheduled for appointment #175', '2025-11-18 01:20:03', 1, NULL),
(156, 2022001, 'Follow-up appointment scheduled for appointment #175', '2025-11-18 01:22:09', 1, NULL),
(157, 2022001, 'Low stock alert:  has only 3 items left.', '2025-11-19 05:50:17', 1, NULL),
(158, 2022001, 'Expiration alert:  has expired on .', '2025-11-19 05:50:17', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `patient_code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` enum('M','F','O') NOT NULL,
  `address` varchar(250) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `name`) VALUES
(1, 'BSIT'),
(2, 'BSHM'),
(3, 'BEED'),
(4, 'BSED');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'nurse'),
(1, 'student');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `program_id`, `name`, `semester`, `is_archived`) VALUES
(1, 1, 'BSIT 1A', '', 1),
(2, 1, 'BSIT 1B', '1st Semester', 0),
(3, 3, 'BEED 1A', '1st Semester', 0),
(4, 2, 'BSHM 1A', '1st Semester', 0),
(5, 2, 'BSHM 1B', '1st Semester', 0),
(6, 4, 'BSED 1A', '1st Semester', 0),
(7, 3, 'BEED 1B', '1st Semester', 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(100) DEFAULT NULL,
  `guardian_address` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(20) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `course` varchar(50) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `section` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `vaccine_record` varchar(255) DEFAULT NULL,
  `medical_history` varchar(255) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `requirements_completed` tinyint(1) DEFAULT 0,
  `emergency_contact` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `blood_type` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `bmi` decimal(5,2) DEFAULT NULL,
  `is_responder` tinyint(1) DEFAULT 0,
  `responder_status` enum('Active','On Duty','Off Duty') DEFAULT 'Off Duty'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `middle_name`, `last_name`, `birthday`, `gender`, `email`, `phone`, `home_address`, `guardian_name`, `guardian_address`, `guardian_contact`, `relationship`, `course`, `year_level`, `section`, `password`, `profile_picture`, `vaccine_record`, `medical_history`, `section_id`, `requirements_completed`, `emergency_contact`, `created_at`, `blood_type`, `allergies`, `height`, `weight`, `bmi`, `is_responder`, `responder_status`) VALUES
(1, '202203', 'janpaul', 'P', 'Yngco', '2025-07-31', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', '0921323', 'sister', 'BSIT', '1', 'C', '$2y$10$5EKGii72aUAP3D0NyP9UFeHIzqha1H/4PzSqv8EHzex4K6xY8P5wq', NULL, NULL, NULL, 0, 0, NULL, '2025-08-19 07:22:26', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(2, '202203', 'janpaul', 'P', 'Yngco', '2025-08-15', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', '0921323', 'sister', 'BSIT', '1', 'C', '$2y$10$7tpcr/LpawC6w1zq8aIXjuSALmCE08WMEppPFtcub/FneOfca8YNy', NULL, NULL, NULL, 0, 0, NULL, '2025-08-19 07:23:34', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(3, '202203', 'janpaul', 'P', 'Yngco', '2025-07-30', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', '0921323', 'sister', 'BSIT', '1', 'B', '$2y$10$LOzpnPRZ6J/z/kpz.tIvMuWirQrjU27jnHlwGdKNpHLfO3TARcYKO', NULL, NULL, NULL, 0, 0, NULL, '2025-08-19 08:26:16', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(4, '202203', 'janpaul', 'P', 'Yngco', '2025-08-06', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', '0921323', 'sister', 'BSIT', '1', 'B', '$2y$10$RA8SWbkFdwp6/JrCN.N0quZs6xDiZfSEh4EVDx4gaXMWdCUmOFely', NULL, NULL, NULL, 0, 0, NULL, '2025-08-21 12:21:51', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(5, '202203', 'janpaul', 'P', 'Yngco', '2025-08-04', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', '0921323', 'sister', 'BSIT', '1', 'BSIT 1B', '$2y$10$8ZZFIG7.wK9An1vEhu95SupvfGP/0syZrIFqKqoGJANVKk9tSHlOK', NULL, NULL, NULL, 2, 0, NULL, '2025-08-21 12:29:20', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(6, '2022031', 'charlene mae ', 'RIO', 'Sinagpulo', '2003-03-31', 'female', 'charlenesinagpulo@gmail.com', '09950377527', 'alegria cordova cebu', 'jonnel Inoc', 'alegria cordova cebu', '09611528474', 'brother', 'BSIT', '1', 'BSIT 1B', '$2y$10$l1Vg9buzzpJX1YSWlaspSe.aUkX0eS6fykO902ZxskqgGAeh1XAZy', NULL, NULL, NULL, 2, 0, NULL, '2025-08-21 12:39:13', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(7, '20220322', 'Jonnel', 'Pogoy', 'Inoc', '2003-06-02', 'male', 'inocjonnel18@gmail.om', '09950377527', 'alegria cordova cebu', 'Charlene mae', 'alegria cordova cebu', NULL, 'sister', 'BSIT', '1', 'BSIT 1A', '$2y$10$Ys0cTkjwKCA6yKDBQkbcsOIgP5cwnWWbpFVTpSS8rxwBpjK/brPQ2', NULL, NULL, NULL, 1, 0, '09611528474', '2025-08-21 12:52:18', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(8, '20220322', 'charlene mae ', 'RIO', 'Sinagpulo', '2025-08-12', 'male', 'charlenesinagpulo@gmail.com', '09950377527', 'alegria cordova cebu', 'jonnel Inoc', 'alegria cordova cebu', NULL, 'brother', 'BEED', '1', 'BEED 1A', '$2y$10$AABkqtn7xDVjLzjwsJb5Fu6KmD0bQ95WiiSRgvQv.IBH.jYqbSNyG', NULL, NULL, NULL, 3, 0, '09611528474', '2025-08-27 08:17:36', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(9, '2022077', 'janpaul', 'P', 'Yngco', '2025-08-21', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', NULL, 'brother', 'BSIT', '1', 'BSIT 1B', '$2y$10$1OwQ1Pxy1TONbtVXjkVrWe.QEfudv8qGUxrnN.PTUHvT1.LRRoe4e', NULL, NULL, NULL, 2, 0, '09611528474', '2025-08-28 08:05:18', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(10, '202501', 'charlene', 'inoc', 'Sinagpulo', '2025-09-09', 'female', 'charlenesinagpulo@gmail.com', '09950377527', 'alegria cordova cebu', 'jonnel Inoc', 'alegria cordova cebu', NULL, 'brother', 'BSHM', '1', 'BSHM 1B', '$2y$10$2TzUWxLnnpzjcapGCZwfyO4MirFkWLAPU9xNcfycaXS6nvU1YvJPO', 'uploads/profile_68c8d9c962963_Screenshot (8).png', 'uploads/requirements/xray68c8d9c963952_Screenshot (8).png', 'uploads/requirements/medhist68c8d9c963ba9_Screenshot (8).png', 5, 0, '09611528474', '2025-09-16 03:30:17', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(11, '2025002', 'Jonnel', 'RIO', 'Sinagpulo', '2025-09-11', 'male', 'inocjonnel18@gmail.om', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', NULL, 'brother', 'BSHM', '1', 'BSHM 1B', '$2y$10$EDtQwAN9DP7m9NtOMfk9F.TuQ1avvx5rVmb.ONmDyCtXYpO1so1ku', 'uploads/profile_68c8ded57d6d3_cat.jpg', 'uploads/requirements/xray68c8ded57dafa_cat.jpg', 'uploads/requirements/medhist68c8ded57de49_cat.jpg', 5, 0, '09611528474', '2025-09-16 03:51:49', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(12, '2025003', 'janpaul', 'P', 'Yngco', '2025-09-02', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', NULL, 'mother', 'BSIT', '1', 'BSIT 1A', '$2y$10$clO3ajgy2ECBUwxN3zObcuLjn/6FtAHsnevNkSgFlZ7k0wgoJtxxm', 'uploads/profile_68cbab310cca1_cat.jpg', 'uploads/requirements/xray68cbab310e6ba_cat.jpg', NULL, 1, 0, '0921323', '2025-09-18 06:48:17', NULL, NULL, NULL, NULL, NULL, 0, 'Off Duty'),
(13, '2025003', 'janpaul', 'jwk``', 'Yngco', '2025-09-28', 'female', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', NULL, 'mother', 'BSIT', '1', 'BSIT 1B', '$2y$10$CbcdPUq6CHYg5J4WQe/5AuTykXf7ZIIMu9L3ZyMsgeyIExbieabjy', 'uploads/profile_68d8d8761cd3c_Screenshot (1).png', 'uploads/requirements/xray68d8d8761d1ae_Screenshot (6).png', 'uploads/requirements/medhist68d8d8761d692_Screenshot (7).png', 2, 0, '09611528474', '2025-09-28 06:40:54', NULL, NULL, NULL, NULL, NULL, 1, 'Off Duty'),
(14, '20225004', 'janpaul', 'P', 'Yngco', '2025-09-12', 'male', 'janpaul@gmail.com', '09123456789', 'bangbang cordova', 'jolo sahagon', 'sanmiguel', NULL, 'mother', 'BSIT', '1', 'BSIT 1B', '$2y$10$DB3Lv1sBjmhksaxXoCNAO.e7eriURg7JMYi5lAELJ1KxgyYOT6ZIe', 'uploads/profile_68d8d90a58e93_Screenshot (7).png', NULL, NULL, 2, 0, '0921323', '2025-09-28 06:43:22', NULL, NULL, NULL, NULL, NULL, 1, 'Off Duty'),
(15, '2025005', 'jolo', 'y', 'sahagon', '2025-10-06', 'male', 'jolo123@gmail.com', '09950377527', 'bangbang cordova', 'janpaul yngco', 'bangbang cordova', NULL, 'father', 'BSIT', '1', 'BSIT 1B', '$2y$10$SgwJ53zfG4sKlkQP7bd.Fe0GLDj.WcTHTiewFwpJm/d5NLirHv8om', NULL, NULL, NULL, 2, 0, '09611528474', '2025-10-01 08:04:14', NULL, NULL, NULL, NULL, NULL, 1, 'Off Duty'),
(16, '2025006', 'kenjie', 'p', 'suan', '2025-10-01', 'male', 'kenjie@gmail.com', '09950377527', 'alegria cordova cebu', 'kurt marababol', 'alegria cordova cebu', NULL, 'father', 'BEED', '1', 'BEED 1A', '$2y$10$KRBmJY70zkBcDY3r5ZUQte..Gmuk1gbiOr6/3kcmRQgLs51LxzdZO', NULL, NULL, NULL, 3, 0, '09611528474', '2025-10-01 08:07:30', NULL, NULL, NULL, NULL, NULL, 1, 'Off Duty'),
(17, '2025007', 'kenjie', 'p', 'suan', '2025-10-22', 'male', 'kenjie@gmail.com', '09950377527', 'alegria cordova cebu', 'kurt marababol', 'alegria cordova cebu', NULL, 'father', 'BSIT', '1', 'BSIT 1B', '$2y$10$CyCIsFD3Fosinx7O7JUmWuPsOehpmMZCk7In1yu6kpoyxEz1GsIdK', NULL, NULL, NULL, 2, 0, '09611528474', '2025-10-03 06:26:41', NULL, NULL, NULL, NULL, NULL, 1, 'Off Duty'),
(18, '2025008', 'Jonnel', 'P', 'Inoc', '2025-10-04', 'male', 'inocjonnel18@gmail.om', '09611528474', 'alegria cordova cebu', 'Charlene mae', 'alegria cordova cebu', NULL, 'mother', 'BSIT', '1', 'BSIT 1B', '$2y$10$hP18nvW32FjqD1nY1GSwxuQ5DJgNXn3HZm8tOQfv9kr2Y2ED5XwRa', NULL, NULL, NULL, 2, 0, '09950377517', '2025-10-04 06:48:35', NULL, NULL, NULL, NULL, NULL, 1, 'Active'),
(19, '2025009', 'janpaul', 'P', 'Yngco', '2025-10-06', 'male', 'janpaul@gmail.com', '09272143851', 'bangbang cordova', 'jolo sahagon', 'bangbang cordova', NULL, 'father', 'BSIT', '1', 'BSIT 1B', '$2y$10$4LeAX504nafArWGFnS2LH.8ufIy3HqwFvnORJFiGq7yRhU.MAosVG', NULL, NULL, NULL, 2, 0, '09611528474', '2025-10-04 07:23:53', NULL, NULL, NULL, NULL, NULL, 1, 'Active'),
(20, '2025010', 'izell', 'p', 'inoc', '2025-10-15', 'female', 'izell@gmail.com', '09264643929', 'alegria cordova cebu', 'nina', 'alegria cordova cebu', NULL, 'mother', 'BSHM', '1', 'BSHM 1A', '$2y$10$as1af4IMLo1CuUxUlw5gcOiRP4BoYPyEjNKKEmW2duCP8bFleot9.', NULL, NULL, NULL, 4, 0, '09611528474', '2025-10-04 08:33:17', NULL, NULL, NULL, NULL, NULL, 1, 'Active'),
(21, '202512', 'Jonnel', 'P', 'Inoc', '2003-06-02', 'male', 'inocjonnel18@gmail.om', '09611528474', 'alegria cordova cebu', 'Charlene mae', 'alegria cordova cebu', NULL, 'mother', 'BSIT', '1', 'BSIT 1B', '$2y$10$SKdRdgeaVnCeNZYq3ODBce0agNyYg0gOgnNbTtlfAMlN4cn0b8lb2', NULL, NULL, NULL, 2, 0, '09950377517', '2025-10-22 06:45:47', NULL, NULL, NULL, NULL, NULL, 1, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `student_notifications`
--

CREATE TABLE `student_notifications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0,
  `appointment_id` int(11) DEFAULT NULL,
  `reschedule_status` enum('pending','accepted','declined') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_notifications`
--

INSERT INTO `student_notifications` (`id`, `student_id`, `message`, `created_at`, `is_read`, `appointment_id`, `reschedule_status`) VALUES
(1, 10, 'Your appointment has been rescheduled to 2025-09-22 02:11 PM', '2025-09-22 14:11:08', 1, NULL, 'pending'),
(2, 10, 'Your appointment has been rescheduled to 2025-09-01 02:17 PM', '2025-09-22 14:17:22', 1, NULL, 'pending'),
(3, 10, 'Your appointment has been rescheduled to 2025-09-13 02:23 PM', '2025-09-22 14:23:04', 1, NULL, 'pending'),
(4, 10, 'Your appointment has been rescheduled to 2025-09-23 02:28 PM', '2025-09-22 14:28:26', 1, NULL, 'pending'),
(5, 10, 'Your appointment has been rescheduled to 2025-09-23 02:32 PM', '2025-09-22 14:32:09', 1, NULL, 'pending'),
(6, 10, 'Your appointment has been rescheduled to 2025-09-23 02:36 PM', '2025-09-22 14:36:27', 1, NULL, 'pending'),
(7, 10, 'Your appointment has been rescheduled to 2025-09-02 02:43 PM', '2025-09-22 14:43:26', 1, NULL, 'pending'),
(8, 10, 'Your appointment has been rescheduled to 2025-09-25 02:48 PM', '2025-09-22 14:48:10', 1, NULL, 'pending'),
(9, 10, 'Your appointment has been rescheduled to 2025-09-06 02:50 PM', '2025-09-22 14:50:24', 1, NULL, 'pending'),
(10, 10, 'Your appointment has been rescheduled to 2025-09-22 02:54 PM', '2025-09-22 14:54:14', 1, NULL, 'pending'),
(11, 10, 'Your appointment has been rescheduled to 2025-09-20 02:57 PM', '2025-09-22 14:57:05', 1, NULL, 'pending'),
(12, 10, 'Your appointment has been rescheduled to 2025-09-04 02:57 PM', '2025-09-22 14:57:52', 1, NULL, 'pending'),
(13, 10, 'Your appointment has been rescheduled to 2025-09-13 03:00 PM', '2025-09-22 15:00:44', 1, NULL, 'pending'),
(14, 10, 'Your appointment has been rescheduled to 2025-09-04 03:06 PM', '2025-09-22 15:06:15', 1, NULL, 'pending'),
(15, 10, 'Your appointment has been rescheduled to 2025-09-04 03:15 PM', '2025-09-22 15:15:21', 1, NULL, 'pending'),
(16, 10, 'Your appointment has been rescheduled to 2025-09-02 03:19 PM', '2025-09-22 15:19:10', 1, NULL, 'pending'),
(17, 10, 'Your appointment has been rescheduled to 2025-09-04 03:23 PM', '2025-09-22 15:24:01', 1, NULL, 'pending'),
(18, 10, 'Your appointment has been rescheduled to 2025-09-03 03:41 PM', '2025-09-22 15:41:34', 1, NULL, 'pending'),
(19, 10, 'Your appointment has been rescheduled to 2025-09-06 03:47 PM', '2025-09-22 15:45:26', 1, NULL, 'pending'),
(20, 10, 'Your appointment has been rescheduled to 2025-09-05 06:46 PM', '2025-09-22 15:46:13', 1, NULL, 'pending'),
(21, 10, 'Your appointment has been rescheduled to 2025-09-06 03:49 PM', '2025-09-22 15:49:40', 1, NULL, 'pending'),
(22, 10, 'Your appointment has been rescheduled to 2025-09-01 03:54 PM', '2025-09-22 15:53:22', 1, NULL, 'pending'),
(23, 10, 'Your appointment has been rescheduled to 2025-09-05 03:56 PM', '2025-09-22 15:56:18', 1, NULL, 'pending'),
(24, 10, 'Your appointment has been rescheduled to 2025-09-02 03:59 PM', '2025-09-22 15:59:47', 1, NULL, 'pending'),
(25, 10, 'Your appointment has been rescheduled to 2025-09-06 04:02 PM', '2025-09-22 16:02:47', 1, NULL, 'pending'),
(26, 10, 'Your appointment has been rescheduled to 2025-09-04 04:07 PM', '2025-09-22 16:07:08', 1, NULL, 'pending'),
(27, 10, 'Your appointment has been rescheduled to 2025-09-04 04:39 PM', '2025-09-22 16:39:33', 1, NULL, 'pending'),
(28, 10, 'Your appointment has been rescheduled to 2025-09-11 02:34 PM', '2025-09-25 14:34:48', 1, NULL, 'pending'),
(29, 10, 'Your appointment has been rescheduled to 2025-09-28 03:20 PM', '2025-09-28 14:18:56', 1, NULL, 'pending'),
(30, 10, 'Your appointment has been rescheduled to 2025-09-09 03:39 PM', '2025-09-28 15:39:46', 1, NULL, 'pending'),
(31, 10, 'Your appointment has been rescheduled to 2025-09-29 03:40 PM', '2025-09-28 15:40:50', 1, NULL, 'pending'),
(32, 10, 'Your appointment has been rescheduled to 2025-10-01 06:41 PM', '2025-09-28 15:41:57', 1, NULL, 'pending'),
(33, 10, 'Your appointment has been rescheduled to 2025-09-05 04:02 PM', '2025-09-28 16:03:02', 1, NULL, 'pending'),
(34, 10, 'Your appointment has been rescheduled to 2025-09-28 04:15 PM', '2025-09-28 16:15:56', 1, NULL, 'pending'),
(35, 10, 'Your appointment has been rescheduled to 2025-09-28 10:43 PM', '2025-09-28 19:43:10', 1, NULL, 'pending'),
(36, 10, 'Your appointment has been rescheduled to 2025-09-28 01:43 PM', '2025-09-28 19:44:13', 1, NULL, 'pending'),
(37, 10, 'Your appointment has been rescheduled to 2025-09-28 02:43 PM', '2025-09-28 19:49:58', 1, NULL, 'pending'),
(38, 10, 'Your appointment has been rescheduled to 2025-09-28 10:04 PM', '2025-09-28 20:04:44', 1, NULL, 'pending'),
(39, 10, 'Your appointment has been rescheduled to 2025-09-28 01:04 PM. Please confirm.', '2025-09-28 20:09:41', 1, NULL, 'pending'),
(40, 10, 'Your appointment has been rescheduled to 2025-09-28 03:04 PM. Please confirm.', '2025-09-28 20:32:01', 1, NULL, 'pending'),
(41, 10, 'Your appointment has been rescheduled to 2025-09-28 04:04 PM. Please confirm.', '2025-09-28 20:50:46', 1, NULL, 'pending'),
(42, 10, 'Your appointment has been rescheduled to 2025-09-28T17:04. Please confirm.', '2025-09-28 20:57:12', 1, 115, 'accepted'),
(43, 10, 'Your appointment has been rescheduled to 2025-09-28T18:04. Please confirm.', '2025-09-28 21:02:43', 1, 116, 'accepted'),
(44, 10, 'Your appointment has been rescheduled to 2025-09-28 04:00 PM. Please confirm.', '2025-09-28 21:25:50', 1, 115, 'accepted'),
(48, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 01:18 PM. Please accept or decline.', '2025-09-29 23:18:31', 1, 126, 'accepted'),
(49, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 11:25 PM. Please accept or decline.', '2025-09-29 23:25:57', 1, 129, 'accepted'),
(50, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 11:25 PM. Please accept or decline.', '2025-09-29 23:35:32', 1, 119, 'accepted'),
(51, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 11:25 PM. Please accept or decline.', '2025-09-29 23:44:13', 1, 130, 'accepted'),
(52, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 11:25 PM. Please accept or decline.', '2025-09-29 23:53:59', 1, 131, 'accepted'),
(53, 10, 'Your appointment has been rescheduled to Sep 29, 2025 - 11:25 PM. Please accept or decline.', '2025-09-30 00:13:17', 1, 132, 'accepted'),
(54, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 01:19 AM. Please accept or decline.', '2025-09-30 00:20:03', 1, 133, 'accepted'),
(55, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 01:23 AM. Please accept or decline.', '2025-09-30 00:23:37', 1, 134, 'accepted'),
(56, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 12:30 AM. Please accept or decline.', '2025-09-30 00:29:21', 1, 135, 'accepted'),
(57, 10, 'Your appointment has been rescheduled to Sep 19, 2025 - 12:39 AM. Please accept or decline.', '2025-09-30 00:39:07', 1, 136, 'accepted'),
(58, 10, 'Your appointment has been rescheduled to Sep 19, 2025 - 12:39 AM. Please accept or decline.', '2025-09-30 00:40:29', 1, 137, 'accepted'),
(59, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:43 AM. Please accept or decline.', '2025-09-30 00:43:42', 1, 138, NULL),
(60, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:19 AM. Please accept or decline.', '2025-09-30 01:19:04', 1, 139, NULL),
(61, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:40 AM. Please accept or decline.', '2025-09-30 01:40:09', 1, 140, 'accepted'),
(62, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:48 AM. Please accept or decline.', '2025-09-30 01:48:18', 1, 141, 'accepted'),
(63, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:53 AM. Please accept or decline.', '2025-09-30 01:53:21', 1, 142, 'accepted'),
(64, 10, 'Your appointment has been rescheduled to Sep 06, 2025 - 01:55 AM. Please accept or decline.', '2025-09-30 01:55:53', 1, 143, 'accepted'),
(65, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 05:00 AM. Please accept or decline.', '2025-09-30 02:00:17', 1, 144, 'accepted'),
(66, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 04:03 AM. Please accept or decline.', '2025-09-30 02:03:18', 1, 145, 'accepted'),
(67, 10, 'Your appointment has been rescheduled to Sep 06, 2025 - 02:05 AM. Please accept or decline.', '2025-09-30 02:05:40', 1, 146, 'accepted'),
(68, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 02:10 AM. Please accept or decline.', '2025-09-30 02:08:54', 1, 147, 'accepted'),
(69, 10, 'Your appointment has been rescheduled to Sep 06, 2025 - 02:12 AM. Please accept or decline.', '2025-09-30 02:12:13', 1, 148, 'accepted'),
(70, 10, 'Your appointment has been rescheduled to Sep 05, 2025 - 02:16 AM. Please accept or decline.', '2025-09-30 02:16:21', 1, 150, 'accepted'),
(71, 10, 'Your appointment has been rescheduled to Sep 05, 2025 - 02:24 AM. Please accept or decline.', '2025-09-30 02:25:02', 1, 151, 'accepted'),
(72, 10, 'Your appointment has been rescheduled to Sep 05, 2025 - 03:32 AM. Please accept or decline.', '2025-09-30 03:32:37', 1, 155, 'accepted'),
(73, 10, 'Your appointment has been rescheduled to Sep 04, 2025 - 04:03 AM. Please accept or decline.', '2025-09-30 04:03:27', 1, 161, 'accepted'),
(74, 10, 'Your appointment has been rescheduled to Sep 06, 2025 - 04:25 AM. Please accept or decline.', '2025-09-30 04:25:20', 1, 162, 'accepted'),
(75, 10, 'Your appointment has been rescheduled to Sep 04, 2025 - 04:50 AM. Please accept or decline.', '2025-09-30 04:50:15', 1, 163, 'accepted'),
(76, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 06:09 AM. Please accept or decline.', '2025-09-30 05:09:37', 1, 164, 'accepted'),
(77, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 06:09 AM. Please accept or decline.', '2025-09-30 05:12:39', 1, 165, 'accepted'),
(78, 10, 'Your appointment has been rescheduled to Sep 30, 2025 - 07:15 AM. Please accept or decline.', '2025-09-30 05:15:11', 1, 166, 'accepted'),
(79, 10, 'Your appointment has been rescheduled to Sep 06, 2025 - 07:15 AM. Please accept or decline.', '2025-09-30 05:33:05', 1, 167, 'accepted'),
(80, 10, 'Your appointment has been rescheduled to Sep 18, 2025 - 05:34 AM. Please accept or decline.', '2025-09-30 05:34:41', 1, 168, 'accepted'),
(81, 10, 'Your appointment has been rescheduled to Oct 18, 2025 - 01:51 PM. Please accept or decline.', '2025-10-01 13:51:35', 1, 169, 'accepted'),
(82, 10, 'Your appointment has been rescheduled to Oct 02, 2025 - 02:36 PM. Please accept or decline.', '2025-10-01 14:36:33', 1, 175, 'accepted'),
(83, 10, 'Your appointment has been rescheduled to Oct 01, 2025 - 02:40 PM. Please accept or decline.', '2025-10-01 14:38:31', 1, 178, 'accepted'),
(84, 10, 'Your appointment has been rescheduled to Oct 01, 2025 - 02:41 PM. Please accept or decline.', '2025-10-01 14:40:04', 1, 179, 'accepted'),
(85, 10, 'Your appointment has been rescheduled to Oct 03, 2025 - 03:39 PM. Please accept or decline.', '2025-10-03 13:39:52', 1, 181, 'accepted'),
(86, 10, 'Your appointment has been accepted by the nurse.', '2025-10-03 13:46:20', 1, 182, ''),
(87, 10, 'Your appointment has been successfully booked!.', '2025-10-03 13:48:32', 1, 183, ''),
(88, 10, 'Your appointment has been rejected by the nurse.', '2025-10-03 14:51:38', 1, 184, ''),
(89, 10, 'Your appointment has been rescheduled to Oct 04, 2025 - 01:19 PM. Please accept or decline.', '2025-10-04 22:19:18', 1, 185, 'accepted'),
(90, 10, 'Your appointment has been successfully booked!.', '2025-10-04 22:19:26', 1, 185, ''),
(91, 10, 'Your appointment has been rejected by the nurse.', '2025-10-05 14:09:45', 1, 186, ''),
(92, 10, 'Your appointment has been successfully booked!.', '2025-10-11 09:36:40', 1, 187, ''),
(93, 10, 'Your appointment has been successfully booked!.', '2025-10-14 17:18:31', 1, 188, ''),
(94, 10, 'Your appointment has been successfully booked!.', '2025-10-14 17:39:11', 1, 189, ''),
(95, 10, 'Your appointment has been successfully booked!.', '2025-10-14 17:47:26', 1, 190, ''),
(96, 10, 'Your appointment has been rescheduled to Oct 22, 2025 - 03:24 PM. Please accept or decline.', '2025-10-22 14:24:33', 1, 191, 'accepted'),
(97, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:24:56', 1, 191, ''),
(98, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:24:56', 1, 191, ''),
(99, 10, 'Your appointment has been rescheduled to Oct 22, 2025 - 06:24 PM. Please accept or decline.', '2025-10-22 14:25:48', 1, 192, 'accepted'),
(100, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:39:57', 1, 192, ''),
(101, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:39:57', 1, 192, ''),
(102, 10, 'Your appointment has been rescheduled to Oct 22, 2025 - 05:41 PM. Please accept or decline.', '2025-10-22 14:41:39', 1, 193, 'accepted'),
(103, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:42:04', 1, 193, ''),
(104, 10, 'Your appointment has been successfully booked!.', '2025-10-22 14:42:04', 1, 193, ''),
(105, 21, 'Your appointment has been rescheduled to Oct 22, 2025 - 05:04 PM. Please accept or decline.', '2025-10-22 15:04:48', 1, 194, 'accepted'),
(106, 21, 'Your appointment has been successfully booked!.', '2025-10-22 15:04:59', 1, 194, ''),
(107, 21, 'Your appointment has been successfully booked!.', '2025-10-22 15:04:59', 1, 194, ''),
(108, 21, 'Your appointment has been successfully booked!.', '2025-10-22 15:16:19', 1, 195, ''),
(109, 10, 'Your appointment has been rescheduled to Oct 22, 2025 - 03:36 PM. Please accept or decline.', '2025-10-22 15:34:52', 1, 196, 'accepted'),
(110, 10, 'Your appointment has been successfully booked!.', '2025-10-22 15:35:10', 1, 196, ''),
(111, 10, 'Your appointment has been successfully booked!.', '2025-10-22 15:35:10', 1, 196, ''),
(112, 21, 'Your appointment has been successfully booked!.', '2025-10-22 15:35:46', 1, 197, ''),
(113, 10, 'Your appointment has been rescheduled to Oct 24, 2025 - 04:35 PM. Please accept or decline.', '2025-10-23 13:36:10', 1, 198, 'declined'),
(114, 10, 'Your appointment has been rescheduled to Oct 23, 2025 - 04:45 PM. Please accept or decline.', '2025-10-23 13:45:26', 1, 199, 'declined'),
(115, 10, 'Your appointment has been successfully booked!.', '2025-10-23 13:45:48', 1, 199, ''),
(116, 10, 'Your appointment has been successfully booked!.', '2025-10-23 13:45:48', 1, 199, ''),
(117, 10, 'Your appointment has been successfully booked!.', '2025-10-23 13:45:50', 1, 199, ''),
(118, 10, 'Your appointment has been successfully booked!.', '2025-10-23 13:45:50', 1, 199, ''),
(119, 10, 'Your appointment has been successfully booked!.', '2025-10-23 14:01:39', 1, 200, ''),
(120, 10, 'Your appointment has been successfully booked!.', '2025-10-24 00:02:17', 1, 202, ''),
(121, 10, 'Your appointment has been rescheduled to Oct 24, 2025 - 12:02 AM. Please accept or decline.', '2025-10-24 00:02:26', 1, 201, 'accepted'),
(122, 10, 'Your appointment has been successfully booked!.', '2025-10-24 00:06:49', 1, 201, ''),
(123, 10, 'Your appointment has been successfully booked!.', '2025-10-24 00:06:49', 1, 201, ''),
(124, 10, 'Your appointment has been successfully booked!.', '2025-10-26 20:53:17', 1, 204, ''),
(125, 10, 'Your appointment has been successfully booked!.', '2025-10-27 21:51:04', 1, 203, ''),
(126, 10, 'Your appointment has been successfully booked!.', '2025-11-18 09:00:01', 1, 211, ''),
(127, 10, 'Your appointment has been successfully booked!.', '2025-11-18 09:00:59', 1, 212, ''),
(128, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:05:52', 1, 213, ''),
(129, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:10:13', 1, 182, ''),
(130, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:10:14', 1, 182, ''),
(131, 10, 'Your appointment has been successfully confirmed!.', '2025-11-18 09:10:17', 1, 214, ''),
(132, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:19:54', 1, 215, ''),
(133, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:19:54', 1, 180, ''),
(134, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:19:54', 1, 180, ''),
(135, 10, 'A follow-up appointment has been scheduled for you.', '2025-11-18 09:20:03', 1, 216, ''),
(136, 10, 'Your appointment has been successfully confirmed!.', '2025-11-18 09:21:49', 1, 175, ''),
(137, 10, 'Your appointment has been successfully confirmed!.', '2025-11-18 09:21:49', 1, 175, ''),
(138, 10, 'A follow-up appointment has been scheduled for you.', '2025-11-18 09:22:09', 1, 217, ''),
(139, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:24:04', 1, 175, ''),
(140, 10, 'Your appointment has been rejected by the nurse.', '2025-11-18 09:24:04', 1, 175, ''),
(141, 10, 'Your appointment has been successfully confirmed!.', '2025-11-18 09:24:05', 1, 217, ''),
(142, 10, 'A follow-up appointment has been scheduled for you.', '2025-11-18 09:24:25', 1, 218, ''),
(143, 10, 'Your appointment has been successfully confirmed!.', '2025-11-18 09:24:38', 1, 218, ''),
(144, 10, 'A follow-up appointment has been scheduled for you.', '2025-11-19 13:49:47', 1, 219, ''),
(145, 10, 'Your appointment has been rejected by the nurse.', '2025-11-19 13:57:18', 1, 219, ''),
(146, 10, 'A follow-up appointment has been scheduled for you.', '2025-11-19 15:10:01', 1, 220, '');

-- --------------------------------------------------------

--
-- Table structure for table `student_requests`
--

CREATE TABLE `student_requests` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_visits`
--

CREATE TABLE `student_visits` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `visit_date` datetime NOT NULL,
  `location` varchar(100) DEFAULT NULL,
  `student_name` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `reason` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `med_id` varchar(20) DEFAULT NULL,
  `dosage` varchar(50) DEFAULT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_visits`
--

INSERT INTO `student_visits` (`id`, `student_id`, `visit_date`, `location`, `student_name`, `course`, `reason`, `action_taken`, `med_id`, `dosage`, `quantity`, `remarks`, `created_at`, `updated_at`, `status`) VALUES
(8, 10, '2025-10-26 13:03:00', 'school clinic', 'charlene sinagpulo', 'BSHM 1A', 'headache', 'giving medicine', 'paracetamol12', '10gram', '1', NULL, '2025-10-26 21:05:26', '2025-10-27 23:30:01', 'Completed'),
(9, 21, '2025-10-27 14:40:00', NULL, 'Jonnel Inoc ', 'BSIT 1B', 'arayy koooooo', 'giving medicine', 'paracetamol', '10gram', '1', NULL, '2025-10-27 22:41:07', '2025-10-27 23:35:57', 'Completed'),
(10, 10, '2025-10-27 15:36:00', NULL, 'charlene sinagpulo', 'BSHM 1B', 'done', 'giving medicine', 'paracetamol12', '12grm', '1', NULL, '2025-10-27 23:36:52', '2025-10-27 23:36:56', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_active` timestamp NULL DEFAULT NULL,
  `last_logout` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role_id`, `created_at`, `updated_at`, `last_active`, `last_logout`) VALUES
(2022001, 'Nurse Account', 'nurse@example.com', '$2y$10$fV/LpULOtKe5w8wGuuftz.mKpo3a1I9MQzAD1HLOL25ONxOQ57eFS', 2, '2025-09-16 14:53:34', '2025-11-19 15:13:14', NULL, '2025-11-19 07:13:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nurse_id` (`nurse_id`),
  ADD KEY `appointments_ibfk_1` (`student_id`);

--
-- Indexes for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `appointment_medications`
--
ALTER TABLE `appointment_medications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `clinic_utilization`
--
ALTER TABLE `clinic_utilization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_responders`
--
ALTER TABLE `emergency_responders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_emergency_responders_student_id` (`student_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_appointment_id` (`appointment_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medication_id` (`medication_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `medications`
--
ALTER TABLE `medications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notifications_user` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patient_code` (`patient_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `program_id` (`program_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_requests_student_id` (`student_id`);

--
-- Indexes for table `student_visits`
--
ALTER TABLE `student_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student_visits_student` (`student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `appointment_medications`
--
ALTER TABLE `appointment_medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `clinic_utilization`
--
ALTER TABLE `clinic_utilization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_responders`
--
ALTER TABLE `emergency_responders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medications`
--
ALTER TABLE `medications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `student_notifications`
--
ALTER TABLE `student_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `student_requests`
--
ALTER TABLE `student_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_visits`
--
ALTER TABLE `student_visits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2022002;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`nurse_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD CONSTRAINT `appointment_logs_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);

--
-- Constraints for table `appointment_medications`
--
ALTER TABLE `appointment_medications`
  ADD CONSTRAINT `appointment_medications_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_responders`
--
ALTER TABLE `emergency_responders`
  ADD CONSTRAINT `fk_emergency_responders_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `fk_events_appointment_id` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`medication_id`) REFERENCES `medications` (`id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `medical_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_notifications`
--
ALTER TABLE `student_notifications`
  ADD CONSTRAINT `student_notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_requests`
--
ALTER TABLE `student_requests`
  ADD CONSTRAINT `fk_student_requests_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_visits`
--
ALTER TABLE `student_visits`
  ADD CONSTRAINT `fk_student_visits_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
