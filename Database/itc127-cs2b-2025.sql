-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 04:19 PM
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
-- Database: `itc127-cs2b-2025`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`username`, `password`, `usertype`, `status`, `createdby`, `datecreated`) VALUES
('admin', '123456', 'ADMINISTRATOR', 'ACTIVE', 'admin', '03/09/2025'),
('staff', '123456', 'STAFF', 'ACTIVE', 'admin', '03/09/2025'),
('tech2', '123456', 'TECHNICAL', 'ACTIVE', 'admin', '25/03/2025'),
('technical', '123456', 'TECHNICAL', 'ACTIVE', 'admin', '03/09/2025'),
('user', '123456', 'USER', 'ACTIVE', 'admin', '24/03/2025');

-- --------------------------------------------------------

--
-- Table structure for table `tblequipments`
--

CREATE TABLE `tblequipments` (
  `AssetNumber` varchar(50) NOT NULL,
  `SerialNumber` varchar(50) NOT NULL,
  `Type` varchar(20) NOT NULL,
  `Manufacturer` varchar(30) NOT NULL,
  `YearModel` varchar(20) NOT NULL,
  `Description` varchar(200) NOT NULL,
  `Branch` varchar(50) NOT NULL,
  `Department` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `Createdby` varchar(20) NOT NULL,
  `DateCreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblequipments`
--

INSERT INTO `tblequipments` (`AssetNumber`, `SerialNumber`, `Type`, `Manufacturer`, `YearModel`, `Description`, `Branch`, `Department`, `Status`, `Createdby`, `DateCreated`) VALUES
('AU-EEM-2024VT21', '2024-EEM-123ABC', 'PROJECTOR', 'Asus', '2023', 'This is the description of this equipment.', 'JOSE RIZAL CAMPUS', 'SCHOOL OF HOSPITALITY AND TOURISM MANAGEMENT', 'WORKING', 'admin', '2025-03-15'),
('AU-EM-2024-403002', 'SN-2024-DEF143', 'MONITOR', 'Asus', '2024', 'superb image quality, flexible connectivity, and great ergonomics.', 'JOSE RIZAL CAMPUS', 'SCHOOL OF BUSINESS ADMINISTRATION', 'WORKING', 'admin', '2025-03-14'),
('AU-EM-202403001', 'SN-202403-ABC1564', 'MONITOR', 'Lenovo', '2024', 'Business-oriented desktop computers with mid-range to high-end processors.', 'JUAN SUMULONG CAMPUS', 'SCHOOL OF COMPUTER STUDIES', 'WORKING', 'admin', '2025-03-14'),
('AU-JSC-202101572', 'AU-EM-2021678', 'MAC', 'Apple', '2021', 'a family of personal computers designed and marketed by Apple since 1984.', 'JUAN SUMULONG CAMPUS', 'SCHOOL OF COMPUTER STUDIES', 'WORKING', 'admin', '2025-03-14');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(20) NOT NULL,
  `timelog` varchar(20) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `performedto` varchar(50) NOT NULL,
  `performedby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbltickets`
--

CREATE TABLE `tbltickets` (
  `TicketNumber` varchar(50) NOT NULL,
  `Problem` varchar(200) NOT NULL,
  `Details` varchar(200) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `Createdby` varchar(20) NOT NULL,
  `DateCreated` varchar(20) NOT NULL,
  `AssignedTo` varchar(20) NOT NULL,
  `DateAssigned` varchar(20) NOT NULL,
  `DateCompleted` varchar(20) NOT NULL,
  `ApprovedBy` varchar(20) NOT NULL,
  `DateApproved` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltickets`
--

INSERT INTO `tbltickets` (`TicketNumber`, `Problem`, `Details`, `Status`, `Createdby`, `DateCreated`, `AssignedTo`, `DateAssigned`, `DateCompleted`, `ApprovedBy`, `DateApproved`) VALUES
('20250325110634', 'Connection', 'sample problem', 'Pending', 'user', '2025-03-25 11:06:34', '', '', '', '', ''),
('20250326010517', 'Software', 'sample', 'Pending', 'user', '2025-03-26 01:05:17', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tblequipments`
--
ALTER TABLE `tblequipments`
  ADD PRIMARY KEY (`AssetNumber`,`SerialNumber`,`Type`,`Department`);

--
-- Indexes for table `tbltickets`
--
ALTER TABLE `tbltickets`
  ADD PRIMARY KEY (`TicketNumber`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
