-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2025 at 07:49 PM
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
-- Database: `optics_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`) VALUES
(8, 'Glasses', 'Amazing glasses that can see through walls like x-ray vision.'),
(9, 'Tint', 'Can darken your eyesight and lower the symptoms of stigmatism.'),
(10, 'Polarized Glasses', 'Can see underwater'),
(11, 'frame', 'frame blue');

-- --------------------------------------------------------

--
-- Table structure for table `frame`
--

CREATE TABLE `frame` (
  `Frame_ID` int(11) NOT NULL,
  `Frame_Name` varchar(255) NOT NULL,
  `Frame_Brand` varchar(255) DEFAULT NULL,
  `Frame_Material` varchar(255) DEFAULT NULL,
  `Frame_Style` varchar(255) DEFAULT NULL,
  `Frame_Size` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `frame`
--

INSERT INTO `frame` (`Frame_ID`, `Frame_Name`, `Frame_Brand`, `Frame_Material`, `Frame_Style`, `Frame_Size`) VALUES
(1, 'dasd', 'sda', 'dad', 'asda', 'dad'),
(2, 'da', 'dasd', 'dad', 'da', 'da'),
(3, 's', 's', 's', 's', 's'),
(4, 'Miko', 'Frame', 'Metal', 'Maangas', '11'),
(5, 'eocircle', 'eo', 'fabric', 'ciRCLE', '11');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `Item_ID` int(11) NOT NULL,
  `Category_ID` int(11) DEFAULT NULL,
  `Item_Name` varchar(255) NOT NULL,
  `Quantity_In_Stock` int(11) DEFAULT 0,
  `Purchase_Price` decimal(10,2) DEFAULT NULL,
  `Selling_Price` decimal(10,2) DEFAULT NULL,
  `Reorder_Level` int(11) DEFAULT 10,
  `Supplier_ID` int(11) DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lens_type`
--

CREATE TABLE `lens_type` (
  `Lens_Type_ID` int(11) NOT NULL,
  `Lens_Type_Name` varchar(255) NOT NULL,
  `Lens_Material` varchar(255) DEFAULT NULL,
  `Lens_Coating` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lens_type`
--

INSERT INTO `lens_type` (`Lens_Type_ID`, `Lens_Type_Name`, `Lens_Material`, `Lens_Coating`) VALUES
(1, 's', 's', 'dad'),
(2, 'Tint', 'Glass', 'Darkin');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `Patient_ID` int(11) NOT NULL,
  `First_Name` char(255) NOT NULL,
  `Middle_Name` char(255) DEFAULT NULL,
  `Last_Name` char(255) NOT NULL,
  `Birthdate` date DEFAULT NULL,
  `Gender` enum('Male','Female','Other') NOT NULL,
  `Phone_Number` int(15) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updated_At` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`Patient_ID`, `First_Name`, `Middle_Name`, `Last_Name`, `Birthdate`, `Gender`, `Phone_Number`, `Email`, `Address`, `Created_At`, `Updated_At`) VALUES
(5, 'justine', 'ventura', 'bacurin', '2025-07-08', 'Male', 912395106, NULL, '1151 torres bugallon st. tondo manila', '2025-07-07 22:39:26', '2025-07-07 22:39:26'),
(8, 'ad', 'sfg', 'dag', '2000-08-07', 'Male', 8, NULL, 'dagd', '2025-07-10 20:08:28', '2025-07-10 20:08:28'),
(9, 'dad', 'da', 'da', '2002-05-04', 'Female', 0, NULL, 'asda', '2025-07-10 20:09:17', '2025-07-10 20:09:17'),
(10, 'jsut', 'sdaduf', 'ausdu', '1984-05-08', 'Male', 949149, NULL, 'jasfj', '2025-07-10 20:12:00', '2025-07-10 20:12:00'),
(11, 'sadaj', 'dajdh', 'dahdah', '2383-09-02', 'Female', 912395106, NULL, 'dhadh', '2025-07-10 20:12:39', '2025-07-10 20:12:39'),
(12, 'justi', 'saddj', 'dasjdj', '1957-04-08', 'Female', 0, NULL, 'dajdj', '2025-07-10 20:13:34', '2025-07-10 20:13:34'),
(13, 'jdi', 'dafjmk', 'dsh', '1984-08-09', 'Male', 0, NULL, 'cahdh', '2025-07-10 20:14:23', '2025-07-10 20:14:23'),
(14, 'JUstinwqh', 'sdafh', 'gfdl', '7128-05-09', 'Female', 0, NULL, 'dasd', '2025-07-10 20:15:45', '2025-07-10 20:15:45'),
(15, 'jsajf', 'fsdjfj', 'ajdj', '2005-02-08', 'Male', 0, NULL, 'adjj', '2025-07-10 20:16:33', '2025-07-10 20:16:33'),
(16, 'adas', 'asjdsa', 'dajdj', '2068-08-09', 'Male', 0, NULL, 'dajdj', '2025-07-10 20:17:07', '2025-07-10 20:17:07'),
(17, 'ralph', 'A', 'Dela Rosa', '2001-02-01', 'Female', 0, NULL, 'dawg', '2025-07-10 20:17:58', '2025-07-11 21:13:01'),
(18, 'justine', 'ventura', 'bacurin', '2025-07-17', 'Female', 0, NULL, 'qew', '2025-07-10 20:18:33', '2025-07-10 20:18:33'),
(19, 'justine', 'ventura', 'bacurin', '2005-12-08', 'Male', 912395106, NULL, '1151 torres bugallon st. tondo manila', '2025-07-12 00:56:52', '2025-07-12 00:56:52'),
(20, '231', '3123``', '2346', '2246-06-04', 'Male', 4146, NULL, '3466', '2025-07-12 08:07:08', '2025-07-12 08:07:08'),
(21, '231', '3123``', '2346', '2246-06-04', 'Male', 4146, NULL, '3466', '2025-07-12 08:11:55', '2025-07-12 08:11:55'),
(22, '23123', '313', '131', '0001-03-12', 'Male', 0, NULL, '123', '2025-07-12 08:14:32', '2025-07-12 08:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `Prescription_ID` int(11) NOT NULL,
  `Patient_ID` int(11) DEFAULT NULL,
  `Prescription_Date` date NOT NULL,
  `Sphere` decimal(5,2) DEFAULT NULL,
  `Cylinder` decimal(5,2) DEFAULT NULL,
  `Axis` int(11) DEFAULT NULL,
  `Addition` decimal(5,2) DEFAULT NULL,
  `PD` decimal(5,2) DEFAULT NULL,
  `Frame_ID` int(11) DEFAULT NULL,
  `Lens_Type_ID` int(11) DEFAULT NULL,
  `Tint` varchar(50) DEFAULT NULL,
  `Prescribed` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`Prescription_ID`, `Patient_ID`, `Prescription_Date`, `Sphere`, `Cylinder`, `Axis`, `Addition`, `PD`, `Frame_ID`, `Lens_Type_ID`, `Tint`, `Prescribed`) VALUES
(5, 5, '0000-00-00', 15.00, 61.00, 25, 52.00, 61.00, 1, 1, '52', 'ralph'),
(8, 8, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 1, 2, '451', 'da'),
(9, 9, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 4, 2, 's', 'qwrq'),
(10, 10, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 2, 2, 'dhdh', 'asjd'),
(11, 11, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 2, 2, 'dajdj', 'sad'),
(12, 12, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 3, 2, '352', 'c'),
(13, 13, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 2, 2, 'cd', 'dhsh'),
(14, 14, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 4, 2, 'asd', 'asda'),
(15, 15, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 4, 2, 'dasjd', 'dsad'),
(16, 16, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 1, 2, 'fjfh', 'dad'),
(17, 17, '0000-00-00', 98.00, 88.00, 76, 767.00, 67.00, 3, 2, 'da', 'sad'),
(18, 18, '0000-00-00', 0.00, 0.00, 0, 0.00, 0.00, 4, 1, 'sad', 'xa'),
(19, 19, '0000-00-00', 15.00, 61.00, 25, 0.00, 90.00, 4, 2, '451', 'ralph'),
(20, 20, '0000-00-00', 273.00, 999.99, 4237, 999.99, 999.99, 4, 1, '5273', 'sad'),
(21, 21, '0000-00-00', 273.00, 999.99, 4237, 999.99, 999.99, 4, 1, '5273', 'sad'),
(22, 22, '0000-00-00', 999.99, 999.99, 231, 999.99, 123.00, 5, 2, '123', '123');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `Product_ID` int(11) NOT NULL,
  `Product_Name` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `Category_ID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT 0,
  `Selling_Price` decimal(10,2) NOT NULL,
  `Discounted_Price` decimal(10,2) DEFAULT NULL,
  `Date_Added` timestamp NOT NULL DEFAULT current_timestamp(),
  `Reorder_Level` int(11) DEFAULT 10,
  `Reorder_Quantity` int(11) DEFAULT 20
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`Product_ID`, `Product_Name`, `Description`, `Category_ID`, `Quantity`, `Selling_Price`, `Discounted_Price`, `Date_Added`, `Reorder_Level`, `Reorder_Quantity`) VALUES
(8, 'Eye Vision', 'Can make you see 8x', 8, 25, 790.00, 0.00, '2025-07-10 14:20:25', 0, 0),
(9, 'aspirin', 'nakakalason', 8, 2, 45.00, 525.00, '2025-07-10 15:13:44', 52, 52),
(10, 'Eyeglass', 'Sun', 8, 50, 100.00, 80.00, '2025-07-11 16:35:10', 10, 10),
(11, 'Eye', 'Glass', 8, 60, 100.00, 80.00, '2025-07-11 16:36:13', 10, 10),
(12, 'M4A1', 'GUn Phew PHew', 8, 50, 1000.00, 100.00, '2025-07-11 17:07:43', 100, 100),
(13, 'Tabo', 'pew', 8, 100, 180.00, 0.00, '2025-07-11 18:39:22', 0, 0),
(14, 'baso', 'wer', 8, 100, 120.00, 0.00, '2025-07-12 02:47:21', 0, 0),
(15, 'Eye Vision', 'Can make you see 8x', 8, 12, 12.00, 12.00, '2025-07-12 06:29:32', 12, 12);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_transaction`
--

CREATE TABLE `purchase_transaction` (
  `Purchase_ID` int(11) NOT NULL,
  `Item_ID` int(11) DEFAULT NULL,
  `Transaction_Code` varchar(100) NOT NULL,
  `Supplier_ID` int(11) DEFAULT NULL,
  `Quantity_Purchased` int(11) DEFAULT NULL,
  `Total_Cost` decimal(10,2) DEFAULT NULL,
  `Purchase_Price` decimal(10,2) DEFAULT NULL,
  `Purchase_Date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_item`
--

CREATE TABLE `sale_item` (
  `Sale_Item_ID` int(11) NOT NULL,
  `Sale_ID` int(11) DEFAULT NULL,
  `Product_ID` int(11) DEFAULT NULL,
  `Quantity_Sold` int(11) DEFAULT NULL,
  `Unit_Price` decimal(10,2) DEFAULT NULL,
  `Total_Price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_item`
--

INSERT INTO `sale_item` (`Sale_Item_ID`, `Sale_ID`, `Product_ID`, `Quantity_Sold`, `Unit_Price`, `Total_Price`) VALUES
(3, 4, 12, 3, 1000.00, 1500.00),
(4, 5, 12, 12, 1000.00, 9600.00),
(8, 8, 12, 1, 1000.00, 1000.00),
(9, 9, 11, 1, 100.00, 100.00),
(10, 9, 14, 2, 120.00, 240.00);

-- --------------------------------------------------------

--
-- Table structure for table `sale_transaction`
--

CREATE TABLE `sale_transaction` (
  `Sale_ID` int(11) NOT NULL,
  `Patient_ID` int(11) DEFAULT NULL,
  `Transaction_Date` timestamp NOT NULL DEFAULT current_timestamp(),
  `Total_Amount` decimal(10,2) DEFAULT NULL,
  `Discount_Amount` decimal(10,2) DEFAULT 0.00,
  `Payment_Status` enum('Paid','Pending','Refunded') DEFAULT 'Pending',
  `Created_At` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_transaction`
--

INSERT INTO `sale_transaction` (`Sale_ID`, `Patient_ID`, `Transaction_Date`, `Total_Amount`, `Discount_Amount`, `Payment_Status`, `Created_At`) VALUES
(4, 5, '2025-07-16 16:00:00', 1500.00, 0.00, 'Paid', '2025-07-11 17:08:54'),
(5, 5, '2025-07-31 16:00:00', 9600.00, 0.00, 'Pending', '2025-07-11 18:12:16'),
(8, 5, '2025-07-26 16:00:00', 1000.00, 0.00, 'Paid', '2025-07-12 01:39:14'),
(9, 5, '2025-07-11 16:00:00', 340.00, 0.00, 'Paid', '2025-07-12 06:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `user_account`
--

CREATE TABLE `user_account` (
  `User_ID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Role` enum('Admin','Staff','Doctor') NOT NULL DEFAULT 'Staff',
  `Status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `Date_Created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_account`
--

INSERT INTO `user_account` (`User_ID`, `Username`, `Password`, `Email`, `Role`, `Status`, `Date_Created`) VALUES
(13, 'justine', '$2y$10$8vsjy1BFqtpSFtzoMy2soe02WAw56bUkQg1YaL82Lp0G7mtXgMWXS', '123@gmail.com', 'Admin', 'Active', '2025-07-12 07:08:39'),
(14, 'rogelyn', '$2y$10$OpAgIMVSIkvieVUzqhkUMOhHoQGcc65l.N/W9.RzKapfUCb8c8inm', 'rogelynpelico@gmail.com', 'Staff', 'Active', '2025-07-12 07:09:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `frame`
--
ALTER TABLE `frame`
  ADD PRIMARY KEY (`Frame_ID`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`Item_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `lens_type`
--
ALTER TABLE `lens_type`
  ADD PRIMARY KEY (`Lens_Type_ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`Prescription_ID`),
  ADD KEY `Frame_ID` (`Frame_ID`),
  ADD KEY `Lens_Type_ID` (`Lens_Type_ID`),
  ADD KEY `prescription_ibfk_1` (`Patient_ID`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `purchase_transaction`
--
ALTER TABLE `purchase_transaction`
  ADD PRIMARY KEY (`Purchase_ID`),
  ADD KEY `Item_ID` (`Item_ID`);

--
-- Indexes for table `sale_item`
--
ALTER TABLE `sale_item`
  ADD PRIMARY KEY (`Sale_Item_ID`),
  ADD KEY `Sale_ID` (`Sale_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `sale_transaction`
--
ALTER TABLE `sale_transaction`
  ADD PRIMARY KEY (`Sale_ID`),
  ADD KEY `Patient_ID` (`Patient_ID`);

--
-- Indexes for table `user_account`
--
ALTER TABLE `user_account`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `frame`
--
ALTER TABLE `frame`
  MODIFY `Frame_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `Item_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lens_type`
--
ALTER TABLE `lens_type`
  MODIFY `Lens_Type_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `Patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `Prescription_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `purchase_transaction`
--
ALTER TABLE `purchase_transaction`
  MODIFY `Purchase_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_item`
--
ALTER TABLE `sale_item`
  MODIFY `Sale_Item_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sale_transaction`
--
ALTER TABLE `sale_transaction`
  MODIFY `Sale_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_account`
--
ALTER TABLE `user_account`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`Frame_ID`) REFERENCES `frame` (`Frame_ID`),
  ADD CONSTRAINT `prescription_ibfk_3` FOREIGN KEY (`Lens_Type_ID`) REFERENCES `lens_type` (`Lens_Type_ID`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `purchase_transaction`
--
ALTER TABLE `purchase_transaction`
  ADD CONSTRAINT `purchase_transaction_ibfk_1` FOREIGN KEY (`Item_ID`) REFERENCES `item` (`Item_ID`);

--
-- Constraints for table `sale_item`
--
ALTER TABLE `sale_item`
  ADD CONSTRAINT `sale_item_ibfk_1` FOREIGN KEY (`Sale_ID`) REFERENCES `sale_transaction` (`Sale_ID`),
  ADD CONSTRAINT `sale_item_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `product` (`Product_ID`);

--
-- Constraints for table `sale_transaction`
--
ALTER TABLE `sale_transaction`
  ADD CONSTRAINT `sale_transaction_ibfk_1` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
