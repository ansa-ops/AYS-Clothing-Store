
-- Data Layer
-- This SQL file creates the AYS Clothing Store database.
-- It includes customer tables, product tables, order tables,
-- test data and stored procedures for the customer management system.
CREATE DATABASE IF NOT EXISTS ays_clothing_store;
USE ays_clothing_store;

DROP PROCEDURE IF EXISTS AddCustomer;
DROP PROCEDURE IF EXISTS UpdateCustomer;
DROP PROCEDURE IF EXISTS DeactivateCustomer;
DROP PROCEDURE IF EXISTS GetAllCustomers;
DROP PROCEDURE IF EXISTS FindCustomer;
DROP PROCEDURE IF EXISTS FilterCustomers;
DROP PROCEDURE IF EXISTS LoginCustomer;
DROP PROCEDURE IF EXISTS UpdateCustomerMembership;

DROP TABLE IF EXISTS OrderDetail;
DROP TABLE IF EXISTS Orders;
DROP TABLE IF EXISTS CustomerMembership;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Customer;

CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL,
    PhoneNumber VARCHAR(20) NOT NULL,
    IsActive BOOLEAN NOT NULL DEFAULT TRUE,
    DateCreated DATE NOT NULL
);

CREATE TABLE CustomerMembership (
    MembershipID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    MembershipType VARCHAR(20) NOT NULL DEFAULT 'Bronze',
    DiscountRate INT NOT NULL DEFAULT 5,
    Points INT NOT NULL DEFAULT 0,
    DateJoined DATE NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE Product (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(100) NOT NULL,
    Category VARCHAR(50) NOT NULL,
    Gender VARCHAR(20) NOT NULL,
    Size VARCHAR(40) NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    Stock INT NOT NULL,
    Description VARCHAR(255),
    IsAvailable BOOLEAN DEFAULT TRUE,
    Image VARCHAR(255)
);

CREATE TABLE Orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    CustomerID INT NOT NULL,
    OrderDate DATETIME NOT NULL,
    PaymentMethod VARCHAR(50) NOT NULL,
    Status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    SubTotal DECIMAL(10,2) NOT NULL,
    DiscountRate INT NOT NULL,
    FinalTotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
);

CREATE TABLE OrderDetail (
    OrderDetailID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT NOT NULL,
    SelectedSize VARCHAR(10) NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES Orders(OrderID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

INSERT INTO Customer (FullName, Email, Password, PhoneNumber, IsActive, DateCreated) VALUES
('AYS Demo Customer','demo@ays.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','07111111111',TRUE,CURDATE()),
('Sophia Williams','sophia@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','07123456780',TRUE,CURDATE()),
('Emily Johnson','emily@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','07234567891',TRUE,CURDATE()),
('Ava Martinez','ava@gmail.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.','07456789111',FALSE,CURDATE());

INSERT INTO CustomerMembership (CustomerID, MembershipType, DiscountRate, Points, DateJoined) VALUES
(1,'Gold',15,260,CURDATE()),(2,'Silver',10,130,CURDATE()),(3,'Bronze',5,40,CURDATE()),(4,'Bronze',5,10,CURDATE());

INSERT INTO Product (ProductName, Category, Gender, Size, Price, Stock, Description, IsAvailable, Image) VALUES
('Floral Summer Top','Women','Women','S, M, L, XL',22.99,20,'Stylish floral summer top for women.',TRUE,'floral summer top.webp'),
('Mini Skirt','Women','Women','S, M, L',18.99,15,'Trendy mini skirt for a modern look.',TRUE,'mini skirt.webp'),
('Maxi Dress','Women','Women','S, M, L, XL',34.99,12,'Elegant maxi dress for women.',TRUE,'maxi dress.webp'),
('High Waist Jeans','Women','Women','S, M, L',28.99,18,'Comfortable high waist jeans for women.',TRUE,'high weist jeans.webp'),
('Men T-Shirt','Men','Men','M, L, XL',14.99,30,'Comfortable casual t-shirt for men.',TRUE,'men tshirt.webp'),
('Men Shirt','Men','Men','M, L, XL',24.99,25,'Smart casual shirt for men.',TRUE,'Men shirt.webp'),
('Men Denim Jacket','Men','Men','M, L, XL',39.99,10,'Stylish denim jacket for men.',TRUE,'men denim jacket.webp'),
('Men Jeans Pant','Men','Men','M, L, XL',29.99,22,'Classic jeans pant for men.',TRUE,'men jeans pant.webp'),
('Men Joggers Pant','Men','Men','M, L, XL',22.99,18,'Comfortable joggers for men.',TRUE,'men joggers pant.webp'),
('Men Leather Jacket','Men','Men','M, L, XL',49.99,8,'Premium leather jacket for men.',TRUE,'men leather jacket.webp'),
('Kids Denim Jeans','Children','Children','Age 5-10',19.99,25,'Comfortable denim jeans for children.',TRUE,'kids denim jeans.webp'),
('Kids Floral Dress','Children','Children','Age 4-9',24.99,18,'Beautiful floral dress for children.',TRUE,'kids floral dress.webp'),
('Kids Hoodie','Children','Children','Age 5-12',18.99,20,'Warm hoodie for children.',TRUE,'kids hoddiee.webp'),
('Kids Jacket','Children','Children','Age 6-12',29.99,16,'Stylish jacket for children.',TRUE,'kids jacket.webp'),
('Kids Romper Set','Children','Children','Age 1-4',16.99,14,'Cute romper set for younger children.',TRUE,'kids romper set.webp'),
('Kids Winter Pajama','Children','Children','Age 4-10',21.99,20,'Warm winter pajama for children.',TRUE,'kids winter pajama.webp');

DELIMITER //
CREATE PROCEDURE AddCustomer(IN p_FullName VARCHAR(100), IN p_Email VARCHAR(100), IN p_Password VARCHAR(255), IN p_PhoneNumber VARCHAR(20), IN p_IsActive BOOLEAN, IN p_DateCreated DATE, IN p_MembershipType VARCHAR(20), IN p_DiscountRate INT)
BEGIN
 INSERT INTO Customer (FullName, Email, Password, PhoneNumber, IsActive, DateCreated) VALUES (p_FullName,p_Email,p_Password,p_PhoneNumber,p_IsActive,p_DateCreated);
 INSERT INTO CustomerMembership (CustomerID, MembershipType, DiscountRate, Points, DateJoined) VALUES (LAST_INSERT_ID(),p_MembershipType,p_DiscountRate,0,CURDATE());
END //
CREATE PROCEDURE UpdateCustomer(IN p_CustomerID INT, IN p_FullName VARCHAR(100), IN p_Email VARCHAR(100), IN p_PhoneNumber VARCHAR(20), IN p_IsActive BOOLEAN)
BEGIN
 UPDATE Customer SET FullName=p_FullName, Email=p_Email, PhoneNumber=p_PhoneNumber, IsActive=p_IsActive WHERE CustomerID=p_CustomerID;
END //
CREATE PROCEDURE DeactivateCustomer(IN p_CustomerID INT)
BEGIN
 UPDATE Customer SET IsActive=FALSE WHERE CustomerID=p_CustomerID;
END //
CREATE PROCEDURE GetAllCustomers()
BEGIN
 SELECT c.CustomerID,c.FullName,c.Email,c.PhoneNumber,c.IsActive,c.DateCreated,m.MembershipType,m.DiscountRate,m.Points
 FROM Customer c LEFT JOIN CustomerMembership m ON c.CustomerID=m.CustomerID ORDER BY c.CustomerID DESC;
END //
CREATE PROCEDURE FindCustomer(IN p_Search VARCHAR(100))
BEGIN
 SELECT c.CustomerID,c.FullName,c.Email,c.PhoneNumber,c.IsActive,c.DateCreated,m.MembershipType,m.DiscountRate,m.Points
 FROM Customer c LEFT JOIN CustomerMembership m ON c.CustomerID=m.CustomerID
 WHERE c.FullName LIKE CONCAT('%',p_Search,'%') OR c.Email LIKE CONCAT('%',p_Search,'%') OR c.PhoneNumber LIKE CONCAT('%',p_Search,'%') OR c.CustomerID=p_Search
 ORDER BY c.CustomerID DESC;
END //
CREATE PROCEDURE FilterCustomers(IN p_IsActive BOOLEAN)
BEGIN
 SELECT c.CustomerID,c.FullName,c.Email,c.PhoneNumber,c.IsActive,c.DateCreated,m.MembershipType,m.DiscountRate,m.Points
 FROM Customer c LEFT JOIN CustomerMembership m ON c.CustomerID=m.CustomerID WHERE c.IsActive=p_IsActive ORDER BY c.CustomerID DESC;
END //
CREATE PROCEDURE LoginCustomer(IN p_Email VARCHAR(100))
BEGIN
 SELECT c.CustomerID,c.FullName,c.Email,c.Password,c.PhoneNumber,c.IsActive,c.DateCreated,m.MembershipType,m.DiscountRate,m.Points
 FROM Customer c LEFT JOIN CustomerMembership m ON c.CustomerID=m.CustomerID WHERE c.Email=p_Email AND c.IsActive=TRUE LIMIT 1;
END //
CREATE PROCEDURE UpdateCustomerMembership(IN p_CustomerID INT, IN p_MembershipType VARCHAR(20), IN p_DiscountRate INT, IN p_Points INT)
BEGIN
 UPDATE CustomerMembership SET MembershipType=p_MembershipType, DiscountRate=p_DiscountRate, Points=p_Points WHERE CustomerID=p_CustomerID;
END //
DELIMITER ;
