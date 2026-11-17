DROP DATABASE IF EXISTS vie_tn;
CREATE DATABASE vie_tn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE vie_tn;

CREATE TABLE users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    role ENUM('patient','proche','medecin','admin') NOT NULL DEFAULT 'patient',
    status ENUM('actif','en_attente','suspendu') NOT NULL DEFAULT 'actif',
    last_login_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_role_status (role, status)
) ENGINE=InnoDB;

CREATE TABLE cancer_types (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL,
    description TEXT DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cancer_types_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE care_centers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(150) NOT NULL,
    city VARCHAR(80) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    latitude DECIMAL(9,6) DEFAULT NULL,
    longitude DECIMAL(9,6) DEFAULT NULL,
    services TEXT DEFAULT NULL,
    equipment TEXT DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_centers_city (city)
) ENGINE=InnoDB;

CREATE TABLE doctors (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    speciality VARCHAR(100) NOT NULL,
    order_number VARCHAR(30) NOT NULL,
    center_id INT UNSIGNED DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    validated_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_doctors_user (user_id),
    UNIQUE KEY uq_doctors_order (order_number),
    CONSTRAINT fk_doctors_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_doctors_center FOREIGN KEY (center_id) REFERENCES care_centers (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE patients (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    share_code CHAR(11) NOT NULL,
    cancer_type_id INT UNSIGNED DEFAULT NULL,
    stage VARCHAR(20) DEFAULT NULL,
    birth_date DATE DEFAULT NULL,
    treatment_progress TINYINT UNSIGNED NOT NULL DEFAULT 0,
    referring_doctor_id INT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_patients_user (user_id),
    UNIQUE KEY uq_patients_share_code (share_code),
    CONSTRAINT fk_patients_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_patients_cancer FOREIGN KEY (cancer_type_id) REFERENCES cancer_types (id) ON DELETE SET NULL,
    CONSTRAINT fk_patients_doctor FOREIGN KEY (referring_doctor_id) REFERENCES doctors (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE proches (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    patient_id INT UNSIGNED NOT NULL,
    relationship VARCHAR(50) DEFAULT NULL,
    share_appointments TINYINT(1) NOT NULL DEFAULT 1,
    share_medications TINYINT(1) NOT NULL DEFAULT 1,
    share_documents TINYINT(1) NOT NULL DEFAULT 0,
    access_expires_at DATE DEFAULT NULL,
    revoked_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_proches_user (user_id),
    KEY idx_proches_patient (patient_id),
    CONSTRAINT fk_proches_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT fk_proches_patient FOREIGN KEY (patient_id) REFERENCES patients (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE doctor_availabilities (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    doctor_id INT UNSIGNED NOT NULL,
    weekday TINYINT UNSIGNED NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    PRIMARY KEY (id),
    KEY idx_availability_doctor (doctor_id, weekday),
    CONSTRAINT fk_availability_doctor FOREIGN KEY (doctor_id) REFERENCES doctors (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE appointments (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT UNSIGNED NOT NULL,
    doctor_id INT UNSIGNED NOT NULL,
    date DATETIME NOT NULL,
    reason VARCHAR(150) DEFAULT NULL,
    mode ENUM('presentiel','teleconsultation') NOT NULL DEFAULT 'presentiel',
    status ENUM('en_attente','confirme','refuse','annule','termine') NOT NULL DEFAULT 'en_attente',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_appointments_doctor_date (doctor_id, date),
    KEY idx_appointments_patient_date (patient_id, date),
    CONSTRAINT fk_appointments_patient FOREIGN KEY (patient_id) REFERENCES patients (id) ON DELETE CASCADE,
    CONSTRAINT fk_appointments_doctor FOREIGN KEY (doctor_id) REFERENCES doctors (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE medications (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT UNSIGNED NOT NULL,
    prescribed_by INT UNSIGNED DEFAULT NULL,
    name VARCHAR(120) NOT NULL,
    dosage VARCHAR(60) NOT NULL,
    frequency VARCHAR(80) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_medications_patient (patient_id),
    CONSTRAINT fk_medications_patient FOREIGN KEY (patient_id) REFERENCES patients (id) ON DELETE CASCADE,
    CONSTRAINT fk_medications_doctor FOREIGN KEY (prescribed_by) REFERENCES doctors (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE medical_notes (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT UNSIGNED NOT NULL,
    doctor_id INT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_notes_patient (patient_id),
    CONSTRAINT fk_notes_patient FOREIGN KEY (patient_id) REFERENCES patients (id) ON DELETE CASCADE,
    CONSTRAINT fk_notes_doctor FOREIGN KEY (doctor_id) REFERENCES doctors (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE documents (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    patient_id INT UNSIGNED NOT NULL,
    title VARCHAR(150) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_documents_patient (patient_id),
    CONSTRAINT fk_documents_patient FOREIGN KEY (patient_id) REFERENCES patients (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE news (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    cancer_type_id INT UNSIGNED DEFAULT NULL,
    author_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    excerpt VARCHAR(300) DEFAULT NULL,
    body MEDIUMTEXT NOT NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_news_published (is_published, published_at),
    CONSTRAINT fk_news_cancer FOREIGN KEY (cancer_type_id) REFERENCES cancer_types (id) ON DELETE SET NULL,
    CONSTRAINT fk_news_author FOREIGN KEY (author_id) REFERENCES users (id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED DEFAULT NULL,
    action VARCHAR(80) NOT NULL,
    target VARCHAR(120) DEFAULT NULL,
    ip_address VARCHAR(45) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_audit_user (user_id),
    KEY idx_audit_created (created_at),
    CONSTRAINT fk_audit_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO cancer_types (name, slug, description) VALUES
('Sein', 'sein', 'Dépistage, traitements et suivi du cancer du sein.'),
('Poumon', 'poumon', 'Prévention, diagnostic et prise en charge du cancer du poumon.'),
('Leucémie', 'leucemie', 'Comprendre les leucémies et les parcours de soins associés.'),
('Colorectal', 'colorectal', 'Dépistage précoce et traitements du cancer colorectal.'),
('Prostate', 'prostate', 'Surveillance, imagerie et traitements du cancer de la prostate.'),
('Peau', 'peau', 'Protection solaire et dépistage des cancers de la peau.');

INSERT INTO care_centers (name, city, address, phone, latitude, longitude, services, equipment) VALUES
('Institut Salah Azaiez', 'Tunis', 'Boulevard du 9 Avril, Bab Saadoun', '+216 71 000 001', 36.808300, 10.165600, 'Oncologie médicale, radiothérapie, chirurgie oncologique', 'IRM, scanner, TEP-scan, accélérateurs linéaires'),
('Hôpital Abderrahmen Mami', 'Ariana', 'Pavillon Ibn Khaldoun', '+216 71 000 002', 36.866500, 10.164700, 'Pneumologie, oncologie thoracique', 'Scanner, bronchoscopie, laboratoire d''anatomopathologie'),
('Centre Hospitalo-Universitaire Farhat Hached', 'Sousse', 'Avenue Ibn El Jazzar', '+216 73 000 003', 35.825600, 10.608600, 'Hématologie, oncologie pédiatrique', 'Chambres stériles, cytométrie, IRM'),
('Clinique El Manar', 'Tunis', 'Rue Ibn Sina, El Manar', '+216 71 000 004', 36.832500, 10.147800, 'Sénologie, dépistage, soins de support', 'Mammographie 3D, échographie, IRM mammaire');

INSERT INTO users (full_name, email, password, phone, role, status, last_login_at) VALUES
('Administrateur VIE', 'admin@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 001', 'admin', 'actif', NOW()),
('Dr Ben Salah', 'bensalah@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 002', 'medecin', 'actif', NOW()),
('Dr Kallel', 'kallel@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 003', 'medecin', 'en_attente', NULL),
('Sarra Trabelsi', 'sarra.trabelsi@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 004', 'patient', 'actif', NOW()),
('Karim Mansour', 'karim.mansour@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 005', 'patient', 'suspendu', NULL),
('Mehdi Trabelsi', 'mehdi.trabelsi@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 006', 'proche', 'actif', NOW()),
('Amine Gharbi', 'amine.gharbi@vie.tn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+216 20 000 007', 'patient', 'actif', NOW());

INSERT INTO doctors (user_id, speciality, order_number, center_id, bio, validated_at) VALUES
(2, 'Oncologie médicale', '12345', 1, 'Oncologue, suivi des cancers du sein et du poumon.', NOW()),
(3, 'Hématologie', '23456', 3, 'Hématologue, prise en charge des leucémies.', NULL);

INSERT INTO patients (user_id, share_code, cancer_type_id, stage, birth_date, treatment_progress, referring_doctor_id) VALUES
(4, 'CC-7F3K-92', 1, 'II', '1984-03-12', 68, 1),
(5, 'CC-4M8Q-17', 2, 'III', '1971-09-30', 35, 1),
(7, 'CC-9X2D-55', 3, 'I', '1990-01-22', 80, 2);

INSERT INTO proches (user_id, patient_id, relationship, share_appointments, share_medications, share_documents, access_expires_at) VALUES
(6, 1, 'Époux', 1, 1, 0, '2026-12-31');

INSERT INTO doctor_availabilities (doctor_id, weekday, start_time, end_time) VALUES
(1, 1, '08:30:00', '12:30:00'),
(1, 2, '08:30:00', '12:30:00'),
(1, 2, '14:00:00', '17:00:00'),
(1, 4, '08:30:00', '12:30:00'),
(1, 4, '14:00:00', '17:00:00'),
(1, 5, '09:00:00', '13:00:00');

INSERT INTO appointments (patient_id, doctor_id, date, reason, mode, status) VALUES
(1, 1, '2026-11-24 10:30:00', 'Contrôle sein', 'presentiel', 'confirme'),
(2, 1, '2026-11-24 11:15:00', 'Bilan poumon', 'presentiel', 'en_attente'),
(3, 2, '2026-11-26 14:00:00', 'Résultats', 'teleconsultation', 'confirme');

INSERT INTO medications (patient_id, prescribed_by, name, dosage, frequency, start_date, end_date) VALUES
(1, 1, 'Tamoxifène', '20 mg', '1 comprimé chaque matin', '2026-09-01', NULL),
(1, 1, 'Ondansétron', '8 mg', 'Si nausées, maximum 2 par jour', '2026-09-01', NULL),
(1, 1, 'Vitamine D3', '1000 UI', '1 gélule chaque soir', '2026-09-15', NULL);

INSERT INTO medical_notes (patient_id, doctor_id, content) VALUES
(1, 1, 'Bonne tolérance du traitement. Prochain contrôle d''imagerie à prévoir dans six semaines.');

INSERT INTO documents (patient_id, title, file_path) VALUES
(1, 'Compte rendu de consultation', 'uploads/cr-consultation.pdf'),
(1, 'Bilan sanguin', 'uploads/bilan-sanguin.pdf');

INSERT INTO news (cancer_type_id, author_id, title, excerpt, body, is_published, published_at) VALUES
(1, 1, 'Dépistage : l''âge de la première mammographie en débat', 'Les recommandations évoluent selon les profils de risque.', 'Contenu de l''article.', 1, '2026-10-12 09:00:00'),
(2, 1, 'Immunothérapie : de nouveaux résultats chez les patients avancés', 'Un point sur les études récentes.', 'Contenu de l''article.', 1, '2026-10-05 09:00:00'),
(3, 1, 'Thérapies ciblées : meilleure survie chez l''enfant', 'Ce que changent les traitements ciblés.', 'Contenu de l''article.', 0, NULL);

INSERT INTO audit_logs (user_id, action, target, ip_address) VALUES
(1, 'validation_medecin', 'Dr Ben Salah', '127.0.0.1'),
(1, 'publication_article', 'Dépistage : l''âge de la première mammographie en débat', '127.0.0.1'),
(4, 'connexion', 'sarra.trabelsi@vie.tn', '127.0.0.1');
