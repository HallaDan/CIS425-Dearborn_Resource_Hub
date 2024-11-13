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

)

CREATE TABLE IF NOT EXISTS business_es (

)

CREATE TABLE IF NOT EXISTS business_ar (

)

