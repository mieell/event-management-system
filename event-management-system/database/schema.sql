CREATE DATABASE IF NOT EXISTS event_management_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE event_management_system;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','organizer','attendee') NOT NULL DEFAULT 'attendee',
  profile_image VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_users_role (role),
  INDEX idx_users_created_at (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  description TEXT NOT NULL,
  venue VARCHAR(180) NOT NULL,
  category VARCHAR(80) NOT NULL,
  event_date DATE NOT NULL,
  event_time TIME NOT NULL,
  image VARCHAR(255) NULL,
  capacity INT UNSIGNED NOT NULL DEFAULT 50,
  status ENUM('draft','published','featured','cancelled','completed') NOT NULL DEFAULT 'draft',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_events_title_date (title, event_date),
  INDEX idx_events_status_date (status, event_date),
  INDEX idx_events_category (category),
  FULLTEXT INDEX ft_events_search (title, description, venue, category)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  event_id INT UNSIGNED NOT NULL,
  status ENUM('pending','approved','rejected','cancelled') NOT NULL DEFAULT 'pending',
  payment_proof VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_registration_user_event (user_id, event_id),
  INDEX idx_registrations_status (status),
  INDEX idx_registrations_created_at (created_at),
  CONSTRAINT fk_registrations_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_registrations_event FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  message VARCHAR(255) NOT NULL,
  is_read TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_notifications_user_read (user_id, is_read),
  CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activity_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  activity VARCHAR(255) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_activity_logs_user (user_id),
  INDEX idx_activity_logs_created_at (created_at),
  CONSTRAINT fk_activity_logs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS remember_tokens (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  selector CHAR(24) NOT NULL UNIQUE,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_remember_tokens_user (user_id),
  INDEX idx_remember_tokens_expiry (expires_at),
  CONSTRAINT fk_remember_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO users (fullname, email, password, role, created_at)
VALUES
  ('System Administrator', 'admin@Evenira.test', '$2y$10$jEeuFNT2384z0fC9qqXvk.SdENTWCvJ/pcSui5QK0CWmWHPE6Ed8K', 'admin', NOW()),
  ('Event Organizer', 'organizer@Evenira.test', '$2y$10$jEeuFNT2384z0fC9qqXvk.SdENTWCvJ/pcSui5QK0CWmWHPE6Ed8K', 'organizer', NOW()),
  ('Sample Attendee', 'attendee@Evenira.test', '$2y$10$jEeuFNT2384z0fC9qqXvk.SdENTWCvJ/pcSui5QK0CWmWHPE6Ed8K', 'attendee', NOW())
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO events (title, description, venue, category, event_date, event_time, image, capacity, status, created_at)
VALUES
  ('Mythic Cup Mobile Legends Tournament', 'A 5v5 Mobile Legends tournament for campus squads, featuring bracket play, finals livestreaming, MVP awards, and championship prizes.', 'Celestial Esports Arena', 'Tournament', DATE_ADD(CURDATE(), INTERVAL 18 DAY), '09:00:00', 'assets/img/events/mythic-cup.svg', 160, 'featured', NOW()),
  ('Legend Squad Scrim Night', 'A managed scrim event for Mobile Legends teams that want ranked practice, room codes, referee support, and post-match result tracking.', 'Aurora Gaming Hub', 'Scrim', DATE_ADD(CURDATE(), INTERVAL 32 DAY), '18:30:00', 'assets/img/events/scrim-night.svg', 80, 'published', NOW()),
  ('MPL Finals Watch Party', 'A community watch party for Mobile Legends fans with live match analysis, cosplay corners, prediction games, and team meetups.', 'Skyline Esports Lounge', 'Watch Party', DATE_ADD(CURDATE(), INTERVAL 45 DAY), '15:00:00', 'assets/img/events/watch-party.svg', 120, 'published', NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title);
