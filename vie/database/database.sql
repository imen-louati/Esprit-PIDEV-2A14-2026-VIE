CREATE DATABASE IF NOT EXISTS projetV CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE projetV;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('patient','proche','medecin','admin') NOT NULL,
    status ENUM('active','inactive','blocked') NOT NULL DEFAULT 'active',

    birth_date DATE DEFAULT NULL,
    share_code VARCHAR(50) DEFAULT NULL,
    cancer_type VARCHAR(100) DEFAULT NULL,
    stage VARCHAR(50) DEFAULT NULL,
    progress TEXT DEFAULT NULL,

    speciality VARCHAR(100) DEFAULT NULL,
    license_number VARCHAR(100) DEFAULT NULL,
    city VARCHAR(100) DEFAULT NULL,

    patient_id INT UNSIGNED DEFAULT NULL,
    relationship VARCHAR(50) DEFAULT NULL,
    share_appointments TINYINT(1) DEFAULT NULL,
    share_medications TINYINT(1) DEFAULT NULL,
    share_documents TINYINT(1) DEFAULT NULL,
    access_expires_at DATE DEFAULT NULL,
    access_revoked_at DATETIME DEFAULT NULL,

    last_login_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    UNIQUE KEY uq_users_share_code (share_code),
    UNIQUE KEY uq_users_license_number (license_number),
    KEY idx_users_role_status (role, status),
    KEY idx_users_patient (patient_id),
    KEY idx_users_created (created_at),
    CONSTRAINT fk_users_patient FOREIGN KEY (patient_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL,
    expires_at DATETIME NOT NULL,
    used_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_password_resets_token (token_hash),
    KEY idx_password_resets_user (user_id),
    CONSTRAINT fk_password_resets_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
