-- Plesk-achtige database + gebruiker. Wordt bij elke `ddev start` uitgevoerd
-- via de post-start hook in .ddev/config.yaml (idempotent).
CREATE DATABASE IF NOT EXISTS `smaakmaatje` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER IF NOT EXISTS 'student_user'@'%' IDENTIFIED BY 'student_password';
CREATE USER IF NOT EXISTS 'student_user'@'localhost' IDENTIFIED BY 'student_password';
GRANT ALL PRIVILEGES ON `smaakmaatje`.* TO 'student_user'@'%';
GRANT ALL PRIVILEGES ON `smaakmaatje`.* TO 'student_user'@'localhost';
FLUSH PRIVILEGES;
