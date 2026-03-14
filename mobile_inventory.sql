-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2026 at 07:15 AM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`log_id`, `user_id`, `action`, `module`, `created_at`) VALUES
(1, 1, 'Added Product', 'Products', '2026-03-14 06:14:08'),
(2, 2, 'Updated Stock', 'Stock', '2026-03-14 06:14:08'),
(3, 3, 'Created Receipt', 'Receipts', '2026-03-14 06:14:08'),
(4, 1, 'Processed Delivery', 'Deliveries', '2026-03-14 06:14:08'),
(5, 2, 'Stock Adjustment', 'Adjustments', '2026-03-14 06:14:08'),
(6, 3, 'Transfer Created', 'Transfers', '2026-03-14 06:14:08'),
(7, 1, 'Report Generated', 'Reports', '2026-03-14 06:14:08'),
(8, 2, 'Delivery Updated', 'Deliveries', '2026-03-14 06:14:08'),
(9, 3, 'Product Updated', 'Products', '2026-03-14 06:14:08'),
(10, 1, 'User Login', 'Auth', '2026-03-14 06:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `adjustments`
--

CREATE TABLE `adjustments` (
  `adjustment_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `counted_quantity` int(11) DEFAULT NULL,
  `adjustment_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adjustments`
--

INSERT INTO `adjustments` (`adjustment_id`, `product_id`, `warehouse_id`, `counted_quantity`, `adjustment_date`, `reason`) VALUES
(1, 1, 1, 48, '2026-03-05', 'Stock recount'),
(2, 2, 1, 39, '2026-03-06', 'Minor loss'),
(3, 3, 1, 34, '2026-03-07', 'Damage'),
(4, 4, 2, 58, '2026-03-08', 'Correction'),
(5, 5, 2, 29, '2026-03-09', 'Manual update'),
(6, 6, 1, 19, '2026-03-10', 'Audit'),
(7, 7, 3, 24, '2026-03-11', 'Recount'),
(8, 8, 3, 14, '2026-03-12', 'Broken item'),
(9, 9, 2, 17, '2026-03-13', 'Correction'),
(10, 10, 1, 98, '2026-03-14', 'Inventory check');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Smartphones'),
(2, 'Tablets'),
(3, 'Accessories'),
(4, 'Wearables'),
(5, 'Audio');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `customer_name` varchar(150) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` enum('draft','ready','done','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`delivery_id`, `customer_name`, `warehouse_id`, `delivery_date`, `status`) VALUES
(1, 'Ramesh Patel', 1, '2026-03-05', 'done'),
(2, 'Suresh Shah', 1, '2026-03-06', 'done'),
(3, 'Amit Mehta', 2, '2026-03-07', 'ready'),
(4, 'Neha Joshi', 3, '2026-03-08', 'draft'),
(5, 'Priya Desai', 1, '2026-03-09', 'done'),
(6, 'Karan Shah', 2, '2026-03-10', 'ready'),
(7, 'Rahul Trivedi', 3, '2026-03-11', 'draft'),
(8, 'Sneha Patel', 1, '2026-03-12', 'done'),
(9, 'Jay Shah', 2, '2026-03-13', 'done'),
(10, 'Ankit Bhatt', 3, '2026-03-14', 'ready');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `item_id` int(11) NOT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `delivery_items`
--

INSERT INTO `delivery_items` (`item_id`, `delivery_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 2),
(2, 2, 2, 1),
(3, 3, 3, 2),
(4, 4, 4, 1),
(5, 5, 5, 1),
(6, 6, 6, 1),
(7, 7, 7, 1),
(8, 8, 8, 1),
(9, 9, 9, 1),
(10, 10, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(150) DEFAULT NULL,
  `sku` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `reorder_level` int(11) DEFAULT 10,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `sku`, `category_id`, `unit`, `reorder_level`, `created_at`) VALUES
(1, 'iPhone 15 Pro', 'SKU-IP15P', 1, 'pcs', 10, '2026-03-14 06:14:06'),
(2, 'Samsung Galaxy S23', 'SKU-SGS23', 1, 'pcs', 10, '2026-03-14 06:14:06'),
(3, 'OnePlus 12', 'SKU-OP12', 1, 'pcs', 10, '2026-03-14 06:14:06'),
(4, 'Xiaomi Redmi Note 13', 'SKU-RN13', 1, 'pcs', 15, '2026-03-14 06:14:06'),
(5, 'Realme GT 6', 'SKU-RGT6', 1, 'pcs', 12, '2026-03-14 06:14:06'),
(6, 'iPad Air 5', 'SKU-IPADA5', 2, 'pcs', 8, '2026-03-14 06:14:06'),
(7, 'Samsung Galaxy Tab S9', 'SKU-TABS9', 2, 'pcs', 8, '2026-03-14 06:14:06'),
(8, 'Apple Watch Series 9', 'SKU-AW9', 4, 'pcs', 6, '2026-03-14 06:14:06'),
(9, 'Samsung Galaxy Watch 6', 'SKU-SGW6', 4, 'pcs', 6, '2026-03-14 06:14:06'),
(10, 'Boat Airdopes 441', 'SKU-BA441', 5, 'pcs', 20, '2026-03-14 06:14:06'),
(11, 'Sony WH-1000XM5', 'SKU-SONYXM5', 5, 'pcs', 10, '2026-03-14 06:14:06'),
(12, 'Anker Fast Charger', 'SKU-ANKFC', 3, 'pcs', 25, '2026-03-14 06:14:06'),
(13, 'Spigen Mobile Case', 'SKU-SPCASE', 3, 'pcs', 30, '2026-03-14 06:14:06'),
(14, 'Logitech Bluetooth Keyboard', 'SKU-LKB', 3, 'pcs', 10, '2026-03-14 06:14:06'),
(15, 'JBL Flip 6 Speaker', 'SKU-JBLF6', 5, 'pcs', 12, '2026-03-14 06:14:06');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `status` enum('draft','waiting','done','cancelled') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`receipt_id`, `supplier_id`, `warehouse_id`, `receipt_date`, `status`) VALUES
(1, 1, 1, '2026-03-01', 'done'),
(2, 2, 1, '2026-03-02', 'done'),
(3, 3, 2, '2026-03-03', 'done'),
(4, 4, 2, '2026-03-04', 'waiting'),
(5, 5, 3, '2026-03-05', 'done'),
(6, 1, 1, '2026-03-06', 'done'),
(7, 2, 3, '2026-03-07', 'waiting'),
(8, 3, 2, '2026-03-08', 'done'),
(9, 4, 1, '2026-03-09', 'done'),
(10, 5, 3, '2026-03-10', 'draft');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_items`
--

CREATE TABLE `receipt_items` (
  `item_id` int(11) NOT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipt_items`
--

INSERT INTO `receipt_items` (`item_id`, `receipt_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 20),
(2, 2, 2, 15),
(3, 3, 3, 25),
(4, 4, 4, 30),
(5, 5, 5, 12),
(6, 6, 6, 10),
(7, 7, 7, 9),
(8, 8, 8, 7),
(9, 9, 9, 11),
(10, 10, 10, 40);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `delivery_id` int(11) DEFAULT NULL,
  `report_name` varchar(150) DEFAULT NULL,
  `pdf_file` longblob DEFAULT NULL,
  `generated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`report_id`, `delivery_id`, `report_name`, `pdf_file`, `generated_at`) VALUES
(1, 1, 'Delivery_Report_1', NULL, '2026-03-14 06:14:08'),
(2, 2, 'Delivery_Report_2', NULL, '2026-03-14 06:14:08'),
(3, 3, 'Delivery_Report_3', NULL, '2026-03-14 06:14:08'),
(4, 4, 'Delivery_Report_4', NULL, '2026-03-14 06:14:08'),
(5, 5, 'Delivery_Report_5', NULL, '2026-03-14 06:14:08'),
(6, 6, 'Delivery_Report_6', NULL, '2026-03-14 06:14:08'),
(7, 7, 'Delivery_Report_7', NULL, '2026-03-14 06:14:08'),
(8, 8, 'Delivery_Report_8', NULL, '2026-03-14 06:14:08'),
(9, 9, 'Delivery_Report_9', NULL, '2026-03-14 06:14:08'),
(10, 10, 'Delivery_Report_10', NULL, '2026-03-14 06:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `product_id`, `warehouse_id`, `quantity`) VALUES
(1, 1, 1, 50),
(2, 2, 1, 40),
(3, 3, 1, 35),
(4, 4, 2, 60),
(5, 5, 2, 30),
(6, 6, 1, 20),
(7, 7, 3, 25),
(8, 8, 3, 15),
(9, 9, 2, 18),
(10, 10, 1, 100);

-- --------------------------------------------------------

--
-- Table structure for table `stock_ledger`
--

CREATE TABLE `stock_ledger` (
  `ledger_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `change_qty` int(11) DEFAULT NULL,
  `movement_type` enum('receipt','delivery','transfer','adjustment') DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_ledger`
--

INSERT INTO `stock_ledger` (`ledger_id`, `product_id`, `warehouse_id`, `change_qty`, `movement_type`, `reference_id`, `created_at`) VALUES
(1, 1, 1, 20, 'receipt', 1, '2026-03-14 06:14:08'),
(2, 2, 1, 15, 'receipt', 2, '2026-03-14 06:14:08'),
(3, 3, 1, -2, 'delivery', 1, '2026-03-14 06:14:08'),
(4, 4, 2, 30, 'receipt', 4, '2026-03-14 06:14:08'),
(5, 5, 2, -1, 'delivery', 5, '2026-03-14 06:14:08'),
(6, 6, 1, 10, 'receipt', 6, '2026-03-14 06:14:08'),
(7, 7, 3, -1, 'delivery', 7, '2026-03-14 06:14:08'),
(8, 8, 3, 7, 'receipt', 8, '2026-03-14 06:14:08'),
(9, 9, 2, -1, 'delivery', 9, '2026-03-14 06:14:08'),
(10, 10, 1, 40, 'receipt', 10, '2026-03-14 06:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `phone`, `address`) VALUES
(1, 'Tech Distributors', '9876543210', 'Ahmedabad'),
(2, 'Mobile Hub Supply', '9876543211', 'Mumbai'),
(3, 'Digital World Supply', '9876543212', 'Delhi'),
(4, 'Prime Electronics', '9876543213', 'Bangalore'),
(5, 'NextGen Devices', '9876543214', 'Pune');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `transfer_id` int(11) NOT NULL,
  `from_warehouse` int(11) DEFAULT NULL,
  `to_warehouse` int(11) DEFAULT NULL,
  `transfer_date` date DEFAULT NULL,
  `status` enum('draft','done') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfers`
--

INSERT INTO `transfers` (`transfer_id`, `from_warehouse`, `to_warehouse`, `transfer_date`, `status`) VALUES
(1, 1, 2, '2026-03-02', 'done'),
(2, 2, 3, '2026-03-03', 'done'),
(3, 1, 3, '2026-03-04', 'draft'),
(4, 3, 1, '2026-03-05', 'done'),
(5, 2, 1, '2026-03-06', 'done'),
(6, 1, 2, '2026-03-07', 'draft'),
(7, 3, 2, '2026-03-08', 'done'),
(8, 2, 3, '2026-03-09', 'done'),
(9, 1, 3, '2026-03-10', 'draft'),
(10, 3, 1, '2026-03-11', 'done');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_items`
--

CREATE TABLE `transfer_items` (
  `item_id` int(11) NOT NULL,
  `transfer_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfer_items`
--

INSERT INTO `transfer_items` (`item_id`, `transfer_id`, `product_id`, `quantity`) VALUES
(1, 1, 1, 5),
(2, 2, 2, 3),
(3, 3, 3, 4),
(4, 4, 4, 6),
(5, 5, 5, 2),
(6, 6, 6, 1),
(7, 7, 7, 2),
(8, 8, 8, 1),
(9, 9, 9, 2),
(10, 10, 10, 3);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('manager','staff') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Yug patel', 'yugpatel.23.cse@iite.indusuni.ac.in', '$2y$10$9zDPPo233DTMhPLSqysxn.r./6fZvH4SEGTKWhPHdC7fCpbpXKV1i', 'staff', '2026-03-14 04:03:56'),
(2, 'Keval Sheth', 'kevalsheth.23.cse@iite.indusuni.ac.in', '$2y$10$oVd/Bmt60qQt5KLu7xNRQu9Y3koZ7Nr6ufBQVzhcG7yjjrI2usQIC', 'staff', '2026-03-14 06:01:15'),
(3, 'Gautum Makwana', 'gautummakwana.23.cse@iite.indusuni.ac.in', '$2y$10$VDhHRw88GRkDVQSo9LXWPeFBZZDpDP/MjOVrCnNr7QIsJsVL/AeNC', 'staff', '2026-03-14 06:02:05');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `warehouse_id` int(11) NOT NULL,
  `warehouse_name` varchar(100) DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`warehouse_id`, `warehouse_name`, `location`) VALUES
(1, 'Main Warehouse', 'Ahmedabad'),
(2, 'Backup Warehouse', 'Surat'),
(3, 'City Store Warehouse', 'Vadodara');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `adjustments`
--
ALTER TABLE `adjustments`
  ADD PRIMARY KEY (`adjustment_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `delivery_id` (`delivery_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `receipt_id` (`receipt_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `delivery_id` (`delivery_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `stock_ledger`
--
ALTER TABLE `stock_ledger`
  ADD PRIMARY KEY (`ledger_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `from_warehouse` (`from_warehouse`),
  ADD KEY `to_warehouse` (`to_warehouse`);

--
-- Indexes for table `transfer_items`
--
ALTER TABLE `transfer_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `transfer_id` (`transfer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`warehouse_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `adjustments`
--
ALTER TABLE `adjustments`
  MODIFY `adjustment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `receipt_items`
--
ALTER TABLE `receipt_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stock_ledger`
--
ALTER TABLE `stock_ledger`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transfer_items`
--
ALTER TABLE `transfer_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `warehouse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `adjustments`
--
ALTER TABLE `adjustments`
  ADD CONSTRAINT `adjustments_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `adjustments_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `delivery_items_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`delivery_id`),
  ADD CONSTRAINT `delivery_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `receipts`
--
ALTER TABLE `receipts`
  ADD CONSTRAINT `receipts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  ADD CONSTRAINT `receipts_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `receipt_items`
--
ALTER TABLE `receipt_items`
  ADD CONSTRAINT `receipt_items_ibfk_1` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`receipt_id`),
  ADD CONSTRAINT `receipt_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`delivery_id`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `stock_ledger`
--
ALTER TABLE `stock_ledger`
  ADD CONSTRAINT `stock_ledger_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `stock_ledger_ibfk_2` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `transfers`
--
ALTER TABLE `transfers`
  ADD CONSTRAINT `transfers_ibfk_1` FOREIGN KEY (`from_warehouse`) REFERENCES `warehouses` (`warehouse_id`),
  ADD CONSTRAINT `transfers_ibfk_2` FOREIGN KEY (`to_warehouse`) REFERENCES `warehouses` (`warehouse_id`);

--
-- Constraints for table `transfer_items`
--
ALTER TABLE `transfer_items`
  ADD CONSTRAINT `transfer_items_ibfk_1` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`transfer_id`),
  ADD CONSTRAINT `transfer_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
