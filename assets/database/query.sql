-- 1: Create the Database
CREATE DATABASE sales_system_db;  
USE sales_system_db;

-- 2: Create Tables

-- 1. Users Table
CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'seller') NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Sellers Table
CREATE TABLE seller (
  seller_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  seller_name VARCHAR(255) NOT NULL,
  business_name VARCHAR(255),
  seller_email VARCHAR(255),
  seller_phone VARCHAR(15),
  seller_image LONGTEXT,  
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);


-- 3. Products Table
CREATE TABLE Products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    stock_qty INT NOT NULL,
    seller_id INT,
    product_image LONGTEXT,  
    category ENUM(
        'Electronics', 
        'Clothing', 
        'Home Appliances', 
        'Books', 
        'Sports', 
        'Beauty & Health', 
        'Toys', 
        'Automotive', 
        'Furniture', 
        'Groceries'
    ) NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES seller(seller_id) ON DELETE SET NULL  
);
   

-- 4. Orders Table
CREATE TABLE Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    seller_status ENUM('pending', 'shipped') DEFAULT 'pending',
    buyer_status ENUM('active', 'cancelled', 'delivered') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,  
    FOREIGN KEY (product_id) REFERENCES Products(id) ON DELETE CASCADE
);

-- 5. FeedBack Table
CREATE TABLE feedback (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  status ENUM('pending', 'reviewed', 'resolved') DEFAULT 'pending'
);

