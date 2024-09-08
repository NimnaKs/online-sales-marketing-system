-- 1: Create the Database
CREATE DATABASE sales_system_db;  
USE sales_system_db;

-- 2: Create Tables

-- 1. Admin Table
CREATE TABLE `admin_table` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(200) NOT NULL,
  `admin_image` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
);

INSERT INTO `admin_table` (`admin_id`, `admin_name`, `admin_email`, `admin_image`, `admin_password`) VALUES
(1, 'nimnaks', 'nimnaks@gmail.com', 'logo after 3d_2.png', '$2y$10$M/A/r5j/GSeJrAZxI8NtRu9eG5yNltfgTrfQVoClfSIF/pzNUXa2W');

-- 2. Brand Table
CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_title` varchar(100) NOT NULL,
  PRIMARY KEY (`brand_id`)
);

INSERT INTO `brands` (`brand_id`, `brand_title`) VALUES
(1, 'Canon'),
(2, 'Lenovo'),
(3, 'Nike'),
(4, 'Dell'),
(5, 'Polo'),
(6, 'Hp'),
(7, 'Apple'),
(8, 'Oppo'),
(9, 'Other'),
(10, 'Samsung'),
(13, 'Nokia');

-- 3. card_details Table
CREATE TABLE `card_details` (
  `product_id` int(11) NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `quantity` int(100) NOT NULL,
  PRIMARY KEY (`product_id`)
);

-- 4. categories Table
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_title` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
);

INSERT INTO `categories` (`category_id`, `category_title`) VALUES
(1, 'Mobiles'),
(2, 'Books'),
(3, 'Food'),
(4, 'Clothes'),
(5, 'HeadPhones'),
(6, 'Electronics'),
(7, 'Accessories');

-- 5. orders_pending Table
CREATE TABLE `orders_pending` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `invoice_number` int(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(255) NOT NULL,
  `order_status` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`)
);

INSERT INTO `orders_pending` (`order_id`, `user_id`, `invoice_number`, `product_id`, `quantity`, `order_status`) VALUES
(1, 1, 312346784, 1, 3, 'pending'),
(2, 1, 312346784, 2, 1, 'pending'),
(3, 1, 312346784, 4, 1, 'pending'),
(4, 1, 1918753782, 3, 2, 'pending'),
(5, 1, 351837813, 1, 2, 'pending');

-- 6. products Table
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_title` varchar(120) NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_keywords` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_image_one` varchar(255) NOT NULL,
  `product_image_two` varchar(255) NOT NULL,
  `product_image_three` varchar(255) NOT NULL,
  `product_price` float NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`product_id`)
);

INSERT INTO `products` (`product_id`, `product_title`, `product_description`, `product_keywords`, `category_id`, `brand_id`, `product_image_one`, `product_image_two`, `product_image_three`, `product_price`, `date`, `status`) VALUES
(1, 'HAVIT HV-G92 Gamepad', 'allows you to use the familiar layout and buttons to enjoy console control when playing games on your PC', 'gamepad , havit , hv-g92 , logistech', 6, 9, 'havit1.png', 'havit2.png', 'havit1.png', 120, '2023-08-29 18:05:15', 'true'),
(2, 'ASUS FHD Gaming Laptop', 'Laptop ASUS TUF Gaming F15 FX506HF-HN001W(11th Intel® Core™ i5 11400H - Ram 8GB - Hard 512 GB SSD - GPU Nvidia Geforce RTX™ 2050 4GB - Display 15.6 4k', 'Laptop , gaming , asus , intell 11', 6, 2, 'lap1.png', 'lap2.png', 'lap3.png', 700, '2023-10-25 02:06:58', 'true'),
(3, 'CANON EOS DSLR Camera', 'High Image Quality with 32.5 Megapixel CMOS (APS-C) Sensor', 'Canon, camera , high quality, 4k', 6, 1, 'camera1.png', 'camera2.png', 'camera3.png', 380, '2023-08-29 18:13:22', 'true'),
(4, 'Breed Dry Dog Food', 'Chicken, chicken by-product meal', 'food, dog food, cat food', 3, 9, 'food1.png', 'food2.png', 'food3.png', 100, '2023-10-25 01:41:31', 'true');

-- 7. user_orders Table
CREATE TABLE `user_orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount_due` int(255) NOT NULL,
  `invoice_number` int(255) NOT NULL,
  `total_products` int(255) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `order_status` varchar(255) NOT NULL,
  PRIMARY KEY (`order_id`)
);

INSERT INTO `user_orders` (`order_id`, `user_id`, `amount_due`, `invoice_number`, `total_products`, `order_date`, `order_status`) VALUES
(1, 1, 1160, 312346784, 3, '2023-10-22 15:31:20', 'paid'),
(2, 1, 760, 1918753782, 1, '2023-10-24 00:25:10', 'pending'),
(3, 1, 240, 351837813, 1, '2023-10-24 18:41:02', 'pending');

-- 8. user_payments Table
CREATE TABLE `user_payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `invoice_number` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_payments` (`payment_id`, `order_id`, `invoice_number`, `amount`, `payment_method`, `payment_date`) VALUES
(1, 1, 312346784, 1160, 'paypal', '2023-10-24 00:23:26');

-- 9. user_table Table
CREATE TABLE `user_table` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_table` (`user_id`, `username`, `user_email`, `user_image`, `user_password`) VALUES
(1, 'kaveesha', 'kaveesha@gmail.com', 'logo after 3d_2.png', '$2y$10$WPEf5.JG2JvDG91jNnOVM.OTREUOPvPAGorNCJqTgz0tJDARirFeS');
