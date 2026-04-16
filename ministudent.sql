-- Create database
CREATE DATABASE IF NOT EXISTS ministudents_db;
USE ministudents_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
);

-- Insert demo user (password: demo123)
INSERT INTO users (name, email, password) VALUES 
('Alex Rivera', 'demo@ministudent.com', '$2y$10$YourHashHere');

-- Note: Run PHP script to generate proper hash or use:
-- password_hash('demo123', PASSWORD_DEFAULT);