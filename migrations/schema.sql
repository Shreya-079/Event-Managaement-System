-- Create Database
CREATE DATABASE IF NOT EXISTS event_management
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE event_management;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('attendee','organizer','admin') DEFAULT 'attendee',
    status ENUM('active','blocked') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Event Categories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Events Table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    category_id INT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    location VARCHAR(200),
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    capacity INT NOT NULL,
    status ENUM('upcoming','completed','cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (organizer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tickets / Registrations
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    qr_code VARCHAR(255), -- path to QR image file
    status ENUM('registered','checked_in') DEFAULT 'registered',
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    checkin_time DATETIME NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Default Admin Account
INSERT INTO users (name, email, password, role) 
VALUES ('Admin User', 'admin@example.com', MD5('admin123'), 'admin');

-- Insert Some Default Categories
INSERT INTO categories (name, description) VALUES
('Technology', 'Tech conferences, hackathons, and workshops'),
('Music', 'Concerts, festivals, and gigs'),
('Sports', 'Marathons, tournaments, and matches'),
('Education', 'Seminars, webinars, and classes');

-- Sample Organizers
INSERT INTO users (name, email, password, role, status) VALUES
('Organizer One', 'organizer1@example.com', MD5('organizer123'), 'organizer', 'active'),
('Organizer Two', 'organizer2@example.com', MD5('organizer123'), 'organizer', 'active');

-- Sample Attendees
INSERT INTO users (name, email, password, role, status) VALUES
('Attendee One', 'attendee1@example.com', MD5('attendee123'), 'attendee', 'active'),
('Attendee Two', 'attendee2@example.com', MD5('attendee123'), 'attendee', 'active'),
('Attendee Three', 'attendee3@example.com', MD5('attendee123'), 'attendee', 'active');

-- Sample Events
INSERT INTO events (organizer_id, category_id, title, description, location, event_date, event_time, capacity) VALUES
(1, 1, 'Tech Hackathon 2025', '24-hour coding hackathon for students.', 'Tech Park, City A', '2025-09-05', '10:00:00', 100),
(1, 2, 'Indie Music Night', 'Live music performances by indie bands.', 'City Concert Hall', '2025-09-12', '18:00:00', 200),
(2, 3, 'City Marathon', 'Annual city marathon for all age groups.', 'Central Park', '2025-09-20', '06:00:00', 500),
(2, 4, 'AI Seminar', 'Seminar on Artificial Intelligence applications.', 'Tech University Auditorium', '2025-09-25', '14:00:00', 150);

-- Sample Tickets (Attendees registering for events)
INSERT INTO tickets (event_id, user_id, qr_code) VALUES
(1, 3, 'qr/tech_hackathon_attendee3.png'),
(1, 4, 'qr/tech_hackathon_attendee4.png'),
(2, 3, 'qr/indie_music_attendee3.png'),
(3, 5, 'qr/city_marathon_attendee5.png');
