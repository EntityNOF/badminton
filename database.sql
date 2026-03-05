-- ==============================
-- ระบบจองสนามแบดมินตัน - Database Setup
-- ==============================

-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS badminton_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE badminton_db;

-- ==============================
-- ตาราง employee (เก็บข้อมูลชื่อ-นามสกุลของผู้ใช้)
-- ==============================
CREATE TABLE IF NOT EXISTS employee (
    employee_id INT          AUTO_INCREMENT PRIMARY KEY,
    firstname   VARCHAR(100) NOT NULL,
    lastname    VARCHAR(100) NOT NULL,
    phone       VARCHAR(20)           DEFAULT '',
    email       VARCHAR(100)          DEFAULT '',
    gender      ENUM('M','F','other') DEFAULT 'other',
    birth_date  DATE                  DEFAULT NULL,
    address     VARCHAR(255)          DEFAULT ''
);

-- ==============================
-- ตาราง systemuser (เก็บบัญชีผู้ใช้ระบบ)
-- level: 2 = ผู้ใช้ทั่วไป, 3 = ผู้ดูแลระบบ
-- ==============================
CREATE TABLE IF NOT EXISTS systemuser (
    user_id     INT          AUTO_INCREMENT PRIMARY KEY,
    username    VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    level       INT          NOT NULL DEFAULT 2,
    employee_id INT,
    FOREIGN KEY (employee_id) REFERENCES employee(employee_id) ON DELETE SET NULL
);

-- ==============================
-- ตาราง court (เก็บข้อมูลสนามแบดมินตัน)
-- is_active: 1 = เปิดใช้งาน, 0 = ปิดปรับปรุง
-- ==============================
CREATE TABLE IF NOT EXISTS court (
    court_id       INT          AUTO_INCREMENT PRIMARY KEY,
    court_number   INT          NOT NULL UNIQUE,
    court_name     VARCHAR(100) NOT NULL,
    description    TEXT                  DEFAULT NULL,
    price_per_hour DECIMAL(8,2) NOT NULL DEFAULT 100.00,
    is_active      TINYINT(1)   NOT NULL DEFAULT 1
);

-- ==============================
-- ตาราง booking (เก็บข้อมูลการจองสนาม)
-- payment_status: pending = รอชำระ, paid = ชำระแล้ว, cancelled = ยกเลิก
-- ==============================
CREATE TABLE IF NOT EXISTS booking (
    booking_id     INT          AUTO_INCREMENT PRIMARY KEY,
    username       VARCHAR(100) NOT NULL,
    booking_date   DATE         NOT NULL,
    time_slot      VARCHAR(20)  NOT NULL,
    court_number   INT          NOT NULL,
    price_per_slot DECIMAL(8,2) NOT NULL DEFAULT 100.00,
    slip_image     VARCHAR(255)          DEFAULT '',
    payment_status VARCHAR(20)           DEFAULT 'pending',
    payment_method VARCHAR(20)           DEFAULT '',
    note           TEXT                  DEFAULT NULL,
    created_at     TIMESTAMP             DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP             DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (court_number) REFERENCES court(court_number) ON UPDATE CASCADE
);

-- ==============================
-- Index เพิ่มประสิทธิภาพการค้นหา
-- ==============================
CREATE INDEX idx_booking_date   ON booking (booking_date);
CREATE INDEX idx_booking_user   ON booking (username);
CREATE INDEX idx_booking_status ON booking (payment_status);

-- ==============================
-- ข้อมูลสนาม (court)
-- ==============================
INSERT INTO court (court_number, court_name, price_per_hour, is_active) VALUES
(1, 'สนาม A', 100.00, 1),
(2, 'สนาม B', 100.00, 1),
(3, 'สนาม C', 120.00, 1),
(4, 'สนาม D', 120.00, 1);

-- ==============================
-- ข้อมูลเริ่มต้น: Admin Account
-- ==============================
INSERT INTO employee (firstname, lastname) VALUES ('Admin', 'System');
INSERT INTO systemuser (username, password, level, employee_id)
VALUES ('admin', 'admin', 3, 1);

-- ==============================
-- ข้อมูลตัวอย่าง: ผู้ใช้ทั่วไป
-- ==============================
INSERT INTO employee (firstname, lastname) VALUES ('สมชาย', 'ใจดี');
INSERT INTO systemuser (username, password, level, employee_id)
VALUES ('user1', '1234', 2, 2);

INSERT INTO employee (firstname, lastname) VALUES ('สมหญิง', 'รักดี');
INSERT INTO systemuser (username, password, level, employee_id)
VALUES ('user2', '1234', 2, 3);
