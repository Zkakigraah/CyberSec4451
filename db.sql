-- Create the database
CREATE DATABASE IF NOT EXISTS socialnet;
USE socialnet;

-- Create the table according to assignment specs
CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT
);

-- Note: Passwords must be hashed using PHP's password_hash()
