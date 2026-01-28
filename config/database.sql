CREATE DATABASE IF NOT EXISTS netfish CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE netfish;

CREATE TABLE IF NOT EXISTS user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS passwords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    reset_hash VARCHAR(255) DEFAULT NULL,
    reset_expires DATETIME DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS movie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    year INT(4),
    description TEXT,
    cover_image VARCHAR(255),
    tmdb_id INT DEFAULT NULL,
    video_type ENUM('youtube', 'vimeo', 'direct', 'embed') DEFAULT 'embed',
    video_id VARCHAR(255) DEFAULT NULL,
    rating DECIMAL(3,1) DEFAULT NULL,
    duration INT DEFAULT NULL,
    genre VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Voorbeeld admin account (wachtwoord: admin123)
INSERT INTO user (username, email, is_admin) VALUES ('admin', 'admin@netfish.com', 1);
INSERT INTO passwords (user_id, password_hash) VALUES (1, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
