-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 08, 2025 at 08:03 PM
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
-- Database: `plantopia`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `product_id`, `session_id`, `quantity`, `created_at`) VALUES
(9, 1, 'n6vkjmv7r18dnl3ovueb4ud2kl', 1, '2025-08-03 18:04:37'),
(10, 7, 'p3ni776159igqoq1l2id7kr4rg', 1, '2025-08-03 20:04:39'),
(11, 2, 'eabp1so7qf4628ct1t86fudeps', 1, '2025-08-03 20:05:10'),
(19, 1, 'jjt9e6a75haomog4ecg1ua9cgq', 1, '2025-08-04 17:37:29'),
(20, 2, 'jjt9e6a75haomog4ecg1ua9cgq', 1, '2025-08-04 17:48:33'),
(29, 2, 'jqk43s194sbu7o0ap4prq1prt9', 1, '2025-08-08 09:30:39'),
(30, 3, 'jqk43s194sbu7o0ap4prq1prt9', 1, '2025-08-08 09:30:40'),
(31, 4, 'jqk43s194sbu7o0ap4prq1prt9', 1, '2025-08-08 09:30:42');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `email`, `phone`, `address`, `total_price`, `session_id`, `created_at`) VALUES
(1, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'igr49m5mdfmbb67do5joiu5h5t', '2025-08-03 17:10:04'),
(2, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 1196.00, 'igr49m5mdfmbb67do5joiu5h5t', '2025-08-03 17:10:54'),
(3, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 1595.00, 'igr49m5mdfmbb67do5joiu5h5t', '2025-08-03 17:14:29'),
(4, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'n6vkjmv7r18dnl3ovueb4ud2kl', '2025-08-03 18:05:25'),
(5, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 897.00, '19ibis02p8majmvef7q21r3sr2', '2025-08-04 02:07:46'),
(6, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'rbtu44g1nnq65m581ipjlihoe4', '2025-08-04 04:56:35'),
(7, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'rbtu44g1nnq65m581ipjlihoe4', '2025-08-04 15:37:02'),
(8, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 698.00, '3qcegfbo3qqa1lu196cumh91e1', '2025-08-04 15:39:10'),
(9, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'entmh0g31sq7kefmor6n2rv9v4', '2025-08-04 17:49:17'),
(10, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 598.00, 'hse6pe7f7gneae3sga4i6jijf5', '2025-08-05 16:17:46'),
(11, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'fgqa958ba13o5c351o2ep8m67c', '2025-08-08 06:10:13'),
(12, 'Venkateshwar Rao', 'venkatarella@gmail.com', '9985420384', '16/11/775A Sharadha Nilayam,Moosarambagh', 897.00, 'jqk43s194sbu7o0ap4prq1prt9', '2025-08-08 09:09:13'),
(13, 'Venkateshwar Rao', 'venkatarella@gmail.com', '9985420384', '16/11/775A Sharadha Nilayam,Moosarambagh', 299.00, 'jqk43s194sbu7o0ap4prq1prt9', '2025-08-08 09:19:09');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 299.00),
(2, 2, 1, 1, 299.00),
(3, 2, 2, 1, 299.00),
(4, 2, 3, 1, 299.00),
(5, 2, 4, 1, 299.00),
(6, 3, 1, 2, 299.00),
(7, 3, 2, 2, 299.00),
(8, 3, 7, 1, 399.00),
(9, 4, 1, 1, 299.00),
(10, 5, 2, 1, 299.00),
(11, 5, 4, 2, 299.00),
(12, 6, 4, 1, 299.00),
(13, 7, 2, 1, 299.00),
(14, 8, 3, 1, 299.00),
(15, 8, 7, 1, 399.00),
(16, 9, 3, 1, 299.00),
(17, 10, 2, 2, 299.00),
(18, 11, 2, 1, 299.00),
(19, 12, 2, 2, 299.00),
(20, 12, 6, 1, 299.00),
(21, 13, 3, 1, 299.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Aralia Green Plant', 'The Aralia Green Plant is a lush, low-maintenance indoor plant that enhances décor while purifying the air.', 299.00, '15.png', '2025-08-03 16:57:47'),
(2, 'Golden Hahnii Snake Plant', 'The Golden Hahnii Snake Plant in a medium self-watering pot adds elegance, purifies air, and thrives with minimal care.', 299.00, '12.png', '2025-08-03 16:57:47'),
(3, 'Aglaonema Plant', 'Aglaonema, a vibrant and low-maintenance indoor plant, enhances spaces with its air-purifying properties and strike.', 299.00, '13.png', '2025-08-03 16:57:47'),
(4, 'Syngonium Pink Plant', 'The Syngonium Pink Plant adds a touch of elegance with its beautiful pink-hued leaves, perfect for air purification and low-maintenance.', 299.00, '14.png', '2025-08-03 16:57:47'),
(5, 'Lucky Jade Plant', 'It symbolizes prosperity and good fortune, making it a perfect addition to any home or office', 299.00, '11.png', '2025-08-03 16:57:47'),
(6, 'Calathea Triostar', 'The Calathea Triostar dazzles with its vibrant pink, green, and white foliage, making it a stunning, air-purifying indoor plant.', 299.00, '16.png', '2025-08-03 16:57:47'),
(7, 'Lucky Jade Plant With Self Watering Pot', 'Bring prosperity and positive energy into your space with the Lucky Jade. Known for its vibrant green leaves and symbolic connection to wealth.', 399.00, '19.png', '2025-08-03 16:57:47'),
(8, 'Money Plant', 'Brings luck and prosperity to your home with its beautiful trailing vines.', 249.00, '20.png', '2025-08-03 16:57:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'Tejaswi Arella', '22311A6677@sreenidhi.edu.in', '8317580631', '$2y$10$ueVbFxVaR2cPzCNLc4zYLe5j7dxS/.IXfWNwzLyQJdNwBfMHfv3pS', '2025-08-03 17:53:44'),
(2, 'Admin User', 'admin@plantopia.com', '9876543210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-08-03 17:58:43'),
(3, 'varsha', '22311A6677@aimlsreenidhi.edu.in', '9985420384', '$2y$10$0N89QHWUUbvq3SMCHOYlbuWd9Kl4S/0q9JCstbKElOMThk6r1hUfC', '2025-08-03 18:31:45'),
(4, 'Venkateshwar Rao', 'venkatarella@gmail.com', '9985420384', '$2y$10$Le9Scnwu8C8cM.oQd/xQLewcEpERFh2n3..vSS0LBJdKO80OPyg.q', '2025-08-08 09:07:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
