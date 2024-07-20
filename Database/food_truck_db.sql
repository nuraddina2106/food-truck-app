-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2024 at 05:17 PM
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
  `business_hours` varchar(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_trucks`
--

INSERT INTO `food_trucks` (`truck_id`, `business_type`, `name`, `operator_name`, `address`, `business_hours`, `latitude`, `longitude`, `image`) VALUES
(1, 'Ice cream gula apong', 'Mokti\'s Food Truck', 'Siti Fatimah Adnan', '26, 26A, Jln Kangar Jaya 4, Kangar Jaya, 01000 Kangar, Perlis', '10am - 4pm', 6.407972, 100.180694, 'uploads/mokti.jpg'),
(2, 'Kebab', 'Kebab Jebat', 'Jebat Ali', 'Jalan Padang Katong, 01000 Kangar, Perlis', '5pm - 11pm', 6.442528, 100.186333, 'uploads/kebabjebat.jpg'),
(4, 'French fries', 'My Cheezyfries', 'Yusob bin Omar', '58, Jalan Sri Hartamas 3, Taman Desa Sentua, 01000 Kangar, Perlis', '12pm - 12am', 6.412861, 100.202111, 'uploads/cheezyfries.jpg'),
(6, 'pizza', 'Shala Pizza Food Truck', 'Abu Razak bin Abu Yahya', 'Kampong, 01000 Kangar, Perlis', '10am - 9pm', 6.440806, 100.180111, 'uploads/street-food-truck-background_98396-848.jpg'),
(7, 'Cafe', 'Tff Cafe Food Truck', 'Ali bin Hamad', 'Jalan Dahlia, Taman Ira, 01000 Kangar, Perlis', '10', 6.4367, 100.2047, 'uploads/dde721ffda9cc460203f4af7ad7cbba7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL,
  `truck_id` int(11) DEFAULT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_desc` text DEFAULT NULL,
  `menu_price` decimal(10,2) NOT NULL,
  `menu_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `truck_id`, `menu_name`, `menu_desc`, `menu_price`, `menu_image`) VALUES
(1, 2, 'Chicken Shawarma', 'Marinated chicken breast grilled to perfection, served with a blend of lettuce, tomatoes, cucumbers, and a drizzle of garlic sauce. Wrapped in warm pita bread for a delightful experience.', 15.00, 'uploads/shawarma.jpg'),
(2, 1, 'Cocktail Ice Cream', 'Refreshing and icy vanilla flavour with fruits', 14.90, 'uploads/cocktail ice cream.jpg'),
(3, 1, 'Harumanis Ice Cream', '100% harum manis ice cream that comes in small tubes 👍👍', 2.00, 'uploads/harumanis ice cream.jpg'),
(4, 1, 'Soft Apong Ice Cream', 'Signature Flavour 🌟 100% everyone\'s favorite 🥰', 5.50, 'uploads/soft apong.jpg'),
(5, 1, 'Small tube Solerom', 'Sweet vanilla and sour lime solero that comes on small tubes 🤤🤤', 3.00, 'uploads/solerom.jpg'),
(6, 1, 'Savoury Waffle', 'Crunchy waffle with yummy savoury filling ✌️✌️', 8.00, 'uploads/waffle.jpg'),
(7, 2, 'Beef Kofta', 'Juicy ground beef mixed with aromatic spices and herbs, grilled on skewers, and accompanied by fresh veggies and a touch of tahini sauce. All wrapped in soft flatbread.', 18.00, 'uploads/beef kofta.jpg'),
(8, 2, 'Lamb Gyro', 'Tender slices of lamb seasoned with Mediterranean spices, served with onion, tomatoes, and tzatziki sauce. Wrapped in a fluffy pita bread, perfect for a hearty meal.', 20.00, 'uploads/lamb gyro.png'),
(9, 2, 'Falafel', 'Crispy, golden falafel balls made from ground chickpeas and spices, paired with hummus, fresh greens, and pickled vegetables. Wrapped in a whole wheat pita for a vegetarian delight.', 12.00, 'uploads/falafel.jpg'),
(10, 2, 'Grilled Vegetable', 'A mix of grilled bell peppers, zucchini, onions, and eggplant, topped with feta cheese and a dash of balsamic glaze. Wrapped in a whole grain flatbread for a healthy and flavorful option.', 10.00, 'uploads/grilled vege.jpg'),
(11, 4, 'Premium Sausage Cheese Fries', 'Crispy golden fries topped with premium sausage slices, smothered in rich, melted cheese. Served in a convenient package box, perfect for a tasty treat on the go.', 12.00, 'uploads/sausage.jpg'),
(12, 4, 'Beef Pepperoni Cheese Fries', 'Savory beef pepperoni layered over crispy fries, covered in a blanket of melted cheese. Served in a package box for a delightful snack experience.', 14.00, 'uploads/beef pepperoni.jpg'),
(13, 4, 'Ayam Gunting Cheese Tarik', 'Bite-sized pieces of crispy fried chicken (Ayam Gunting) combined with a generous amount of stretchy, melted cheese. Served in a package box for a delicious and easy-to-eat meal.', 15.00, 'uploads/ayam gunting.jpg'),
(14, 4, 'Enoki Mushroom Crunchy', 'Crispy battered enoki mushrooms offering a delightful crunch with every bite. Served in a package box, ideal for a light and tasty snack.', 10.00, 'uploads/enoki.jpg'),
(15, 4, 'Crab Rangoon', 'Creamy and savory crab filling wrapped in a crispy wonton shell, perfect for a flavorful snack. Served in a package box for convenience.', 13.00, 'uploads/crab ragoon.jpg'),
(26, 6, 'Classic Crust Beef Meatballs & Pepperoni', 'Hand tossed classic crust', 15.00, 'uploads/meatball pepperoni.jpg'),
(27, 6, 'Baked Macaroni Cheese', 'Chef recommended', 20.90, 'uploads/macaroni.jpg'),
(28, 6, 'Deli Smoked Chicken Wing', 'Chef recommended', 23.90, 'uploads/deli chicken.jpg'),
(29, 6, 'Spicy Chicken Popcorn Salad', 'A delicious mix of crispy popcorn chicken and fresh greens, tossed with colorful vegetables and a zesty dressing. Perfectly spicy and refreshing, it’s a satisfying salad for any meal!', 13.90, 'uploads/salad.jpg'),
(30, 6, 'Carbonara Spaghetti', 'Classic Italian pasta dish made with creamy sauce, pancetta, and Parmesan cheese, creating a rich and flavorful experience in every bite.', 18.90, 'uploads/carbonara spaghetti.jpg'),
(31, 6, 'Beef Meatball Spaghetti', 'Hearty spaghetti topped with savory beef meatballs simmered in a rich tomato sauce. A comforting and filling meal that\'s sure to satisfy!', 18.90, 'uploads/beef spaghetti.jpg'),
(33, 7, 'Chocolate Chip Milkshake', 'The Choco Chip Milkshake is a rich chocolate milkshake swirled with chocolate syrup and topped with chocolate chips and whipped cream, served in a clear cup with the cafe\'s logo.', 10.00, '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `food_trucks`
--
ALTER TABLE `food_trucks`
  ADD PRIMARY KEY (`truck_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `truck_id` (`truck_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `food_trucks`
--
ALTER TABLE `food_trucks`
  MODIFY `truck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`truck_id`) REFERENCES `food_trucks` (`truck_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
