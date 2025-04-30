-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 19, 2025 at 03:00 PM
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
-- Database: `interndb`
--

-- --------------------------------------------------------
create Database `interndb`
--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `student_id`, `status`, `created_at`) VALUES
(1, 1, 'Pending', '2025-04-14 14:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `company_name`, `company_address`, `registration_number`, `created_at`) VALUES
(1, 'Econet', '2965GunhilExtensionSanyati', 'econet.arda', '2025-04-14 12:53:50'),
(2, 'Econet', '2965GunhilExtensionSanyati', 'econet.arda', '2025-04-14 12:58:34'),
(3, 'CABS', 'zionChristian2023', 'C1007', '2025-04-15 08:24:57'),
(4, 'TalexComps', 'Belvedere, Zw', 'C230987y', '2025-04-16 07:52:14'),
(5, 'TalexComps', 'Belvedere, Zw', 'C230987y', '2025-04-16 07:58:24'),
(6, 'UZ', 'mt pleasant', 'c456790h', '2025-04-16 08:57:13'),
(7, 'UZ', 'mt pleasant', 'c456790h', '2025-04-16 09:00:22'),
(8, 'ComputerAt@Cyber', 'Harare', 'h2345y', '2025-04-16 10:16:17'),
(9, 'ComputerAt@Cyber', 'Harare', 'h2345y', '2025-04-16 10:47:06'),
(10, 'ExtremeRetHatsAcademy', 'Belvedere, Zw', 'g24567u', '2025-04-16 10:47:49'),
(11, 'ExtremeRetHatsAcademy', 'Belvedere, Zw', 'g24567u', '2025-04-16 10:49:07'),
(12, 'HITSPECIALS45', 'Belvedere, Zw', 'S56789y', '2025-04-16 10:49:41'),
(13, 'HITSPECIALS45', 'Belvedere, Zw', 'S56789y', '2025-04-16 10:52:16'),
(14, 'TechZim', '25Mail 1Street Bulawayo', 'z50000g1', '2025-04-16 10:53:12'),
(15, 'TechZim', '25Mail 1Street Bulawayo', 'z50000g1', '2025-04-16 10:54:07'),
(16, 'ChivhayoTech', 'Borrodale, Harare', 'w789375y', '2025-04-16 10:54:56'),
(17, 'ChivhayoTech', 'Borrodale, Harare', 'w789375y', '2025-04-16 10:57:32'),
(18, 'ChivhayoTech', 'Borrodale, Harare', 'w789375y', '2025-04-16 10:59:32');

-- --------------------------------------------------------

--
-- Table structure for table `interncv`
--

CREATE TABLE `interncv` (
  `ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `ID` bigint(10) NOT NULL,
  `regnumber` varchar(8) NOT NULL,
  `password` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`ID`, `regnumber`, `password`) VALUES
(1, 'h230725y', 'Hit@2023Inta'),
(2, 'h230702e', 'Hit@2023Inta'),
(3, 'h230768g', 'Hit@2023Inta'),
(4, 'h230009a', 'Hit@2023Inta');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `student_id`, `message`, `created_at`) VALUES
(1, 1, 'New internship opportunity posted: Cybersecurity Intern', '2025-04-14 14:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `opportunities`
--

CREATE TABLE `opportunities` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Available','Applied') DEFAULT 'Available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `opportunities`
--

INSERT INTO `opportunities` (`id`, `student_id`, `title`, `description`, `status`, `created_at`) VALUES
(1, 1, 'Cybersecurity Intern', 'An exciting opportunity to work on cybersecurity projects.', 'Available', '2025-04-14 14:25:45');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(6) UNSIGNED NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `cv_file` varchar(255) DEFAULT NULL,
  `web_design` int(3) DEFAULT NULL,
  `website_markup` int(3) DEFAULT NULL,
  `one_page` int(3) DEFAULT NULL,
  `mobile_template` int(3) DEFAULT NULL,
  `backend_api` int(3) DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile_creation`
--

CREATE TABLE `profile_creation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cv_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`cv_data`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentcv`
--

CREATE TABLE `studentcv` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `university` varchar(255) NOT NULL,
  `program` varchar(255) NOT NULL,
  `expected_graduation` date NOT NULL,
  `skills` text NOT NULL,
  `interests` text NOT NULL,
  `work_experience` text NOT NULL,
  `certifications` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studentcv`
--

INSERT INTO `studentcv` (`id`, `full_name`, `email`, `phone_number`, `university`, `program`, `expected_graduation`, `skills`, `interests`, `work_experience`, `certifications`, `created_at`) VALUES
(1, 'RONIOUS TANAKA CHAPEYAMA', 'h230725y@hit.ac.zw', '0780951550', 'Harare Institute of Technology', 'Information Security & Assurance', '2027-12-31', 'geo', 'yyyfyfyuy', 'hjdsvdsudsua', 'hsdsdusdu', '2025-04-14 13:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_complete` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `full_name`, `email`, `phone_number`, `profile_complete`, `created_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', '123-456-7890', 75, '2025-04-14 14:25:44'),
(2, 'CHAPEYAMA RONIOUS TANAKA', 'h230725y@hit.ac.zw', NULL, 0, '2025-04-15 03:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `studentsdata`
--

CREATE TABLE `studentsdata` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `profile_complete` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_portal`
--

CREATE TABLE `student_portal` (
  `ID` int(11) NOT NULL,
  `REG NUMBER` varchar(8) NOT NULL,
  `profile_creation` varchar(100) NOT NULL,
  `studentCV` varchar(1000) NOT NULL,
  `tblinternship_applications` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `fullname` text NOT NULL,
  `USERNAME` text NOT NULL,
  `email` varchar(20) NOT NULL,
  `contactnumber` int(15) NOT NULL,
  `password` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `fullname`, `USERNAME`, `email`, `contactnumber`, `password`) VALUES
(1, 'DR CHAPEYAMA RONIOUS', 'ronious123', 'ronious@yahoo.com', 783463148, 'Admin1'),
(2, 'DR WILTON MUZAVA', 'muzava123', 'wmuzava@gmail.com', 775312514, 'Admin2'),
(3, 'DR MUTSIKWI', 'timothy123', 'mutsikwitimothy@gmai', 0, 'Admin3'),
(4, 'DR B NYARUVIRO', 'bnyaruviro', 'bnyaruviro@gmail.com', 0, 'Admin4');

-- --------------------------------------------------------

--
-- Table structure for table `tblavailable_internships`
--

CREATE TABLE `tblavailable_internships` (
  `InternshipID` int(11) NOT NULL,
  `Position` varchar(100) NOT NULL,
  `Duration` varchar(50) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `GenderPreference` varchar(10) DEFAULT NULL,
  `Status` varchar(20) DEFAULT 'available',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblinternship_applications`
--

CREATE TABLE `tblinternship_applications` (
  `ApplicationID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `MobileNumber` varchar(15) NOT NULL,
  `Address` text NOT NULL,
  `Position` varchar(100) NOT NULL,
  `Duration` varchar(50) NOT NULL,
  `Department` varchar(100) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `Status` varchar(20) DEFAULT 'pending',
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` bigint(10) NOT NULL,
  `fullname` varchar(25) NOT NULL,
  `regnumber` varchar(8) NOT NULL,
  `email` varchar(20) NOT NULL,
  `password` varchar(15) NOT NULL,
  `phonenumber` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `fullname`, `regnumber`, `email`, `password`, `phonenumber`) VALUES
(1, ' MAPEPETA  BEN T.', 'h230234z', 'h230234z@hit.ac.zw', 'Hit@2023Intake', ''),
(2, 'BRIAN T. KONJI', 'h230087a', 'h230087a@hit.ac.zw', 'Hit@2023Intake', ''),
(3, 'VONGAI KUNDISHORa', 'h230693f', 'h230693f2@hit.ac.zw', 'Hit@2023Intake', ''),
(8, 'CEBILLEH MAJAJA', 'h230288j', 'h230288j@hit.ac.zw', 'Hit@2023Intake', ''),
(9, 'CHAPEYAMA RONIOUS TANAKA', 'h230725y', 'h230725y@hit.ac.zw', 'Hit@2023Intake', ''),
(10, 'CHARLENE L. SARAI', 'h230071a', 'h230071a@hit.ac.zw', 'Hit@2023Intake', ''),
(11, 'CRISPEN MUTAURWA', 'h230673g', 'h230673g@hit.ac.zw', 'Hit@2023Intake', ''),
(12, 'DONALD WAENI', 'h230769j', 'h230769j@hit.ac.zw', 'Hit@2023Intake', ''),
(13, 'DOKA SEON T.', 'h230719c', 'h230719c@hit.ac.zw', 'Hit@2023Intake', ''),
(14, 'ESTHER T. DODO', 'h230765a', 'h230765a@hit.ac.zw', 'Hit@2023Intake', ''),
(15, 'RUNESU FAITH', 'h230278r', 'h230278r@hit.ac.zw', 'Hit@2023Intake', ''),
(16, ' MABASA FELICIA', 'h230366z', 'h230366z@hit.ac.zw', 'Hit@2023Intake', ''),
(17, 'HAMZAH DAWOOD', 'h230354t', 'h230354t@hit.ac.zw', 'Hit@2023Intake', ''),
(18, 'KUTENDA BELIEVE SAMAKANDE', 'h230617g', 'h230617g@hit.ac.zw', 'Hit@2023Intake', ''),
(19, 'MAIBHEKA ANOTIDAISHE', 'h230702e', 'h230702e@hit.ac.zw', 'Hit@2023Intake', ''),
(20, 'MAKATIDA NGWERUME', 'h230006g', 'h230006g@hit.ac.zw', 'Hit@2023Intake', ''),
(21, 'MEMORY MATARE', 'h230003m', 'h230003m@hit.ac.zw', 'Hit@2023Intake', ''),
(22, 'MUCHENJE RUVARASHE', 'h230698h', 'h230698h@hit.ac.zw', 'Hit@2023Intake', ''),
(23, 'MUFARO TERERA', 'h230691h', 'h230691h@hit.ac.zw', 'Hit@2023Intake', ''),
(24, 'MUNYANYI DESIRE', 'h230684m', 'h230684m@hit.ac.zw', 'Hit@2023Intake', ''),
(25, 'MUSARAH KRISHNA', 'h230683n', 'h230683n@hit.ac.zw', 'Hit@2023Intake', ''),
(26, 'MUSEMWA FANNUEL', 'h230696w', 'h230696w@hit.ac.zw', 'Hit@2023Intake', ''),
(27, 'MUSVUURWA RUFARO CHANNYAR', 'h230768g', 'h230487b@hit.ac.zw', 'Hit@2023Intake', ''),
(28, 'MUTYORAMWENDO NOTHANDO', 'h230487b', 'h230487b@hit.ac.zw', 'Hit@2023Intake', ''),
(29, 'NIGEL MUPIRA', 'h230828v', 'h230828v@hit.ac.zw', 'Hit@2023Intake', ''),
(30, 'PANASHE MUDHOKWANI', 'h230009a', 'h230009a@hit.ac.zw', 'Hit@2023Intake', ''),
(31, 'PRECIOUS R. CHARLIE', 'h230407r', 'h230407r@hit.ac.zw', 'Hit@2023Intake', ''),
(32, 'PRINCESS SHUMBA', 'h230764f', 'h230764f@hit.ac.zw', 'Hit@2023Intake', ''),
(33, 'PROSPER MUNONOKI', 'h230729r', 'h230729r@hit.ac.zw', 'Hit@2023Intake', ''),
(34, 'ROPAFADZO MANDIMIKA', 'h230048n', 'h230048n@hit.ac.zw', 'Hit@2023Intake', ''),
(35, 'SAMUNDA GABRIEL', 'h230770f', 'h230770f@hit.ac.zw', 'Hit@2023Intake', ''),
(36, 'STANLEY KANEMBIRRIRA', 'h230755w', 'h230755w@hit.ac.zw', 'Hit@2023Intake', ''),
(37, 'TADIWANASHE KAPFIDZE D.', 'h230762x', 'h230762x@hit.ac.zw', 'Hit@2023Intake', ''),
(38, 'TAFADZWA A. KUDUMBA', 'h230638m', 'h230638m@hit.ac.zw', 'Hit@2023Intake', ''),
(39, 'TANAKA NHEKAIRO S', 'h230261h', 'h230261h@hit.ac.zw', 'Hit@2023Intake', ''),
(40, 'TATENDA E. MUCHERI', 'h230181p', 'h230181p@hit.ac.zw', 'Hit@2023Intake', ''),
(41, 'TAWANANYASHA MATINGO', 'h230761j', 'h230761j@hit.ac.zw', 'Hit@2023Intake', ''),
(42, 'TINOTENDA CHIMINA', 'h230694j', 'h230694j@hit.ac.zw', 'Hit@2023Intake', ''),
(43, 'TIRIVASHE CHITANDA', 'h230708z', 'h230708z@hit.ac.zw', 'Hit@2023Intake', ''),
(44, 'TIMOTHY MUTSIKWI', 'h230298e', 'h230298e@hit.ac.zw', 'Hit@2023Intake', ''),
(45, 'TRUST MPINYURI', 'h230351x', 'h230351x@hit.ac.zw', 'Hit@2023Intake', ''),
(46, 'VALERIA JACHI', 'h230357w', 'h230357w@hit.ac.zw', 'Hit@2023Intake', ''),
(47, 'CHIMOTO CHIMWANZA', 'H230999F', 'chimoto@hit.ac.zw', '$2y$10$GGiZ7/C5', '+263780951550'),
(48, 'Mavuchi Domain', 'H230456F', 'h230456f@gmail.com', '$2y$10$0lJc5ATi', '+263780951550'),
(49, 'MANGWIRO THOMAS', 'H230987Y', 'THOMHAS@HIT.AC.ZW', '$2y$10$ldcCgLU5', '+263780951550'),
(50, 'KATERA ALICE', 'H250796J', 'alie@hit.ac.zw', '$2y$10$ObEFyWYe', '899929e292'),
(51, 'rufaro c', 'h230769g', 'rue.shsha@gmail.com', '$2y$10$LvoAnxi3', '+263719310372'),
(52, 'tatenda', 'h300000h', 'h300000h@hit.ac.zw', '$2y$10$dW8KgdpY', '+263780951550'),
(53, 'hhyvuiv', 'yfyfy779', 'h@gmail.com', '$2y$10$ierREoX/', 'h7t5556666');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interncv`
--
ALTER TABLE `interncv`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profile_creation`
--
ALTER TABLE `profile_creation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `studentcv`
--
ALTER TABLE `studentcv`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `studentsdata`
--
ALTER TABLE `studentsdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_portal`
--
ALTER TABLE `student_portal`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `password` (`password`);

--
-- Indexes for table `tblavailable_internships`
--
ALTER TABLE `tblavailable_internships`
  ADD PRIMARY KEY (`InternshipID`);

--
-- Indexes for table `tblinternship_applications`
--
ALTER TABLE `tblinternship_applications`
  ADD PRIMARY KEY (`ApplicationID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `ID` (`ID`),
  ADD KEY `fullname` (`fullname`),
  ADD KEY `email` (`email`),
  ADD KEY `password` (`password`);
ALTER TABLE `users` ADD FULLTEXT KEY `regnumber` (`regnumber`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `interncv`
--
ALTER TABLE `interncv`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `ID` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `opportunities`
--
ALTER TABLE `opportunities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profile_creation`
--
ALTER TABLE `profile_creation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `studentcv`
--
ALTER TABLE `studentcv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `studentsdata`
--
ALTER TABLE `studentsdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_portal`
--
ALTER TABLE `student_portal`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblavailable_internships`
--
ALTER TABLE `tblavailable_internships`
  MODIFY `InternshipID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblinternship_applications`
--
ALTER TABLE `tblinternship_applications`
  MODIFY `ApplicationID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `opportunities`
--
ALTER TABLE `opportunities`
  ADD CONSTRAINT `opportunities_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
