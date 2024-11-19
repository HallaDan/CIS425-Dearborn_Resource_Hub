-- Create the database
CREATE DATABASE IF NOT EXISTS resource_hub_db;

-- Switch to the database
USE resource_hub_db;

-- Create the `items` table
CREATE TABLE IF NOT EXISTS users (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS business_en (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    enBusinessID INT(6) NOT NULL,
    enBusinessName VARCHAR(255) NOT NULL,
    enBusinessCategory VARCHAR(50) NOT NULL,
    enAddress VARCHAR(255) NOT NULL,
    enBusinessPhone VARCHAR(50) NOT NULL,
    enWebsite VARCHAR(50) NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN key (enBusinessID) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
 )

CREATE TABLE IF NOT EXISTS business_es (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    esBusinessID INT(6) NOT NULL,
    esBusinessName VARCHAR(255) NOT NULL,
    esBusinessCategory VARCHAR(50) NOT NULL,
    esAddress VARCHAR(255) NOT NULL,
    esBusinessPhone VARCHAR(50) NOT NULL,
    esWebsite VARCHAR(50) NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN key (esBusinessID) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)

CREATE TABLE IF NOT EXISTS business_ar (
    id INT(6) AUTO_INCREMENT PRIMARY KEY,
    arBusinessID INT(6) NOT NULL,
    arBusinessName VARCHAR(255) NOT NULL,
    arBusinessCategory VARCHAR(50) NOT NULL,
    arAddress VARCHAR(255) NOT NULL,
    arBusinessPhone VARCHAR(50) NOT NULL,
    arWebsite VARCHAR(50) NOT NULL,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN key (arBusinessID) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
)

