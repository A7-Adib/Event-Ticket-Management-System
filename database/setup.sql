CREATE DATABASE IF NOT EXISTS `event-ticket-management-system`
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `event-ticket-management-system`;

SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS check_in;
DROP TABLE IF EXISTS tickets;
DROP TABLE IF EXISTS registrations;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
  user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  role ENUM('Admin','Organizer','Participant','Staff') NOT NULL DEFAULT 'Participant',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categories (
  category_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  category_name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE events (
  event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  event_name VARCHAR(150) NOT NULL,
  description TEXT,
  date DATE NOT NULL,
  time TIME NOT NULL,
  location VARCHAR(150) NOT NULL,
  capacity INT UNSIGNED NOT NULL,
  status ENUM('Upcoming','Ongoing','Completed','Cancelled') NOT NULL DEFAULT 'Upcoming',
  organizer_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_events_organizer FOREIGN KEY (organizer_id) REFERENCES users(user_id) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_events_category FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE registrations (
  registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  event_id INT UNSIGNED NOT NULL,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('Registered','Confirmed','Attended','Cancelled') NOT NULL DEFAULT 'Registered',
  UNIQUE KEY uq_registration (user_id,event_id),
  CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_reg_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE tickets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_code VARCHAR(50) NOT NULL UNIQUE,
  user_id INT UNSIGNED NOT NULL,
  event_id INT UNSIGNED NOT NULL,
  registration_id INT UNSIGNED NOT NULL UNIQUE,
  attendee_name VARCHAR(100) NOT NULL,
  event_name VARCHAR(150) NOT NULL,
  ticket_type ENUM('Regular','VIP','Student') NOT NULL DEFAULT 'Regular',
  status ENUM('Valid','Used','Cancelled') NOT NULL DEFAULT 'Valid',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ticket_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ticket_event FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ticket_registration FOREIGN KEY (registration_id) REFERENCES registrations(registration_id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE check_in (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  ticket_id INT UNSIGNED NOT NULL UNIQUE,
  ticket_code VARCHAR(50) NOT NULL UNIQUE,
  attendee_name VARCHAR(100) NOT NULL,
  status VARCHAR(30) NOT NULL DEFAULT 'Checked-In',
  checkin_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_checkin_ticket FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE announcements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_by INT UNSIGNED DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_announcement_user FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories(category_name) VALUES
('Technology'),('Business'),('Education'),('Sports'),('Entertainment');

INSERT INTO users(name,email,password,phone,role) VALUES
('System Admin','admin@eventflow.local','$2y$12$2ACpAt2LVkqZS0kpYoTte.IH0E8FWnJC9NhgcbzTUuuXVZmo/ELiW','01700000001','Admin'),
('Event Organizer','organizer@eventflow.local','$2y$12$m0EcjPD7pOHaTez3pix91.jdrdHdhubtlYgtH4teCV7B1mRbv6QX.','01700000002','Organizer'),
('Demo Participant','participant@eventflow.local','$2y$12$mePaeAk8hAm94XT5lyBfi.qtUg/LbALREF7/VXLaN4B73iUIpcj.W','01700000003','Participant'),
('Event Staff','staff@eventflow.local','$2y$12$vj0O4EioxBpP1kFY5XSlUOQDBpAu.IZGuK5I4YgMiDwLvnyOcR1Gm','01700000004','Staff');

INSERT INTO events(event_name,description,date,time,location,capacity,status,organizer_id,category_id) VALUES
('AIUB Tech Fest 2026','Technology festival with talks, projects and competitions.','2026-09-15','10:00:00','AIUB Campus',100,'Upcoming',2,1),
('Startup and Entrepreneurship Summit','Meet entrepreneurs and learn how to build and grow a startup.','2026-09-25','10:30:00','Agrabad, Chattogram',200,'Upcoming',2,2),
('Future Education Conference','A conference about modern learning and digital education.','2026-10-05','12:00:00','Zindabazar, Sylhet',180,'Upcoming',2,3);

INSERT INTO announcements(title,message,created_by) VALUES
('Welcome to EventFlow','The event registration and ticketing system is ready to use.',1);
