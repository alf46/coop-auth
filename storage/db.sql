CREATE DATABASE IF NOT EXISTS `coop-auth`;
use `coop-auth`;
 
-- All system users.
CREATE TABLE IF NOT EXISTS `user` (
  `username` CHAR(11) PRIMARY KEY,
  `password` VARCHAR(128) NOT NULL,
  `email` VARCHAR(100) NULL,
  `role` VARCHAR(15) NOT NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `updated_at` DATETIME(3) NULL,
  `enabled` TINYINT NOT NULL DEFAULT 1
);

-- Default Password is: coop2023
INSERT INTO `user`(`username`, `password`, `email`, `role`) VALUES ('05601768053', '$2y$12$M5hBLauTI0dYDb6KHXZduOt6QL0spe03nPx56Eb9QAvW7cBGS3s9y', 'servicio@coopincuba.com', 'adm');

-- password recovery token.
CREATE TABLE IF NOT EXISTS `reset_password` (
  `id` CHAR(36) PRIMARY KEY,
  `username` CHAR(11) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `exp` int NOT NULL,
  `created_at` DATETIME(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3),
  `used` TINYINT NOT NULL DEFAULT 0
);
