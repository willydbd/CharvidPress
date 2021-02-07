-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 08, 2021 at 12:16 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `charvid`
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` bigint(15) NOT NULL,
  `item_name` varchar(300) NOT NULL,
  `item_cat` varchar(300) NOT NULL,
  `unit_price` double(15,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `threshold` int(11) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_name`, `item_cat`, `unit_price`, `stock`, `threshold`, `add_date`) VALUES
(1, 'A4 Paper', 'Stationary', 1300.00, 12, 2, '2019-12-01 18:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(15) NOT NULL,
  `job_id` varchar(300) NOT NULL,
  `client_name` varchar(300) NOT NULL,
  `client_address` varchar(300) NOT NULL,
  `client_phone` varchar(300) NOT NULL,
  `job_description` varchar(300) NOT NULL,
  `staff_in_charge` varchar(300) NOT NULL,
  `order_payload` mediumtext NOT NULL,
  `job_estimate` double(15,2) NOT NULL,
  `handling_charges` double(15,2) NOT NULL,
  `sales_comm` double(15,2) NOT NULL,
  `profit` double(15,2) NOT NULL,
  `discount` double(15,2) NOT NULL,
  `vat` double(15,2) NOT NULL,
  `reimbursement` double(15,2) NOT NULL,
  `last_instalment` double(15,2) NOT NULL DEFAULT 0.00,
  `order_date` datetime NOT NULL,
  `completion_date` datetime NOT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `cancelation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_id`, `client_name`, `client_address`, `client_phone`, `job_description`, `staff_in_charge`, `order_payload`, `job_estimate`, `handling_charges`, `sales_comm`, `profit`, `discount`, `vat`, `reimbursement`, `last_instalment`, `order_date`, `completion_date`, `canceled`, `cancelation_date`) VALUES
(1, '140220-1', 'Testing123', 'addtesting', '09092494024', 'testing all through', 'Champion Baba (CharvidDeveloper)', '[{\"stage_name\":\"Press\",\"processes\":[{\"process_name\":\"Impression On Speed Master 102\",\"qty\":\"4\",\"price\":\"40,000\",\"process_unit\":\"thousand\"}],\"total\":\"40,000\"}]', 40000.00, 0.00, 0.00, 0.00, 0.00, 2000.00, 40000.00, 40000.00, '2020-02-14 15:39:46', '2020-02-14 15:39:46', 0, '0000-00-00 00:00:00'),
(3, '070920-1', 'Testing123', 'addtesting', '09092494024', 'Another job', 'Champion Baba (CharvidDeveloper)', '[{\"stage_name\":\"BUSINESS CENTER\",\"processes\":[{\"process_name\":\"PHOTOCOPY A4 (BLACK)\",\"qty\":\"70\",\"price\":\"700\",\"process_unit\":\"PAGE\"}],\"total\":\"700\"},{\"stage_name\":\"Pre-press\",\"processes\":[{\"process_name\":\"Exposure\",\"qty\":\"80\",\"price\":\"20,000\",\"process_unit\":\"plate\"}],\"total\":\"20,000\"},{\"stage_name\":\"Press\",\"processes\":[{\"process_name\":\"Impression On MO 1 Colour\",\"qty\":\"70\",\"price\":\"70,000\",\"process_unit\":\"thousand\"}],\"total\":\"70,000\"},{\"stage_name\":\"Post-press\",\"processes\":[{\"process_name\":\"Cutting\",\"qty\":\"50\",\"price\":\"2,500\",\"process_unit\":\"blade\"}],\"total\":\"2,500\"}]', 114200.00, 6000.00, 5000.00, 10000.00, 0.00, 5710.00, 114200.00, 34200.00, '2020-09-07 11:21:33', '2020-09-07 11:52:29', 0, '0000-00-00 00:00:00'),
(4, '080920-1', 'Testing123', 'addtesting', '09092494024', 'Another test', 'Champion (willydbd)', '[{\"stage_name\":\"BUSINESS CENTER\",\"processes\":[{\"process_name\":\"PHOTOCOPY A4 (BLACK)\",\"qty\":\"8\",\"price\":\"80\",\"process_unit\":\"PAGE\"},{\"process_name\":\"PHOTOCOPY A4 (COLOUR)\",\"qty\":\"5\",\"price\":\"250\",\"process_unit\":\"PAGE\"}],\"total\":\"330\"}]', 330.00, 0.00, 0.00, 0.00, 0.00, 16.50, 0.00, 0.00, '2020-09-09 12:07:39', '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `job_roles`
--

CREATE TABLE `job_roles` (
  `id` int(4) NOT NULL,
  `staff_id` varchar(300) NOT NULL,
  `take_job_order` tinyint(1) NOT NULL DEFAULT 0,
  `manage_staff` tinyint(1) NOT NULL DEFAULT 0,
  `manage_production` tinyint(1) NOT NULL DEFAULT 0,
  `manage_inventory` tinyint(1) NOT NULL DEFAULT 0,
  `inventory_notification` tinyint(1) NOT NULL DEFAULT 0,
  `inventory_overview` tinyint(1) NOT NULL DEFAULT 0,
  `view_job_orders` tinyint(1) NOT NULL DEFAULT 0,
  `update_pending_jobs` tinyint(1) NOT NULL DEFAULT 0,
  `view_system_log` tinyint(1) NOT NULL DEFAULT 0,
  `view_revenue_overview` tinyint(1) NOT NULL DEFAULT 0,
  `cancel_job_orders` tinyint(1) NOT NULL DEFAULT 0,
  `accept_refund` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `job_roles`
--

INSERT INTO `job_roles` (`id`, `staff_id`, `take_job_order`, `manage_staff`, `manage_production`, `manage_inventory`, `inventory_notification`, `inventory_overview`, `view_job_orders`, `update_pending_jobs`, `view_system_log`, `view_revenue_overview`, `cancel_job_orders`, `accept_refund`) VALUES
(1, 'Champion', 0, 1, 0, 0, 0, 1, 1, 0, 1, 1, 1, 0),
(2, 'CharvidDeveloper', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE `log` (
  `id` bigint(15) NOT NULL,
  `actor` varchar(300) NOT NULL,
  `action` varchar(300) NOT NULL,
  `report` text NOT NULL,
  `when` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `actor`, `action`, `report`, `when`) VALUES
(1, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'Chief Developer\'', '2019-12-01 17:47:50'),
(2, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 17:48:40'),
(3, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 17:55:16'),
(4, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 17:55:19'),
(5, 'CharvidDeveloper', 'LOG_ADD_INVENTORY_ITEM', 'Champion Baba (Chief Developer) added \'A4 Paper\' to inventory under \'Stationary\' category.\n\nPrice: N1300\nStock: 12\nAlert threshold: 2', '2019-12-01 18:09:44'),
(6, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 18:50:31'),
(7, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 19:42:29'),
(8, 'CharvidDeveloper', 'LOG_NEW_PRODUCTION_STAGE', 'Champion Baba (Chief Developer) added new production stage: \'Pre Press\' Rank #1', '2019-12-01 20:05:31'),
(9, 'CharvidDeveloper', 'LOG_PRODUCTION_STAGE_DELETED', 'Champion Baba (Chief Developer) deleted \'Pre Press\' production stage with 0 processes.', '2019-12-01 20:06:01'),
(10, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 20:37:44'),
(11, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:17:34'),
(12, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:18:20'),
(13, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:19:03'),
(14, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:20:05'),
(15, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:20:08'),
(16, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:23:09'),
(17, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:23:30'),
(18, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:23:47'),
(19, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:34:24'),
(20, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:39:21'),
(21, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:40:33'),
(22, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:45:42'),
(23, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:46:11'),
(24, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:48:37'),
(25, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-01 23:48:52'),
(26, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-01 23:54:28'),
(27, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 00:00:11'),
(28, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 00:02:33'),
(29, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 00:04:46'),
(30, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 00:08:55'),
(31, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 00:08:57'),
(32, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 13:39:02'),
(33, 'CharvidDeveloper', 'LOG_STAFF_INFO_UPDATED', 'Champion Baba (Chief Developer) updated staff details for: CharvidDeveloper.\nUpdated staff now has the following permissions: \'take_job_order\', \'view_job_orders\', \'update_pending_jobs\', \'cancel_job_orders\', \'manage_production\', \'manage_staff\', \'manage_travel\', \'manage_inventory\', \'inventory_notification\', \'inventory_overview\', \'view_revenue_overview\', \'view_system_log\'', '2019-12-02 18:16:06'),
(34, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 18:16:11'),
(35, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 18:16:17'),
(36, 'CharvidDeveloper', 'LOG_STAFF_INFO_UPDATED', 'Champion Baba (Chief Developer) updated staff details for: CharvidDeveloper.\nUpdated staff now has the following permissions: \'take_job_order\', \'view_job_orders\', \'update_pending_jobs\', \'cancel_job_orders\', \'manage_production\', \'manage_staff\', \'manage_inventory\', \'inventory_notification\', \'inventory_overview\', \'view_revenue_overview\', \'view_system_log\'', '2019-12-02 18:17:36'),
(37, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 18:17:40'),
(38, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 18:18:03'),
(39, 'CharvidDeveloper', 'LOG_STAFF_INFO_UPDATED', 'Champion Baba (Chief Developer) updated staff details for: CharvidDeveloper.\nUpdated staff now has the following permissions: \'take_job_order\', \'view_job_orders\', \'update_pending_jobs\', \'cancel_job_orders\', \'manage_production\', \'manage_staff\', \'manage_travel\', \'manage_inventory\', \'inventory_notification\', \'inventory_overview\', \'view_revenue_overview\', \'view_system_log\'', '2019-12-02 18:18:23'),
(40, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 18:18:51'),
(41, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 18:19:14'),
(42, 'CharvidDeveloper', 'LOG_STAFF_INFO_UPDATED', 'Champion Baba (Chief Developer) updated staff details for: CharvidDeveloper.\nUpdated staff now has the following permissions: \'manage_staff\', \'manage_travel\'', '2019-12-02 18:19:23'),
(43, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 18:19:26'),
(44, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 18:19:58'),
(45, 'CharvidDeveloper', 'LOG_STAFF_INFO_UPDATED', 'Champion Baba (Chief Developer) updated staff details for: CharvidDeveloper.\nUpdated staff now has the following permissions: \'take_job_order\', \'view_job_orders\', \'update_pending_jobs\', \'cancel_job_orders\', \'manage_production\', \'manage_staff\', \'manage_inventory\', \'inventory_notification\', \'inventory_overview\', \'view_revenue_overview\', \'view_system_log\'', '2019-12-02 18:38:47'),
(46, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-02 18:38:59'),
(47, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 18:39:05'),
(48, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'ANN-IT-01\'', '2019-12-02 19:27:11'),
(49, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-02 19:30:08'),
(50, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-02 19:30:24'),
(51, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 10:39:37'),
(52, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-03 11:05:56'),
(53, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2019-12-03 11:32:10'),
(54, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2019-12-03 12:33:40'),
(55, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 12:53:54'),
(56, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 12:54:33'),
(57, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:00:27'),
(58, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:01:08'),
(59, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:02:25'),
(60, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:17:37'),
(61, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:19:22'),
(62, 'ANN-IT-01', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (ANN-IT-01) - Developer - logged out', '2019-12-03 13:20:11'),
(63, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2019-12-06 13:35:46'),
(64, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'willydbd\'', '2020-01-27 10:02:33'),
(65, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'willydbd\'', '2020-01-27 10:03:18'),
(66, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-01-27 10:03:37'),
(67, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2020-01-27 10:04:34'),
(68, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-02-05 16:24:02'),
(69, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-02-10 16:40:11'),
(70, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-02-12 19:43:31'),
(71, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-02-12 19:46:06'),
(72, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-02-14 15:36:18'),
(73, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-02-14 15:37:18'),
(74, 'CharvidDeveloper', 'LOG_NEW_JOB', 'Champion Baba (Chief Developer) took a new job order with ID \'140220-1\' from Testing123 [testing all through]', '2020-02-14 15:39:46'),
(75, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-02-14 15:40:18'),
(76, 'CharvidDeveloper', 'LOG_RECEIPT_GENERATED', 'Champion Baba (Chief Developer) generated receipt for Order: 140220-1', '2020-02-14 15:40:27'),
(77, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-02-14 15:41:31'),
(78, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-02-14 15:49:12'),
(79, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-02-15 05:18:39'),
(80, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-02-15 05:19:01'),
(81, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-07-25 10:42:10'),
(82, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-07-25 10:44:15'),
(83, 'CharvidDeveloper', 'LOG_RECEIPT_GENERATED', 'Champion Baba (Chief Developer) generated receipt for Order: 140220-1', '2020-07-25 10:44:59'),
(84, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-07-27 11:05:15'),
(85, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2020-07-27 11:55:44'),
(86, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-07-27 11:57:22'),
(87, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2020-07-27 12:19:03'),
(88, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-07-27 12:19:27'),
(89, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged out', '2020-07-27 12:24:39'),
(90, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-07-29 09:36:47'),
(91, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-07-29 18:26:43'),
(92, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'chaviddeveper\'', '2020-08-12 19:47:44'),
(93, '', 'LOG_SYSTEM_ENTRY_EXIT', 'Invalid login username \'chaviddeveloper\'', '2020-08-12 19:47:52'),
(94, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-08-12 19:50:51'),
(95, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-08-23 08:16:17'),
(96, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-08-23 08:17:19'),
(97, 'anne', 'LOG_SYSTEM_ENTRY_EXIT', 'Anne (anne) - Admin Officer - logged out', '2020-09-05 13:52:00'),
(98, 'CharvidDeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (CharvidDeveloper) - Chief Developer - logged in', '2020-09-05 13:54:33'),
(99, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-09-07 11:08:14'),
(100, 'Charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (Charviddeveloper) - Chief Developer - logged in', '2020-09-07 11:08:58'),
(101, 'CharvidDeveloper', 'LOG_NEW_JOB', 'Champion Baba (Chief Developer) took a new job order with ID \'<? $job_id = new_job_id(); echo $job_id ?>\' from Testing123 [another job]', '2020-09-07 11:11:25'),
(102, 'CharvidDeveloper', 'LOG_NEW_JOB', 'Champion Baba (Chief Developer) took a new job order with ID \'070920-1\' from Testing123 [Another job]', '2020-09-07 11:21:34'),
(103, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 070920-1', '2020-09-07 11:51:06'),
(104, 'CharvidDeveloper', 'LOG_NEW_JOB_INSTALMENT', 'Champion Baba (Chief Developer) received new instalment of N34200 for Order: 070920-1 [Another job].\nPayment is now complete', '2020-09-07 11:52:29'),
(105, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-09-07 15:09:43'),
(106, 'CharvidDeveloper', 'LOG_RECEIPT_GENERATED', 'Champion Baba (Chief Developer) generated POS receipt for Order: 140220-1', '2020-09-07 15:11:21'),
(107, 'CharvidDeveloper', 'LOG_RECEIPT_GENERATED', 'Champion Baba (Chief Developer) generated receipt for Order: 140220-1', '2020-09-07 15:11:41'),
(108, 'CharvidDeveloper', 'LOG_INVOICE_RAISED', 'Champion Baba (Chief Developer) raised an invoice for Order: 140220-1', '2020-09-07 15:11:52'),
(109, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-09-08 09:07:02'),
(110, 'willydbd', 'LOG_NEW_JOB', 'Champion (Developer) took a new job order with ID \'080920-1\' from Testing123 [Another test]', '2020-09-09 12:07:39'),
(111, 'willydbd', 'LOG_INVOICE_RAISED', 'Champion (Developer) raised an invoice for Order: 080920-1', '2020-09-09 12:07:44'),
(112, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-09-09 15:34:54'),
(113, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-09-09 15:35:22'),
(114, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - Developer - logged out', '2020-09-16 13:06:28'),
(115, 'charviddeveloper', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion Baba (charviddeveloper) - Chief Developer - logged in', '2020-09-16 13:07:09'),
(116, 'willydbd', 'LOG_SYSTEM_ENTRY_EXIT', 'Champion (willydbd) - ML Engineer - logged out', '2020-12-19 02:28:57');

-- --------------------------------------------------------

--
-- Table structure for table `production_processes`
--

CREATE TABLE `production_processes` (
  `id` int(4) NOT NULL,
  `production_stage` varchar(300) NOT NULL COMMENT 'points to the id column of production_stages',
  `process_name` varchar(300) NOT NULL,
  `process_rate` double(15,2) NOT NULL,
  `process_unit` varchar(300) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production_processes`
--

INSERT INTO `production_processes` (`id`, `production_stage`, `process_name`, `process_rate`, `process_unit`, `add_date`) VALUES
(10, '9', 'Impression On Speed Master 102', 10000.00, 'thousand', '2015-12-04 15:27:59'),
(11, '9', 'Impression On MOVP', 6000.00, 'thousand', '2015-12-04 15:27:59'),
(12, '9', 'Impression On MO 1 Colour', 1000.00, 'thousand', '2015-12-04 15:27:59'),
(13, '9', 'Impression On KORD', 800.00, 'thousand', '2015-12-04 15:27:59'),
(29, '11', 'Cutting', 50.00, 'blade', '2015-12-04 15:34:17'),
(32, '11', 'Folding', 1.00, 'UNIT', '2015-12-04 15:34:17'),
(33, '11', 'Scoring', 1.00, 'unit', '2015-12-04 15:34:17'),
(34, '11', 'Perforating', 500.00, 'thousand', '2015-12-04 15:34:17'),
(35, '11', 'Stitching', 2.00, 'pin', '2015-12-04 15:34:17'),
(36, '11', 'Binding', 30.00, 'book', '2015-12-04 15:34:17'),
(37, '11', 'Padding', 100.00, 'thousand', '2015-12-04 15:34:17'),
(38, '11', 'Numbering', 1000.00, 'thousand', '2015-12-04 15:34:17'),
(40, '11', 'Spot Lamination', 50.00, 'unit', '2015-12-04 15:34:17'),
(41, '11', 'Trimming', 50.00, 'blade', '2015-12-04 15:34:17'),
(42, '11', 'Wire O', 50.00, 'book', '2015-12-04 15:34:17'),
(43, '11', 'Rimming', 50.00, 'unit', '2015-12-04 15:34:17'),
(61, '8', 'Designing', 1500.00, 'page', '2015-12-12 16:13:59'),
(62, '8', 'Layout / D.I.', 200.00, 'page', '2015-12-12 16:13:59'),
(63, '8', 'Page Planning', 300.00, 'page', '2015-12-12 16:13:59'),
(65, '8', 'Exposure', 250.00, 'plate', '2015-12-12 16:13:59'),
(67, '12', 'Heat Transfer', 50.00, 'unit', '2015-12-12 16:29:35'),
(68, '12', 'Mug Heat Transfer', 300.00, 'unit', '2015-12-12 16:29:35'),
(69, '12', 'T-shirt/cap Heat Transfer', 100.00, 'unit', '2015-12-12 16:29:35'),
(70, '12', 'Punching', 100.00, 'thousand', '2015-12-12 16:29:35'),
(71, '12', 'Collating', 1000.00, 'thousand', '2015-12-12 16:29:35'),
(72, '12', 'Jogging', 100.00, 'thousand', '2015-12-12 16:29:35'),
(73, '12', 'Monogramming', 30.00, 'Thousand Stiches', '2015-12-12 16:29:35'),
(75, '8', 'Plate', 250.00, 'page', '2015-12-21 12:04:45'),
(76, '13', '70 gsm bond paper', 7000.00, 'Ream', '2016-01-04 11:20:27'),
(77, '13', '80 gsm bond paper', 7800.00, 'Ream', '2016-01-04 11:20:27'),
(78, '13', '100 gsm bond paper', 11000.00, 'Ream', '2016-01-04 11:20:27'),
(79, '13', '90 gsm art paper', 7000.00, 'Ream', '2016-01-04 11:20:27'),
(80, '13', '115 gsm art paper', 9500.00, 'Ream', '2016-01-04 11:20:27'),
(81, '13', '135 gsm art paper', 10500.00, 'Ream', '2016-01-04 11:20:27'),
(82, '13', '150 gsm art paper', 13000.00, 'Ream', '2016-01-04 11:20:27'),
(83, '13', '150 gsm matt paper', 13000.00, 'Ream', '2016-01-04 11:20:27'),
(84, '13', '170 gsm matt paper', 17500.00, 'Ream', '2016-01-04 11:20:27'),
(85, '14', '200 gsm art card', 5000.00, 'Pack', '2016-01-04 11:24:14'),
(86, '14', '200 gsm matt card', 5500.00, 'Pack', '2016-01-04 11:24:14'),
(87, '14', '220 gsm art card', 7500.00, 'Pack', '2016-01-04 11:24:14'),
(88, '14', '220 gsm matt card', 8000.00, 'Pack', '2016-01-04 11:24:14'),
(89, '14', '250 gsm art card', 9000.00, 'Pack', '2016-01-04 11:24:14'),
(90, '14', '250 gsm matt card', 9500.00, 'Pack', '2016-01-04 11:24:14'),
(91, '14', '3000 gsm art card', 10000.00, 'Pack', '2016-01-04 11:24:14'),
(92, '14', '3000 gsm matt card', 11000.00, 'Pack', '2016-01-04 11:24:14'),
(93, '14', '3500 gsm matt card', 12000.00, 'Pack', '2016-01-04 11:24:14'),
(94, '15', 'PHOTOCOPY A4 (BLACK)', 10.00, 'PAGE', '2016-01-11 10:26:36'),
(95, '15', 'PHOTOCOPY A4 (COLOUR)', 50.00, 'PAGE', '2016-01-11 10:26:36'),
(96, '15', 'PHOTOCOPY A3 (BLACK)', 20.00, 'PAGE', '2016-01-11 10:26:36'),
(97, '15', 'PHOTOCOPY A3 (COLOUR)', 100.00, 'PAGE', '2016-01-11 10:26:36'),
(98, '15', 'PRINTING A4 (BLACK)', 50.00, 'PAGE', '2016-01-11 10:26:36'),
(99, '15', 'PRINTING A4 (COLOUR)', 100.00, 'PAGE', '2016-01-11 10:26:36'),
(100, '15', 'PRINTING A3 (COLOUR)', 250.00, 'PAGE', '2016-01-11 10:26:36'),
(101, '15', 'PRINTING A3 (BLACK)', 100.00, 'PAGE', '2016-01-11 10:26:36'),
(102, '15', 'TYPE SETTING A4', 150.00, 'PAGE', '2016-01-11 10:26:36'),
(103, '15', 'TYPE SETTING A3', 300.00, 'PAGE', '2016-01-11 10:26:36'),
(104, '15', 'LAMINATION A4', 100.00, 'PAGE', '2016-01-11 10:26:36'),
(105, '15', 'LAMINATION A3', 200.00, 'PAGE', '2016-01-11 10:26:36'),
(106, '15', 'LAMINATION I.D CARD', 50.00, 'PAGE', '2016-01-11 10:26:36'),
(107, '15', 'MATT LAMINATION (BULK)', 10.00, 'PAGE', '2016-01-11 10:26:36'),
(108, '15', 'GLOSSY LAMINATION (BULK)', 10.00, 'PAGE', '2016-01-11 10:26:36'),
(109, '15', 'INTERNET BROWSING (30MINS)', 100.00, 'TIME', '2016-01-11 10:26:36'),
(110, '15', 'INTERNET BROWSING (1HOUR)', 150.00, 'TIME', '2016-01-11 10:26:36'),
(111, '15', 'PRINTING OF PLASTIC I.D CARD', 300.00, 'ONE', '2016-01-11 10:26:36'),
(112, '15', 'PRINTING OF PLASTIC I.D CARD (100+)', 250.00, 'ONE', '2016-01-11 10:26:36'),
(113, '15', 'PRINTING OF PLASTIC I.D CARD (1000+)', 200.00, 'ONE', '2016-01-11 10:26:36'),
(114, '15', 'I.D CARD DESIGN', 1000.00, 'ONE', '2016-01-11 10:26:36'),
(115, '15', 'COMPLIMENTARY CARD DESIGN', 1500.00, 'ONE', '2016-01-11 10:26:36'),
(116, '15', 'TABLE TYPE SETTING A4', 200.00, 'PAGE', '2016-01-11 10:26:36'),
(117, '15', 'TABLE TYPE SETTING A3', 400.00, 'PAGE', '2016-01-11 10:26:36'),
(118, '15', 'PRINTING ON CONQUEROR', 150.00, 'PAGE', '2016-01-11 10:26:36'),
(119, '15', 'COLOUR PRINTING A4 FRONT & BACK', 200.00, 'PAGE', '2016-01-11 10:26:36'),
(120, '15', 'COLOUR PRINTING A3 FRONT & BACK', 400.00, 'PAGE', '2016-01-11 10:26:36'),
(121, '15', 'LETTERHEAD DESIGN', 1500.00, 'PAGE', '2016-01-11 10:26:36'),
(122, '15', 'SPIRAL BINDING A4 (SMALL BULK)', 200.00, 'BULK', '2016-01-11 10:26:36'),
(123, '15', 'SPIRAL BINDING A3 (MEDIUM BULK)', 400.00, 'BULK', '2016-01-11 10:26:36'),
(124, '15', 'SPIRAL BINDING  (LARGE BULK)', 800.00, 'BULK', '2016-01-11 10:26:36'),
(125, '15', 'SPIRAL BINDING  (OTHERS)', 1000.00, 'BULK', '2016-01-11 10:26:36'),
(126, '15', 'SCAN A4', 100.00, 'PAGE', '2016-01-11 10:26:36'),
(127, '15', 'SCAN & SEND A4', 200.00, 'PAGE', '2016-01-11 10:26:36'),
(128, '15', 'SCAN A3', 200.00, 'PAGE', '2016-01-11 10:26:36'),
(129, '11', 'Dia Cutting', 500.00, 'BLADE', '2016-01-13 16:47:44'),
(130, '8', 'Colour Separation (Single)', 1000.00, 'Colour', '2016-01-13 16:51:40'),
(131, '8', 'Colour Separation (Full)', 4000.00, 'Set', '2016-01-13 16:51:40'),
(132, '8', 'A4 Card Front Only', 50.00, 'Paper', '2016-01-13 17:06:54'),
(133, '8', 'A4 Card Front/Back', 100.00, 'Paper', '2016-01-13 17:06:54'),
(134, '8', 'A4 Paper Front Only', 40.00, 'Paper', '2016-01-13 17:06:54'),
(135, '8', 'A4 Paper Front/Back', 80.00, 'Paper', '2016-01-13 17:06:54'),
(136, '8', 'A4 Card&Paper with Solid Front Only', 80.00, 'Paper', '2016-01-13 17:06:54'),
(137, '8', 'A4 Card&Paper with Solid Front/Back ', 160.00, 'Paper', '2016-01-13 17:06:54'),
(138, '8', 'A3 Card/Paper Front Only', 80.00, 'Paper', '2016-01-13 17:13:39'),
(139, '8', 'A3 Card/Paper Front&Back', 160.00, 'Paper', '2016-01-13 17:13:39'),
(140, '8', 'A3 Card/Paper with Solid Front Only', 100.00, 'Paper', '2016-01-13 17:13:39'),
(141, '8', 'A3 Card/Paper with Solid Front&Back', 200.00, 'Paper', '2016-01-13 17:13:39'),
(154, '15', 'PLASTIC I.D CARD (Others)', 500.00, 'ONE', '2016-01-13 17:33:45'),
(155, '9', 'Flex Print', 80.00, 'Square Feet', '2016-01-13 17:33:57'),
(156, '9', 'Sticker (SAV)', 100.00, 'Square Feet', '2016-01-13 17:33:57'),
(157, '9', 'Window Graphics', 300.00, 'Square Feet', '2016-01-13 17:33:57'),
(158, '11', 'Matt/Glossy Lamination A4 Front Only', 10.00, 'Unit', '2016-01-13 17:34:13'),
(159, '11', 'Matt/Glossy Lamination A4 Front&Back', 20.00, 'Unit', '2016-01-13 17:34:13'),
(160, '11', 'Matt/Glossy Lamination A3 Front Only', 20.00, 'Unit', '2016-01-13 17:34:13'),
(161, '11', 'Matt/Glossy Lamination A3 Front&Back', 40.00, 'Unit', '2016-01-13 17:34:13'),
(162, '11', 'Matt/Glossy Lamination A2 Front Only', 40.00, 'Unit', '2016-01-13 17:34:13'),
(163, '11', 'Matt/Glossy Lamination A2 Front&Back', 80.00, 'Unit', '2016-01-13 17:34:13'),
(165, '15', 'EXCEL TYPESETTING', 300.00, 'PAGE', '2016-01-13 17:44:48'),
(166, '13', '70 gsm bond paper ', 14.00, 'Sheet', '2016-01-13 20:13:34'),
(167, '13', '80 gsm bond paper ', 16.00, 'Sheet', '2016-01-13 20:13:34'),
(168, '13', '100 gsm bond paper ', 22.00, 'Sheet', '2016-01-13 20:13:34'),
(169, '13', '90 gsm art paper ', 14.00, 'Sheet', '2016-01-13 20:13:34'),
(170, '13', '115 gsm art paper ', 19.00, 'Sheet', '2016-01-13 20:13:34'),
(171, '13', '135 gsm art paper ', 21.00, 'Sheet', '2016-01-13 20:13:34'),
(172, '13', '150 gsm art/matt paper ', 26.00, 'Sheet', '2016-01-13 20:13:34'),
(173, '13', '170 gsm art/matt paper ', 35.00, 'Sheet', '2016-01-13 20:13:34'),
(174, '14', '200 gsm art card â€‚', 50.00, 'Card', '2016-01-13 20:20:14'),
(175, '14', '200 gsm matt card â€‚', 55.00, 'Card', '2016-01-13 20:20:14'),
(176, '14', '220 gsm art card â€‚', 75.00, 'Card', '2016-01-13 20:20:14'),
(177, '14', '220 gsm matt card â€‚', 80.00, 'Card', '2016-01-13 20:20:14'),
(178, '14', '250 gsm art card â€‚', 90.00, 'Card', '2016-01-13 20:20:14'),
(179, '14', '250 gsm matt card â€‚', 95.00, 'Card', '2016-01-13 20:20:14'),
(180, '14', '300 gsm art card â€‚', 100.00, 'Card', '2016-01-13 20:20:14'),
(181, '14', '300 gsm matt card â€‚', 110.00, 'Card', '2016-01-13 20:20:14'),
(182, '14', '350 gsm matt card â€‚', 120.00, 'Card', '2016-01-13 20:20:14'),
(185, '11', 'Foiling (Pack)', 2000.00, 'Pack', '2016-01-15 14:10:56'),
(186, '11', 'Embossing (Pack)', 3000.00, 'Pack', '2016-01-15 14:10:56'),
(187, '11', 'Foiling  (Ream)', 10000.00, 'Ream', '2016-01-15 14:10:56'),
(188, '11', 'Embossing  (Ream)', 15000.00, 'Ream', '2016-01-15 14:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `production_stages`
--

CREATE TABLE `production_stages` (
  `id` int(4) NOT NULL,
  `stage_name` varchar(300) NOT NULL,
  `stage_rank` int(4) NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production_stages`
--

INSERT INTO `production_stages` (`id`, `stage_name`, `stage_rank`, `add_date`) VALUES
(8, 'Pre-press', 1, '2015-11-30 12:28:24'),
(9, 'Press', 2, '2015-12-04 14:36:00'),
(11, 'Post-press', 3, '2015-12-04 15:20:24'),
(12, 'Others', 4, '2015-12-04 15:53:39'),
(13, 'PAPER', 5, '2016-01-04 11:10:31'),
(14, 'CARD', 6, '2016-01-04 11:11:01'),
(15, 'BUSINESS CENTER', 1, '2016-01-11 09:49:45');

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

CREATE TABLE `refund` (
  `id` bigint(15) NOT NULL,
  `amount` double(15,2) NOT NULL,
  `staff` varchar(300) NOT NULL,
  `purpose` text NOT NULL,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(4) NOT NULL,
  `staff_name` varchar(300) NOT NULL,
  `staff_role` varchar(300) NOT NULL,
  `staff_id` varchar(300) NOT NULL,
  `staff_pswd` varchar(300) NOT NULL,
  `session_token` varchar(300) NOT NULL,
  `is_permanent` tinyint(1) NOT NULL DEFAULT 0,
  `add_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_name`, `staff_role`, `staff_id`, `staff_pswd`, `session_token`, `is_permanent`, `add_date`) VALUES
(1, 'Champion Baba', 'Chief Developer', 'CharvidDeveloper', 'champion', '522634aa78d80731c1fd76b0ad308711f7f68818', 1, '2015-12-12 12:30:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_roles`
--
ALTER TABLE `job_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_processes`
--
ALTER TABLE `production_processes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `production_stages`
--
ALTER TABLE `production_stages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refund`
--
ALTER TABLE `refund`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uid` (`staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_roles`
--
ALTER TABLE `job_roles`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `id` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `production_processes`
--
ALTER TABLE `production_processes`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `production_stages`
--
ALTER TABLE `production_stages`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `refund`
--
ALTER TABLE `refund`
  MODIFY `id` bigint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
