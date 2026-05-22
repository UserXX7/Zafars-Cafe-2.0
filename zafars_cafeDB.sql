-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2026 at 09:39 PM
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
-- Database: `zafars_cafe`
--

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category`, `description`, `price`, `stock_quantity`, `image`, `created_at`) VALUES
(2, 'Can Coca Cola', 'Cold Drinks', 'Classic Coca Cola can.', 1.50, 60, 'coke-can.jpg', '2026-04-05 17:41:09'),
(3, 'Can Sprite', 'Cold Drinks', 'Refreshing Sprite can.', 1.50, 60, 'sprite-can.jpg', '2026-04-05 17:41:09'),
(4, 'Can Ginger Ale', 'Cold Drinks', 'Smooth ginger ale soda.', 1.50, 60, 'ginger-ale-can.jpg', '2026-04-05 17:41:09'),
(5, '20oz Coca Cola', 'Cold Drinks', '20oz Coca Cola bottle.', 2.50, 40, 'coke-20oz.jpg', '2026-04-05 17:41:09'),
(6, '20oz Sprite', 'Cold Drinks', '20oz Sprite bottle.', 2.50, 40, 'sprite-20oz.jpg', '2026-04-05 17:41:09'),
(7, '20oz Ginger Ale', 'Cold Drinks', '20oz Ginger Ale bottle.', 2.50, 40, 'ginger-ale-20oz.jpg', '2026-04-05 17:41:09'),
(8, 'San Pellegrino Seltzer Water', 'Water', 'Sparkling mineral water.', 2.00, 35, 'seltzer.jpg', '2026-04-05 17:41:09'),
(9, 'Bagel with Cream Cheese', 'Breakfast', 'Fresh bagel served with cream cheese.', 3.50, 30, 'bagel.jpg', '2026-04-05 17:41:09'),
(10, 'Plain Croissant', 'Breakfast', 'Buttery flaky croissant.', 2.75, 25, 'croissant.jpg', '2026-04-05 17:41:09'),
(11, 'Gallon Milk', 'Grocery', 'Fresh whole milk gallon.', 4.99, 20, 'milk.jpg', '2026-04-05 17:41:09'),
(12, 'Doritos Chips', 'Snacks', 'Crunchy Doritos tortilla chips.', 2.25, 50, 'doritos.jpg', '2026-04-05 17:41:09'),
(13, 'Cheetos Flamin Hot', 'Snacks', 'Spicy Flamin Hot Cheetos.', 2.25, 50, 'cheetos.jpg', '2026-04-05 17:41:09'),
(14, 'Lays Original', 'Snacks', 'Classic Lays potato chips.', 2.00, 50, 'lays-original.jpg', '2026-04-05 17:41:09'),
(15, 'Lays Sour Cream', 'Snacks', 'Lays sour cream & onion chips.', 2.00, 50, 'lays-sourcream.jpg', '2026-04-05 17:41:09'),
(16, 'Cheese Pizza Slice', 'Food', 'Hot cheese pizza slice.', 3.00, 20, 'cheese-pizza.jpg', '2026-04-05 17:41:09'),
(17, 'Pepperoni Pizza Slice', 'Food', 'Pizza slice with pepperoni.', 3.50, 20, 'pepperoni-pizza.jpg', '2026-04-05 17:41:09'),
(18, 'Veggie Pizza Slice', 'Food', 'Pizza slice with fresh vegetables.', 3.25, 20, 'veggie-pizza.jpg', '2026-04-05 17:41:09'),
(19, 'Beef Patty', 'Food', 'Jamaican-style beef patty.', 3.00, 20, 'beef-patty.jpg', '2026-04-05 17:41:09'),
(20, 'Beef Patty with Cheese', 'Food', 'Beef patty with melted cheese.', 3.50, 20, 'beef-patty-cheese.jpg', '2026-04-05 17:41:09'),
(21, 'Espresso', 'Coffee', 'Strong freshly brewed espresso.', 2.75, 30, 'espresso.jpg', '2026-04-14 21:46:46'),
(22, 'Latte', 'Coffee', 'Hot latte made with steamed milk.', 4.25, 25, 'latte.jpg', '2026-04-14 21:46:46'),
(23, 'Iced Coffee', 'Coffee', 'Chilled coffee served over ice.', 3.50, 35, 'iced-coffee.jpg', '2026-04-14 21:46:46'),
(24, 'Poland Spring Water', 'Water', 'Refreshing bottled water.', 1.50, 50, 'poland-spring.jpg', '2026-04-14 21:46:46'),
(25, 'Dasani Water', 'Water', 'Purified bottled water.', 1.50, 45, 'dasani.jpg', '2026-04-14 21:46:46'),
(26, 'Blueberry Muffin', 'Breakfast', 'Soft muffin with blueberry filling.', 2.95, 20, 'blueberry-muffin.jpg', '2026-04-14 21:46:46'),
(27, 'Egg and Cheese Sandwich', 'Breakfast', 'Classic egg and cheese breakfast sandwich.', 4.99, 18, 'egg-cheese.jpg', '2026-04-14 21:46:46'),
(28, 'Arizona Iced Tea', 'Cold Drinks', 'Sweet iced tea can.', 1.50, 40, 'arizona-tea.jpg', '2026-04-14 21:46:46'),
(29, 'Minute Maid Orange Juice', 'Cold Drinks', 'Chilled orange juice bottle.', 2.75, 25, 'minute-maid.jpg', '2026-04-14 21:46:46'),
(30, 'Oreo Cookies', 'Snacks', 'Classic Oreo cookie pack.', 2.25, 35, 'oreo.jpg', '2026-04-14 21:46:46'),
(31, 'KitKat Bar', 'Snacks', 'Chocolate wafer candy bar.', 1.75, 40, 'kitkat.jpg', '2026-04-14 21:46:46'),
(32, 'White Bread', 'Grocery', 'Fresh packaged white bread loaf.', 3.25, 20, 'white-bread.jpg', '2026-04-14 21:46:46'),
(33, 'Eggs Dozen', 'Grocery', 'One dozen large eggs.', 4.99, 15, 'eggs-dozen.jpg', '2026-04-14 21:46:46'),
(34, 'Chicken Roll', 'Food', 'Hot chicken roll with flaky crust.', 3.50, 20, 'chicken-roll.jpg', '2026-04-14 21:46:46'),
(35, 'Cheese Empanada', 'Food', 'Savory cheese-filled empanada.', 3.25, 20, 'cheese-empanada.jpg', '2026-04-14 21:46:46'),
(36, 'Hot Coffee (10oz Small)', 'Coffee', 'Fresh brewed hot coffee - small.', 2.00, 50, 'hot coffee 10oz..webp', '2026-05-05 20:38:46'),
(37, 'Hot Coffee (16oz Large)', 'Coffee', 'Fresh brewed hot coffee - large.', 2.75, 50, 'hot coffee 16oz..webp', '2026-05-05 20:38:46'),
(38, 'Hot Chocolate (16oz)', 'Coffee', 'Rich and creamy hot chocolate.', 3.00, 40, 'hot-chocolate.jpg', '2026-05-05 20:38:46'),
(39, 'Lipton Tea (10oz Small)', 'Coffee', 'Classic Lipton hot tea.', 1.75, 50, 'tea.jpg', '2026-05-05 20:38:46'),
(40, 'Lipton Tea (16oz Large)', 'Coffee', 'Classic Lipton hot tea.', 2.25, 50, 'tea.jpg', '2026-05-05 20:38:46'),
(41, 'English Breakfast Tea (10oz Small)', 'Coffee', 'Strong black tea.', 2.00, 40, 'EnglishBreakfast.webp', '2026-05-05 20:38:46'),
(42, 'English Breakfast Tea (16oz Large)', 'Coffee', 'Strong black tea.', 2.50, 40, 'EnglishBreakfast.webp', '2026-05-05 20:38:46'),
(43, 'Chamomile Tea (10oz Small)', 'Coffee', 'Relaxing herbal tea.', 2.25, 40, 'CamomileTea.webp', '2026-05-05 20:38:46'),
(44, 'Chamomile Tea (16oz Large)', 'Coffee', 'Relaxing herbal tea.', 2.75, 40, 'CamomileTea.webp', '2026-05-05 20:38:46'),
(45, 'Peppermint Tea (10oz Small)', 'Coffee', 'Refreshing mint herbal tea.', 2.25, 40, 'peppermint.jpg', '2026-05-05 20:38:46'),
(46, 'Peppermint Tea (16oz Large)', 'Coffee', 'Refreshing mint herbal tea.', 2.75, 40, 'peppermint.jpg', '2026-05-05 20:38:46'),
(47, 'Lemon Zinger Tea (10oz Small)', 'Coffee', 'Citrus herbal tea.', 2.25, 40, 'LemonZingerTea.jpg', '2026-05-05 20:38:46'),
(48, 'Lemon Zinger Tea (16oz Large)', 'Coffee', 'Citrus herbal tea.', 2.75, 40, 'LemonZingerTea.jpg', '2026-05-05 20:38:46'),
(49, 'Turmeric Tea (10oz Small)', 'Coffee', 'Warm turmeric herbal tea.', 2.50, 35, 'turmeric.jpg', '2026-05-05 20:38:46'),
(50, 'Turmeric Tea (16oz Large)', 'Coffee', 'Warm turmeric herbal tea.', 3.00, 35, 'turmeric.jpg', '2026-05-05 20:38:46'),
(51, 'Organic Moringa Tea (10oz Small)', 'Coffee', 'Healthy moringa herbal tea.', 2.75, 30, 'moringa.jpg', '2026-05-05 20:38:46'),
(52, 'Organic Moringa Tea (16oz Large)', 'Coffee', 'Healthy moringa herbal tea.', 3.25, 30, 'moringa.jpg', '2026-05-05 20:38:46'),
(53, 'Pocas Ginger Tea (10oz Small)', 'Coffee', 'Spicy ginger tea.', 2.50, 35, 'ginger-tea.jpg', '2026-05-05 20:38:46'),
(54, 'Pocas Ginger Tea (16oz Large)', 'Coffee', 'Spicy ginger tea.', 3.00, 35, 'ginger-tea.jpg', '2026-05-05 20:38:46'),
(55, 'Pepsi Can', 'Cold Drinks', 'Classic Pepsi can.', 1.50, 60, 'pepsi-can.jpg', '2026-05-05 20:42:51'),
(56, 'Fanta Orange Can', 'Cold Drinks', 'Orange soda can.', 1.50, 60, 'fanta-can.jpg', '2026-05-05 20:42:51'),
(57, 'Mountain Dew Can', 'Cold Drinks', 'Citrus soda can.', 1.50, 60, 'mtndew-can.jpg', '2026-05-05 20:42:51'),
(58, 'Dr Pepper Can', 'Cold Drinks', 'Dr Pepper soda.', 1.50, 60, 'drpepper-can.jpg', '2026-05-05 20:42:51'),
(59, 'Pepsi 20oz', 'Cold Drinks', 'Pepsi bottle.', 2.50, 40, 'pepsi-20oz.jpg', '2026-05-05 20:42:51'),
(60, 'Fanta 20oz', 'Cold Drinks', 'Orange soda bottle.', 2.50, 40, 'fanta-20oz.jpg', '2026-05-05 20:42:51'),
(61, 'Mountain Dew 20oz', 'Cold Drinks', 'Citrus soda bottle.', 2.50, 40, 'mtndew-20oz.jpg', '2026-05-05 20:42:51'),
(62, 'Dr Pepper 20oz', 'Cold Drinks', 'Dr Pepper bottle.', 2.50, 40, 'drpepper-20oz.jpg', '2026-05-05 20:42:51'),
(63, 'Apple Juice Bottle', 'Cold Drinks', 'Fresh apple juice.', 2.75, 30, 'apple-juice.jpg', '2026-05-05 20:42:51'),
(64, 'Cranberry Juice', 'Cold Drinks', 'Cranberry drink.', 2.75, 30, 'cranberry.jpg', '2026-05-05 20:42:51'),
(65, 'Mango Juice', 'Cold Drinks', 'Sweet mango juice.', 2.75, 30, 'mango.jpg', '2026-05-05 20:42:51'),
(66, 'Guava Juice', 'Cold Drinks', 'Tropical guava juice.', 2.75, 30, 'guava.jpg', '2026-05-05 20:42:51'),
(67, 'Lipton Iced Tea', 'Cold Drinks', 'Chilled iced tea bottle.', 2.50, 40, 'iced-tea.jpg', '2026-05-05 20:42:51'),
(68, 'Gold Peak Sweet Tea', 'Cold Drinks', 'Sweet iced tea.', 2.75, 40, 'goldpeak.jpg', '2026-05-05 20:42:51'),
(69, 'Arizona Green Tea', 'Cold Drinks', 'Green tea can.', 1.50, 50, 'arizona-green.jpg', '2026-05-05 20:42:51'),
(70, 'Arizona Lemon Tea', 'Cold Drinks', 'Lemon iced tea.', 1.50, 50, 'arizona-lemon.jpg', '2026-05-05 20:42:51'),
(71, 'Red Bull', 'Cold Drinks', 'Energy drink.', 3.50, 25, 'redbull.jpg', '2026-05-05 20:42:51'),
(72, 'Monster Energy', 'Cold Drinks', 'Energy drink can.', 3.50, 25, 'monster.jpg', '2026-05-05 20:42:51'),
(73, 'Bang Energy', 'Cold Drinks', 'High caffeine drink.', 3.75, 25, 'bang.jpg', '2026-05-05 20:42:51'),
(74, 'Gatorade Lemon Lime', 'Cold Drinks', 'Electrolyte drink.', 2.50, 40, 'gatorade.jpg', '2026-05-05 20:42:51'),
(75, 'Gatorade Orange', 'Cold Drinks', 'Orange sports drink.', 2.50, 40, 'gatorade-orange.jpg', '2026-05-05 20:42:51'),
(76, 'Powerade Blue', 'Cold Drinks', 'Blue sports drink.', 2.50, 40, 'powerade.jpg', '2026-05-05 20:42:51'),
(77, 'Poland Spring Water', 'Cold Drinks', 'Bottled water.', 1.25, 80, 'water.jpg', '2026-05-05 20:42:51'),
(78, 'Smart Water', 'Cold Drinks', 'Premium water.', 2.50, 40, 'smartwater.jpg', '2026-05-05 20:42:51'),
(79, 'Vitamin Water', 'Cold Drinks', 'Flavored water.', 2.75, 40, 'vitaminwater.jpg', '2026-05-05 20:42:51'),
(80, 'Chocolate Milk Bottle', 'Cold Drinks', 'Chocolate milk.', 2.75, 30, 'choco-milk.jpg', '2026-05-05 20:42:51'),
(81, 'Strawberry Milk', 'Cold Drinks', 'Strawberry flavored milk.', 2.75, 30, 'strawberry-milk.jpg', '2026-05-05 20:42:51'),
(82, 'Iced Coffee Bottle', 'Cold Drinks', 'Chilled coffee drink.', 3.00, 30, 'iced-coffee.jpg', '2026-05-05 20:42:51'),
(83, 'Starbucks Frappuccino', 'Cold Drinks', 'Bottled frappuccino.', 3.75, 25, 'frap.jpg', '2026-05-05 20:42:51'),
(84, 'Perrier Sparkling Water', 'Cold Drinks', 'Sparkling water.', 2.50, 35, 'perrier.jpg', '2026-05-05 20:42:51'),
(85, 'San Pellegrino Orange', 'Cold Drinks', 'Sparkling orange drink.', 2.50, 35, 'pellegrino-orange.jpg', '2026-05-05 20:42:51'),
(86, 'Poland Spring Water 8oz', 'Water', 'Small natural spring water bottle (8oz).', 0.99, 80, 'water.jpg', '2026-05-05 20:54:59'),
(87, 'Poland Spring Water 16.9oz', 'Water', 'Standard spring water bottle (16.9oz).', 1.50, 100, 'water.jpg', '2026-05-05 20:54:59'),
(88, 'Poland Spring Sports Cap', 'Water', 'Sports cap water bottle for easy drinking.', 1.75, 60, 'water.jpg', '2026-05-05 20:54:59'),
(89, 'Poland Spring Water 700ml', 'Water', 'Mid-size spring water bottle (700ml).', 2.00, 50, 'water.jpg', '2026-05-05 20:54:59'),
(90, 'Poland Spring Water 1L', 'Water', 'Large spring water bottle (1 liter).', 2.25, 50, 'water.jpg', '2026-05-05 20:54:59'),
(91, 'Poland Spring Water 1.5L', 'Water', 'Extra large spring water bottle (1.5 liter).', 2.75, 40, 'water.jpg', '2026-05-05 20:54:59'),
(92, 'Poland Spring Water 3L', 'Water', 'Family size spring water bottle (3 liter).', 4.50, 25, 'water.jpg', '2026-05-05 20:54:59'),
(93, 'Poland Spring Gallon', 'Water', 'One gallon natural spring water.', 5.99, 20, 'water.jpg', '2026-05-05 20:54:59'),
(94, 'Poland Spring 40-Pack (16.9oz)', 'Water', 'Case of 40 bottles (16.9oz each).', 8.99, 15, 'water-case.jpg', '2026-05-05 20:54:59'),
(95, 'Large Pepperoni Pizza Pie', 'Food', 'Large pepperoni pizza with classic cheese and tomato base.', 18.99, 20, 'pizza.jpg', '2026-05-05 21:04:04'),
(96, 'Samosa', 'Food', 'Spiced potato and pea-filled pastry.', 1.99, 40, 'samosa.jpg', '2026-05-05 21:04:04'),
(97, 'Veg Spring Roll', 'Food', 'Crispy rolls filled with veggies.', 1.99, 40, 'spring-roll.jpg', '2026-05-05 21:04:04'),
(98, 'Beef Patty', 'Food', 'Juicy ground beef patty.', 3.45, 30, 'beef-patty.jpg', '2026-05-05 21:04:04'),
(99, 'Beef Patty with Cheese', 'Food', 'Beef patty layered with cheese.', 4.45, 30, 'beef-patty.jpg', '2026-05-05 21:04:04'),
(100, 'Small Personal Cheese Pizza', 'Food', 'Small cheese pizza.', 8.99, 20, 'pizza.jpg', '2026-05-05 21:04:04'),
(101, 'Small Personal Pepperoni Pizza', 'Food', 'Small pepperoni pizza.', 9.99, 20, 'pizza.jpg', '2026-05-05 21:04:04'),
(102, 'Large Cheese Pizza Pie', 'Food', 'Large cheese pizza.', 16.99, 20, 'pizza.jpg', '2026-05-05 21:04:04'),
(103, 'Large Veggie Pizza Pie (Thin Crust)', 'Food', 'Thin crust veggie pizza.', 15.99, 20, 'pizza.jpg', '2026-05-05 21:04:04'),
(104, 'Slice Cheese Pizza', 'Food', 'Single cheese slice.', 2.59, 50, 'pizza-slice.jpg', '2026-05-05 21:04:04'),
(105, 'Slice Pepperoni Pizza', 'Food', 'Single pepperoni slice.', 2.99, 50, 'pizza-slice.jpg', '2026-05-05 21:04:04'),
(106, 'Hard Boiled Egg', 'Food', 'Simple boiled egg snack.', 1.29, 50, 'egg.jpg', '2026-05-05 21:04:04'),
(107, 'Bagel with Cream Cheese', 'Breakfast', 'Classic bagel with smooth cream cheese.', 2.99, 40, 'bagel.jpg', '2026-05-05 21:07:38'),
(108, 'Bagel with Butter', 'Breakfast', 'Bagel with creamy butter.', 2.25, 40, 'bagel.jpg', '2026-05-05 21:07:38'),
(109, 'Bagel with Jam', 'Breakfast', 'Bagel with sweet fruity jam.', 2.25, 40, 'bagel.jpg', '2026-05-05 21:07:38'),
(110, 'Bagel with Cream Cheese & Jam', 'Breakfast', 'Bagel with cream cheese and jam.', 3.95, 40, 'bagel.jpg', '2026-05-05 21:07:38'),
(111, 'Bagel Sliced', 'Breakfast', 'Sliced bagel for quick snack.', 1.00, 50, 'bagel.jpg', '2026-05-05 21:07:38'),
(112, 'Plain Croissant', 'Breakfast', 'Flaky buttery croissant.', 2.59, 35, 'croissant.jpg', '2026-05-05 21:07:38'),
(113, 'Apple Danish', 'Breakfast', 'Flaky pastry with apple filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(114, 'Blueberry Danish', 'Breakfast', 'Flaky pastry with blueberry filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(115, 'Chocolate Danish', 'Breakfast', 'Flaky pastry with chocolate filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(116, 'Cherry Danish', 'Breakfast', 'Flaky pastry with cherry filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(117, 'Cream Cheese Danish', 'Breakfast', 'Flaky pastry with cream cheese filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(118, 'Guava Danish', 'Breakfast', 'Flaky pastry with guava filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(119, 'Strawberry Danish', 'Breakfast', 'Flaky pastry with strawberry filling.', 2.59, 30, 'danish.jpg', '2026-05-05 21:07:38'),
(120, 'Sliced Pound Cake (Plain)', 'Breakfast', 'Classic plain pound cake slice.', 2.49, 25, 'cake.jpg', '2026-05-05 21:07:38'),
(121, 'Sliced Pound Cake (Marble)', 'Breakfast', 'Marble pound cake slice.', 2.49, 25, 'cake.jpg', '2026-05-05 21:07:38'),
(122, 'Banana', 'Breakfast', 'Fresh whole banana.', 0.99, 50, 'banana.jpg', '2026-05-05 21:07:38'),
(123, 'Jumbo Cookies (Chocolate Chip)', 'Breakfast', 'Large chocolate chip cookie.', 2.99, 40, 'cookie.jpg', '2026-05-05 21:07:38'),
(124, 'Jumbo Cookies (Oatmeal Raisin)', 'Breakfast', 'Oatmeal raisin cookie.', 2.99, 40, 'cookie.jpg', '2026-05-05 21:07:38'),
(125, 'Jumbo Cookies (Birthday Cake)', 'Breakfast', 'Birthday cake flavored cookie.', 2.99, 40, 'cookie.jpg', '2026-05-05 21:07:38'),
(126, 'Jumbo Cookies (Peanut Butter)', 'Breakfast', 'Peanut butter cookie.', 2.99, 40, 'cookie.jpg', '2026-05-05 21:07:38'),
(127, 'Jumbo Cookies (M&M\'s)', 'Breakfast', 'Cookie with M&Ms.', 2.99, 40, 'cookie.jpg', '2026-05-05 21:07:38'),
(128, 'Gallon Milk Regular', 'Grocery', 'Regular whole milk gallon.', 4.99, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(129, 'Gallon Milk 1%', 'Grocery', 'Low fat 1% milk gallon.', 4.99, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(130, 'Gallon Milk 2%', 'Grocery', 'Reduced fat 2% milk gallon.', 4.99, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(131, 'Half Gallon Milk Regular', 'Grocery', 'Regular milk half gallon.', 3.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(132, 'Half Gallon Milk 1%', 'Grocery', 'Low fat 1% milk half gallon.', 3.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(133, 'Half Gallon Milk 2%', 'Grocery', 'Reduced fat 2% milk half gallon.', 3.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(134, 'Quarter Gallon Milk Regular', 'Grocery', 'Regular milk quart size.', 2.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(135, 'Quarter Gallon Milk 1%', 'Grocery', 'Low fat 1% milk quart size.', 2.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(136, 'Quarter Gallon Milk 2%', 'Grocery', 'Reduced fat 2% milk quart size.', 2.49, 20, 'milk.jpg', '2026-05-05 21:13:24'),
(137, 'Horizon Organic Whole Milk', 'Grocery', 'Organic whole milk.', 5.99, 15, 'horizon-milk.jpg', '2026-05-05 21:13:24'),
(138, 'Horizon Organic 2% Milk', 'Grocery', 'Organic reduced fat milk.', 5.99, 15, 'horizon-milk.jpg', '2026-05-05 21:13:24'),
(139, 'Almond Breeze Almond Milk Original', 'Grocery', 'Original almond milk.', 4.99, 15, 'almond-milk.jpg', '2026-05-05 21:13:24'),
(140, 'Almond Breeze Almond Milk Vanilla', 'Grocery', 'Vanilla almond milk.', 4.99, 15, 'almond-milk.jpg', '2026-05-05 21:13:24'),
(141, 'Oat Milk Original', 'Grocery', 'Original oat milk.', 4.99, 15, 'oat-milk.jpg', '2026-05-05 21:13:24'),
(142, 'Oat Milk Vanilla', 'Grocery', 'Vanilla oat milk.', 4.99, 15, 'oat-milk.jpg', '2026-05-05 21:13:24'),
(143, 'Lactaid Milk Regular Gallon', 'Grocery', 'Lactose-free regular milk gallon.', 6.49, 15, 'lactaid.jpg', '2026-05-05 21:13:24'),
(144, 'Lactaid Milk Regular Half Gallon', 'Grocery', 'Lactose-free regular milk half gallon.', 4.49, 15, 'lactaid.jpg', '2026-05-05 21:13:24'),
(145, 'Lactaid Milk Reduced Fat Gallon', 'Grocery', 'Lactose-free reduced fat milk gallon.', 6.49, 15, 'lactaid.jpg', '2026-05-05 21:13:24'),
(146, 'Lactaid Milk Reduced Fat Half Gallon', 'Grocery', 'Lactose-free reduced fat milk half gallon.', 4.49, 15, 'lactaid.jpg', '2026-05-05 21:13:24'),
(147, 'Maseca Flour', 'Grocery', 'Instant corn masa flour.', 4.99, 20, 'maseca.jpg', '2026-05-05 21:13:24'),
(148, 'Pillsbury Flour', 'Grocery', 'All-purpose flour.', 4.49, 20, 'pillsbury-flour.jpg', '2026-05-05 21:13:24'),
(149, 'Gold Medal Flour', 'Grocery', 'All-purpose flour.', 4.49, 20, 'flour.jpg', '2026-05-05 21:13:24'),
(150, 'Mazola Corn Oil', 'Grocery', 'Corn cooking oil.', 6.99, 15, 'mazola-oil.jpg', '2026-05-05 21:13:24'),
(151, 'Mazola Vegetable Oil', 'Grocery', 'Vegetable cooking oil.', 6.99, 15, 'mazola-oil.jpg', '2026-05-05 21:13:24'),
(152, 'Goya Corn Oil', 'Grocery', 'Corn cooking oil.', 6.99, 15, 'goya-oil.jpg', '2026-05-05 21:13:24'),
(153, 'Goya Vegetable Oil', 'Grocery', 'Vegetable cooking oil.', 6.99, 15, 'goya-oil.jpg', '2026-05-05 21:13:24'),
(154, 'Goya Canola Oil', 'Grocery', 'Canola cooking oil.', 6.99, 15, 'goya-oil.jpg', '2026-05-05 21:13:24'),
(155, 'Tomato Paste', 'Grocery', 'Canned tomato paste.', 1.99, 30, 'tomato-paste.jpg', '2026-05-05 21:13:24'),
(156, 'Goya Tomato Sauce', 'Grocery', 'Canned tomato sauce.', 1.99, 30, 'tomato-sauce.jpg', '2026-05-05 21:13:24'),
(157, 'Goya Corn Can', 'Grocery', 'Canned sweet corn.', 1.99, 30, 'corn.jpg', '2026-05-05 21:13:24'),
(158, 'Goya Black Beans', 'Grocery', 'Canned black beans.', 1.99, 30, 'beans.jpg', '2026-05-05 21:13:24'),
(159, 'Goya Red Kidney Beans', 'Grocery', 'Canned red kidney beans.', 1.99, 30, 'beans.jpg', '2026-05-05 21:13:24'),
(160, 'Goya Chick Peas', 'Grocery', 'Canned chick peas.', 1.99, 30, 'chickpeas.jpg', '2026-05-05 21:13:24'),
(161, 'Salt', 'Grocery', 'Table salt.', 1.49, 30, 'salt.jpg', '2026-05-05 21:13:24'),
(162, 'Black Pepper', 'Grocery', 'Ground black pepper.', 2.99, 25, 'pepper.jpg', '2026-05-05 21:13:24'),
(163, 'Garlic Powder', 'Grocery', 'Garlic seasoning powder.', 2.99, 25, 'spices.jpg', '2026-05-05 21:13:24'),
(164, 'Onion Powder', 'Grocery', 'Onion seasoning powder.', 2.99, 25, 'spices.jpg', '2026-05-05 21:13:24'),
(165, 'Paprika', 'Grocery', 'Paprika spice.', 2.99, 25, 'spices.jpg', '2026-05-05 21:13:24'),
(166, 'Cumin Powder', 'Grocery', 'Ground cumin spice.', 2.99, 25, 'spices.jpg', '2026-05-05 21:13:24'),
(167, 'Curry Powder', 'Grocery', 'Curry spice blend.', 3.49, 25, 'spices.jpg', '2026-05-05 21:13:24'),
(168, 'White Rice 2lb', 'Grocery', 'Bag of white rice.', 3.99, 20, 'rice.jpg', '2026-05-05 21:13:24'),
(169, 'Basmati Rice 5lb', 'Grocery', 'Premium basmati rice.', 8.99, 15, 'rice.jpg', '2026-05-05 21:13:24'),
(170, 'Sugar 2lb', 'Grocery', 'Granulated sugar.', 3.49, 20, 'sugar.jpg', '2026-05-05 21:13:24'),
(171, 'Brown Sugar', 'Grocery', 'Brown sugar pack.', 3.49, 20, 'sugar.jpg', '2026-05-05 21:13:24'),
(172, 'White Bread', 'Grocery', 'Fresh packaged white bread loaf.', 3.25, 20, 'bread.jpg', '2026-05-05 21:13:24'),
(173, 'Wheat Bread', 'Grocery', 'Fresh packaged wheat bread loaf.', 3.49, 20, 'bread.jpg', '2026-05-05 21:13:24'),
(174, 'Eggs Dozen', 'Grocery', 'One dozen large eggs.', 4.99, 15, 'eggs.jpg', '2026-05-05 21:13:24'),
(175, 'Butter Sticks', 'Grocery', 'Pack of butter sticks.', 4.99, 15, 'butter.jpg', '2026-05-05 21:13:24'),
(176, 'Cream Cheese', 'Grocery', 'Cream cheese spread.', 3.99, 15, 'cream-cheese.jpg', '2026-05-05 21:13:24'),
(177, 'American Cheese Slices', 'Grocery', 'Pack of American cheese slices.', 4.99, 15, 'cheese.jpg', '2026-05-05 21:13:24'),
(178, 'Lays BBQ', 'Snacks', 'Smoky barbecue potato chips.', 2.00, 50, 'lays.jpg', '2026-05-05 21:15:41'),
(179, 'Lays Salt & Vinegar', 'Snacks', 'Tangy salt and vinegar chips.', 2.00, 50, 'lays.jpg', '2026-05-05 21:15:41'),
(180, 'Lays Wavy Original', 'Snacks', 'Thick cut wavy chips.', 2.25, 50, 'lays.jpg', '2026-05-05 21:15:41'),
(181, 'Lays Wavy BBQ', 'Snacks', 'Wavy barbecue chips.', 2.25, 50, 'lays.jpg', '2026-05-05 21:15:41'),
(182, 'Doritos Nacho Cheese', 'Snacks', 'Classic nacho cheese chips.', 2.25, 50, 'doritos.jpg', '2026-05-05 21:15:41'),
(183, 'Doritos Cool Ranch', 'Snacks', 'Cool ranch flavored chips.', 2.25, 50, 'doritos.jpg', '2026-05-05 21:15:41'),
(184, 'Doritos Spicy Nacho', 'Snacks', 'Spicy nacho tortilla chips.', 2.25, 50, 'doritos.jpg', '2026-05-05 21:15:41'),
(185, 'Doritos Sweet Chili', 'Snacks', 'Sweet chili flavored chips.', 2.25, 50, 'doritos.jpg', '2026-05-05 21:15:41'),
(186, 'Cheetos Crunchy', 'Snacks', 'Crunchy cheese snacks.', 2.25, 50, 'cheetos.jpg', '2026-05-05 21:15:41'),
(187, 'Cheetos Puffs', 'Snacks', 'Soft cheese puffs.', 2.25, 50, 'cheetos.jpg', '2026-05-05 21:15:41'),
(188, 'Cheetos Cheddar Jalapeno', 'Snacks', 'Spicy cheddar jalapeno chips.', 2.25, 50, 'cheetos.jpg', '2026-05-05 21:15:41'),
(189, 'Ruffles Original', 'Snacks', 'Classic ridged potato chips.', 2.25, 50, 'ruffles.jpg', '2026-05-05 21:15:41'),
(190, 'Ruffles Cheddar & Sour Cream', 'Snacks', 'Cheddar sour cream chips.', 2.25, 50, 'ruffles.jpg', '2026-05-05 21:15:41'),
(191, 'Ruffles BBQ', 'Snacks', 'Barbecue ridged chips.', 2.25, 50, 'ruffles.jpg', '2026-05-05 21:15:41'),
(192, 'Pringles Original', 'Snacks', 'Stackable potato crisps.', 2.75, 40, 'pringles.jpg', '2026-05-05 21:15:41'),
(193, 'Pringles Sour Cream & Onion', 'Snacks', 'Sour cream flavored crisps.', 2.75, 40, 'pringles.jpg', '2026-05-05 21:15:41'),
(194, 'Pringles BBQ', 'Snacks', 'Barbecue flavored crisps.', 2.75, 40, 'pringles.jpg', '2026-05-05 21:15:41'),
(195, 'Pringles Pizza', 'Snacks', 'Pizza flavored crisps.', 2.75, 40, 'pringles.jpg', '2026-05-05 21:15:41'),
(196, 'Takis Fuego', 'Snacks', 'Spicy chili lime rolled chips.', 2.50, 45, 'takis.jpg', '2026-05-05 21:15:41'),
(197, 'Takis Blue Heat', 'Snacks', 'Hot blue chili chips.', 2.50, 45, 'takis.jpg', '2026-05-05 21:15:41'),
(198, 'Tostitos Scoops', 'Snacks', 'Tortilla chips for dipping.', 2.50, 45, 'tostitos.jpg', '2026-05-05 21:15:41'),
(199, 'Tostitos Hint of Lime', 'Snacks', 'Lime flavored tortilla chips.', 2.50, 45, 'tostitos.jpg', '2026-05-05 21:15:41'),
(200, 'Fritos Original', 'Snacks', 'Classic corn chips.', 2.25, 50, 'fritos.jpg', '2026-05-05 21:15:41'),
(201, 'Fritos Chili Cheese', 'Snacks', 'Chili cheese corn chips.', 2.25, 50, 'fritos.jpg', '2026-05-05 21:15:41'),
(202, 'Sun Chips Harvest Cheddar', 'Snacks', 'Multigrain cheddar chips.', 2.50, 40, 'sunchips.jpg', '2026-05-05 21:15:41'),
(203, 'Sun Chips Garden Salsa', 'Snacks', 'Multigrain salsa chips.', 2.50, 40, 'sunchips.jpg', '2026-05-05 21:15:41'),
(204, 'Kettle Brand Sea Salt', 'Snacks', 'Kettle cooked sea salt chips.', 2.75, 40, 'kettle.jpg', '2026-05-05 21:15:41'),
(205, 'Kettle Brand Jalapeno', 'Snacks', 'Spicy jalapeno chips.', 2.75, 40, 'kettle.jpg', '2026-05-05 21:15:41'),
(206, 'Herrs BBQ Chips', 'Snacks', 'BBQ flavored chips.', 2.00, 50, 'chips.jpg', '2026-05-05 21:15:41'),
(207, 'Herrs Salt & Vinegar', 'Snacks', 'Salt and vinegar chips.', 2.00, 50, 'chips.jpg', '2026-05-05 21:15:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `password`, `role`, `created_at`) VALUES
(1, 'Alif Rony', 'alif1@test.com', '9171234567', '$2y$10$UobWbJMACa99Yp1G4Yggw.2W98zQ9samjTYgH.qpTZKiGZjA16m2O', 'user', '2026-04-05 17:55:41'),
(2, 'Admin User', 'admin@zafarscafe.com', '0000000000', '$2y$10$OBANy2jrJNrDFx1ANTaHD.D.CcPhFzHTZf.EvW3.P.hkx0NzusG5W', 'admin', '2026-05-22 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=208;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
