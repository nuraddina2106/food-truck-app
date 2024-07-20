-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 03:00 PM
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
-- Database: `food_truck_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `food_trucks`
--

CREATE TABLE `food_trucks` (
  `truck_id` int(11) NOT NULL,
  `business_type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `operator_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `menu_name` varchar(50) NOT NULL,
  `business_hours` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_trucks`
--

INSERT INTO `food_trucks` (`truck_id`, `business_type`, `name`, `operator_name`, `address`, `menu_name`, `business_hours`, `latitude`, `longitude`, `image`) VALUES
(1, 'Ice cream gula apong', 'Mokti\'s Food Truck', 'Siti Fatimah Adnan', '26, 26A, Jln Kangar Jaya 4, Kangar Jaya, 01000 Kangar, Perlis', 'Chocolate, Vanilla, Mint Chocolate Ice Cream', '10am - 4pm', 6.407972, 100.180694, 'uploads/mokti.jpg'),
(2, 'Kebab', 'Kebab Jebat', 'Jebat Ali', 'Jalan Padang Katong, 01000 Kangar, Perlis', 'Kebab', '5pm - 11pm', 6.442528, 100.186333, 'uploads/kebabjebat.jpg'),
(4, 'French fries', 'My Cheezyfries', 'Yusob bin Omar', '58, Jalan Sri Hartamas 3, Taman Desa Sentua, 01000 Kangar, Perlis', 'Fast Food', '12pm - 12am', 6.412861, 100.202111, 'uploads/557d22d0f8343fa24c578071ee7fa867.jpg'),
(6, 'Chicken and pizza', 'Shala Pizza Food Truck', 'Abu Razak bin Abu Yahya', 'Kampong, 01000 Kangar, Perlis', 'Pizza', '3pm - 9pm', 6.440806, 100.180111, 'uploads/shala.jpeg'),
(9, 'Cafe', 'Block Caife Food Truck', 'Zabidah binti Khalif', 'Jalan Pengkalan Asam, Kampung Pengkalan Asam, 01000 Kangar, Perlis', 'Coffee, Dessert', '8pm - 1am', 6.435833, 100.185139, 'uploads/187f59352b6b090eada2e98a3ded6a7e.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_trucks`
--
ALTER TABLE `food_trucks`
  ADD PRIMARY KEY (`truck_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_trucks`
--
ALTER TABLE `food_trucks`
  MODIFY `truck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
