-- Email Authentication Database Setup
-- Run these SQL commands in your MySQL database

-- Create Database
CREATE DATABASE IF NOT EXISTS email_auth_db;
USE email_auth_db;

-- Create Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    verification_code VARCHAR(255) UNIQUE,
    is_verified TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_verification_code (verification_code)
);

-- Insert Sample Data (Optional)
-- INSERT INTO users (email, password, verification_code, is_verified) 
-- VALUES ('test@example.com', PASSWORD('password123'), NULL, 1);
