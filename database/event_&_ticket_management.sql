CREATE DATABASE IF NOT EXISTS `event_&_ticket_management`;

USE `event_&_ticket_management`;


-- ============================================
-- TABLE 1: USERS
-- ============================================

CREATE TABLE IF NOT EXISTS users (
    user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role VARCHAR(30) NOT NULL
) ENGINE=InnoDB;


-- ============================================
-- TABLE 2: EVENTS
-- ============================================

CREATE TABLE IF NOT EXISTS events (
    event_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(150) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(150) NOT NULL,
    capacity INT(11) NOT NULL,
    status VARCHAR(30) NOT NULL,
    organizer_id INT(11) NOT NULL,

    CONSTRAINT fk_event_organizer
        FOREIGN KEY (organizer_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB;


-- ============================================
-- TABLE 3: REGISTRATIONS
-- ============================================

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    event_id INT(11) NOT NULL,
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(30) NOT NULL,

    CONSTRAINT fk_registration_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_registration_event
        FOREIGN KEY (event_id)
        REFERENCES events(event_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB;