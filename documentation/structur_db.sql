-- Active: 1733626380290@@127.0.0.1@3306@timbangan_db
DROP TABLE users;
DROP TABLE item;
DROP TABLE transaction;
DROP TABLE position_access;
DROP TABLE jabatan;
DROP TABLE menu_web;
DROP TABLE size_item;
CREATE TABLE jabatan (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `jabatan` VARCHAR(255) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(255) NOT NULL,
    `jabatan_id` BIGINT UNSIGNED NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE menu_web (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `menu` VARCHAR(255) NOT NULL UNIQUE,
    `link` VARCHAR(255) NOT NULL,
    `icon` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE position_access (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `position_id` BIGINT UNSIGNED NOT NULL,
    `menu_id` BIGINT UNSIGNED NOT NULL,
    `read_access` BOOLEAN DEFAULT TRUE,
    `create_access` BOOLEAN DEFAULT FALSE,
    `update_access` BOOLEAN DEFAULT FALSE,
    `delete_access` BOOLEAN DEFAULT FALSE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`position_id`) REFERENCES `jabatan`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`menu_id`) REFERENCES `menu_web`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `item` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `code` VARCHAR(255) NOT NULL UNIQUE,
    `style` VARCHAR(255) NOT NULL,
    `size` VARCHAR(255) NOT NULL,
    `weight_min` DOUBLE(8,4) NOT NULL,
    `weight_max` DOUBLE(8,4) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE size_item (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `size` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `transaction` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `no_invoice` VARCHAR(255) NOT NULL UNIQUE,
    `code_item` VARCHAR(255) NOT NULL,
    `name_item` VARCHAR(255) NOT NULL,
    `style_item` VARCHAR(255) NOT NULL,
    `size_item` VARCHAR(255) NOT NULL,
    `weight_item` DOUBLE NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

