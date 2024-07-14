-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 14, 2024 at 03:04 AM
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
(5, 'Cafe', 'Block Caife Food Truck', 'Zabidah binti Khalif', 'Jalan Pengkalan Asam, Kampung Pengkalan Asam, 01000 Kangar, Perlis', '5pm - 1am', 6.435833, 100.185139, 'uploads/block.png'),
(6, 'Chicken and pizza', 'Shala Pizza Food Truck', 'Abu Razak bin Abu Yahya', 'Kampong, 01000 Kangar, Perlis', '3pm - 9pm', 6.440806, 100.180111, 'uploads/shala.jpeg');

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
(1, 2, 'Chicken Shawarma', 'Marinated chicken breast grilled to perfection, served with a blend of lettuce, tomatoes, cucumbers, and a drizzle of garlic sauce. Wrapped in warm pita bread for a delightful experience.', 15.00, 'shawarma.jpg'),
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
(16, 5, 'Belgian Waffle', 'A classic Belgian waffle with a crisp exterior and a fluffy interior, topped with a dusting of powdered sugar. Served with a side of maple syrup and fresh berries for a delightful breakfast treat.', 10.00, 'uploads/belgian waffle.jpg'),
(17, 5, 'Chocolate Chip Waffle', 'A delicious waffle infused with chocolate chips, providing a perfect blend of crispy and melty textures in every bite. Drizzled with chocolate sauce and served with whipped cream.', 12.00, 'uploads/choco chip waffle.jpg'),
(18, 5, 'Strawberry Waffle', 'A golden waffle topped with fresh strawberries and a generous dollop of whipped cream. Finished with a drizzle of strawberry sauce for a sweet and refreshing treat.', 14.00, 'uploads/strawberry waffle.jpeg'),
(19, 5, 'Grilled Salmon Sandwich', 'A perfectly grilled salmon fillet, served with fresh lettuce, tomatoes, and cucumber slices, all layered between two slices of toasted whole grain bread. Accompanied by a creamy dill sauce for an extra burst of flavor.', 20.00, 'uploads/grilled salmon sandwich.jpg'),
(20, 5, 'Grilled Chicken Sandwich', 'Tender grilled chicken breast, topped with lettuce, tomato, and a slice of cheddar cheese, all nestled between a toasted bun. Accompanied by a side of honey mustard sauce.', 18.00, 'uploads/grilled chicken sandwich.jpg'),
(21, 5, 'Glazed Donut', 'A classic, light, and fluffy donut covered in a sweet, glossy glaze. Perfectly simple and delicious for any time of the day.', 5.00, 'uploads/glazed donut.jpg'),
(22, 5, 'Chocolate Frosted Donut', 'A soft, airy donut topped with rich chocolate frosting and colorful sprinkles. A delightful treat for chocolate lovers.', 6.00, 'uploads/chocolate donut.jpg'),
(23, 5, 'Iced Latte', 'A refreshing blend of espresso and chilled milk, served over ice. Perfectly balanced and smooth, ideal for a hot day.', 8.00, 'uploads/iced latte.jpg'),
(24, 5, 'Strawberry Smoothie', 'A creamy and refreshing smoothie made with fresh strawberries, yogurt, and a touch of honey. A perfect drink to energize your day.', 10.00, 'uploads/strawberry smoothie.jpg'),
(25, 5, 'Classic Lemonade', 'A thirst-quenching drink made with freshly squeezed lemons, water, and just the right amount of sugar. Served chilled with ice for a revitalizing beverage.', 7.00, 'uploads/classic lemonade.jpeg'),
(26, 6, 'Classic Crust Beef Meatballs & Pepperoni', 'Hand tossed classic crust', 15.00, 'uploads/meatball pepperoni.jpg'),
(27, 6, 'Baked Macaroni Cheese', 'Chef recommended', 20.90, 'uploads/macaroni.jpg'),
(28, 6, 'Deli Smoked Chicken Wing', 'Chef recommended', 23.90, 'uploads/deli chicken.jpg'),
(29, 6, 'Spicy Chicken Popcorn Salad', 'A delicious mix of crispy popcorn chicken and fresh greens, tossed with colorful vegetables and a zesty dressing. Perfectly spicy and refreshing, it’s a satisfying salad for any meal!', 13.90, 'uploads/salad.jpg'),
(30, 6, 'Carbonara Spaghetti', 'Classic Italian pasta dish made with creamy sauce, pancetta, and Parmesan cheese, creating a rich and flavorful experience in every bite.', 18.90, 'uploads/carbonara spaghetti.jpg'),
(31, 6, 'Beef Meatball Spaghetti', 'Hearty spaghetti topped with savory beef meatballs simmered in a rich tomato sauce. A comforting and filling meal that\'s sure to satisfy!', 18.90, 'uploads/beef spaghetti.jpg'),
(32, 6, 'Chicken Bolognese Spaghetti', 'Delicious spaghetti served with a flavorful chicken Bolognese sauce, made with ground chicken, tomatoes, and herbs. A lighter twist on a traditional favorite!', 18.90, 'uploads/chicken bolognese.jpeg');

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
  MODIFY `truck_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
