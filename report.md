# รายงานโครงงานวิชา Web Programming
## ระบบจองสนามแบดมินตันออนไลน์ (Badminton Court Booking System)

---

## บทที่ 1 บทนำ

### 1.1 ความเป็นมาและความสำคัญของโครงงาน

ในปัจจุบันการจองสนามกีฬาด้วยวิธีดั้งเดิม เช่น การโทรศัพท์หรือการเดินทางไปจองด้วยตนเอง ก่อให้เกิดความไม่สะดวกทั้งต่อผู้ใช้บริการและผู้ดูแลระบบ เนื่องจากมีความล่าช้าในการสื่อสาร การควบคุมตารางเวลาทับซ้อน และปัญหาด้านการจัดการข้อมูล

ทีมงานจึงพัฒนา **ระบบจองสนามแบดมินตันออนไลน์** ผ่านเว็บไซต์ที่ผู้ใช้สามารถเข้าถึงได้ตลอด 24 ชั่วโมง เพื่อให้การจองสนามเป็นเรื่องสะดวก รวดเร็ว และมีประสิทธิภาพมากยิ่งขึ้น

### 1.2 วัตถุประสงค์ของโครงงาน

1. เพื่อพัฒนาระบบจองสนามแบดมินตันออนไลน์ที่ใช้งานได้จริงผ่านเว็บเบราว์เซอร์
2. เพื่อให้ผู้ใช้ทั่วไปสามารถจองสนาม อัปโหลดสลิปการชำระเงิน และติดตามสถานะการจองได้
3. เพื่อให้ผู้ดูแลระบบสามารถจัดการสนาม การจอง และข้อมูลผู้ใช้ได้อย่างมีประสิทธิภาพ
4. เพื่อฝึกทักษะการพัฒนาเว็บด้วย PHP ร่วมกับฐานข้อมูล MySQL

### 1.3 ขอบเขตของโครงงาน

- พัฒนาด้วยภาษา PHP และฐานข้อมูล MySQL ทำงานบน XAMPP
- รองรับผู้ใช้ 2 ระดับ ได้แก่ ผู้ใช้ทั่วไป (Level 2) และผู้ดูแลระบบ (Level 3)
- ฟังก์ชันหลัก ได้แก่ สมัครสมาชิก, เข้าสู่ระบบ, จองสนาม, อัปโหลดสลิป, จัดการการจอง, จัดการสนาม และจัดการผู้ใช้
- ไม่ครอบคลุมระบบชำระเงินออนไลน์จริง (ใช้การอัปโหลดสลิปแทน)

### 1.4 ประโยชน์ที่คาดว่าจะได้รับ

- ผู้ใช้บริการสามารถตรวจสอบความว่างของสนามและจองได้ทันที ไม่ต้องเดินทาง
- ผู้ดูแลระบบสามารถดูรายการจองและอนุมัติการชำระเงินได้สะดวก
- ลดความผิดพลาดจากการจองซ้ำหรือการสื่อสารคลาดเคลื่อน

---

## บทที่ 2 ทฤษฎีและเทคโนโลยีที่เกี่ยวข้อง

### 2.1 PHP (Hypertext Preprocessor)

PHP เป็นภาษาสคริปต์ฝั่งเซิร์ฟเวอร์ (Server-Side Scripting) ที่นิยมใช้พัฒนาเว็บไซต์แบบ Dynamic มีความสามารถในการเชื่อมต่อฐานข้อมูล จัดการ Session และรับส่งข้อมูลจาก HTML Form ได้อย่างสะดวก โครงงานนี้ใช้ PHP เวอร์ชัน 8 ทำงานบน XAMPP

### 2.2 MySQL

MySQL เป็นระบบจัดการฐานข้อมูลเชิงสัมพันธ์ (RDBMS) แบบโอเพนซอร์ส มีประสิทธิภาพสูงและรองรับ SQL มาตรฐาน โครงงานนี้ใช้ MySQL สำหรับจัดเก็บข้อมูลผู้ใช้ สนาม และการจอง

### 2.3 HTML / CSS

ใช้ HTML5 ร่วมกับ CSS3 ในการออกแบบหน้าเว็บ โดยใช้ Google Fonts (Inter, Playfair Display) เพื่อความสวยงามและอ่านง่าย จัดรูปแบบตาราง ปุ่ม และฟอร์มต่าง ๆ ให้ใช้งานได้สะดวก

### 2.4 XAMPP

XAMPP เป็นแพ็กเกจซอฟต์แวร์ที่รวม Apache Web Server, MySQL, PHP และ phpMyAdmin ไว้ในชุดเดียว เหมาะสำหรับการพัฒนาและทดสอบเว็บไซต์บนเครื่องคอมพิวเตอร์ส่วนตัว

### 2.5 Session Management

ระบบใช้ PHP Session (`$_SESSION`) ในการจัดการการเข้าสู่ระบบและเก็บข้อมูลผู้ใช้ที่ login อยู่ เช่น `username`, `firstname`, `lastname`, `level` และ `user_id`

---

## บทที่ 3 การออกแบบระบบ

### 3.1 ผังโครงสร้างระบบ (System Architecture)

```
[ผู้ใช้เปิดเว็บ]
      │
      ▼
[header.php] ─── Session Check ─── [config.php] ─── [MySQL: badminton_db]
      │
      ├── index.php           (หน้าแรก)
      ├── login.php           (เข้าสู่ระบบ)
      ├── register.php        (สมัครสมาชิก)
      ├── court_booking.php   (จองสนาม)
      ├── my_booking.php      (การจองของฉัน)
      │
      └── [admin only]
          ├── booking_management.php
          ├── court_management.php
          └── user_management.php
```

### 3.2 การออกแบบฐานข้อมูล (Database Design)

ฐานข้อมูลชื่อ `badminton_db` ประกอบด้วย 4 ตาราง รวม **31 fields**

#### ตารางที่ 1: `employee` — ข้อมูลส่วนตัวของผู้ใช้

| Field | Type | คำอธิบาย |
|-------|------|---------|
| employee_id | INT AUTO_INCREMENT PK | รหัสพนักงาน |
| firstname | VARCHAR(100) NOT NULL | ชื่อ |
| lastname | VARCHAR(100) NOT NULL | นามสกุล |
| phone | VARCHAR(20) | เบอร์โทรศัพท์ |
| email | VARCHAR(100) | อีเมล |
| gender | ENUM('M','F','other') | เพศ |
| birth_date | DATE | วันเกิด |
| address | VARCHAR(255) | ที่อยู่ |

#### ตารางที่ 2: `systemuser` — บัญชีผู้ใช้ระบบ

| Field | Type | คำอธิบาย |
|-------|------|---------|
| user_id | INT AUTO_INCREMENT PK | รหัสผู้ใช้ |
| username | VARCHAR(100) NOT NULL UNIQUE | ชื่อผู้ใช้ |
| password | VARCHAR(255) NOT NULL | รหัสผ่าน |
| level | INT DEFAULT 2 | ระดับสิทธิ์ (2=ผู้ใช้, 3=admin) |
| employee_id | INT FK | อ้างอิงตาราง employee |

#### ตารางที่ 3: `court` — ข้อมูลสนามแบดมินตัน

| Field | Type | คำอธิบาย |
|-------|------|---------|
| court_id | INT AUTO_INCREMENT PK | รหัสสนาม |
| court_number | INT NOT NULL UNIQUE | หมายเลขสนาม |
| court_name | VARCHAR(100) NOT NULL | ชื่อสนาม |
| description | TEXT | รายละเอียดสนาม |
| price_per_hour | DECIMAL(8,2) DEFAULT 100.00 | ราคาต่อชั่วโมง |
| is_active | TINYINT(1) DEFAULT 1 | สถานะ (1=เปิด, 0=ปิด) |

#### ตารางที่ 4: `booking` — ข้อมูลการจองสนาม

| Field | Type | คำอธิบาย |
|-------|------|---------|
| booking_id | INT AUTO_INCREMENT PK | รหัสการจอง |
| username | VARCHAR(100) NOT NULL | ชื่อผู้จอง |
| booking_date | DATE NOT NULL | วันที่จอง |
| time_slot | VARCHAR(20) NOT NULL | ช่วงเวลา (เช่น 08:00-09:00) |
| court_number | INT NOT NULL FK | หมายเลขสนาม |
| price_per_slot | DECIMAL(8,2) NOT NULL | ราคาต่อ slot |
| slip_image | VARCHAR(255) | ชื่อไฟล์สลิป |
| payment_status | VARCHAR(20) DEFAULT 'pending' | สถานะ (pending/paid/cancelled) |
| payment_method | VARCHAR(20) | วิธีการชำระเงิน |
| note | TEXT | หมายเหตุ |
| created_at | TIMESTAMP | เวลาที่จอง |
| updated_at | TIMESTAMP ON UPDATE | เวลาแก้ไขล่าสุด |

#### ความสัมพันธ์ระหว่างตาราง (Relationships)

```
employee (employee_id) ◄──── systemuser (employee_id)
                                   [ON DELETE SET NULL]

court (court_number) ◄──────────── booking (court_number)
                                   [ON UPDATE CASCADE]
```

### 3.3 การออกแบบส่วนต่อประสานผู้ใช้ (UI Design)

- ใช้แถบนำทาง (Navigation Bar) สีน้ำตาลแดง (#a35353) แสดงเมนูตามระดับสิทธิ์
- หน้าจองสนามแสดงเป็นตาราง (Grid) แบ่งตามช่วงเวลาและสนาม
- ปุ่มมี 3 สถานะ: **จอง** (ว่าง, login แล้ว), **ว่าง** (ว่าง, ยังไม่ login), **จองแล้ว** (ไม่ว่าง)
- Flash Message แสดงผลสำเร็จ/ข้อผิดพลาดผ่าน `$_SESSION`

---

## บทที่ 4 ฟีเจอร์และการทำงานของระบบ

### 4.1 ฟีเจอร์สำหรับผู้ใช้ทั่วไป (Level 2)

#### 4.1.1 สมัครสมาชิก (`register.php` / `check-register.php`)
- กรอก ชื่อ, นามสกุล, เบอร์โทร, อีเมล, เพศ, วันเกิด, ชื่อผู้ใช้, รหัสผ่าน
- ระบบตรวจสอบ username ซ้ำก่อนบันทึก
- บันทึกข้อมูลส่วนตัวลงตาราง `employee` ก่อน แล้วจึงสร้างบัญชีใน `systemuser`

#### 4.1.2 เข้าสู่ระบบ (`login.php` / `check-login.php`)
- ตรวจสอบ username และ password จากฐานข้อมูล
- เก็บ `username`, `firstname`, `lastname`, `level`, `user_id` ลงใน Session

#### 4.1.3 จองสนาม (`court_booking.php` / `court_booking_submit.php`)
- เลือกวันที่จากช่อง date input แล้วกดปุ่ม "ดูตาราง"
- ตารางแสดงสนามทุกสนามพร้อมชื่อและราคาต่อชั่วโมง (ดึงจากตาราง `court`)
- กดปุ่ม "จอง" เพื่อจองช่วงเวลาที่ต้องการ ระบบบันทึกราคาพร้อมกัน
- ตรวจสอบการจองซ้ำก่อนบันทึกทุกครั้ง

#### 4.1.4 ดูการจองของตัวเอง (`my_booking.php`)
- แสดงรายการจองทั้งหมดของผู้ใช้พร้อมสถานะการชำระเงิน
- อัปโหลดสลิปการชำระเงิน (รองรับ image/*)
- ยกเลิกการจองที่ยังไม่ได้ชำระ (สถานะ paid ยกเลิกไม่ได้)

### 4.2 ฟีเจอร์สำหรับผู้ดูแลระบบ (Level 3)

#### 4.2.1 จัดการการจอง (`booking_management.php`)
- ดูรายการจองทั้งหมด กรองตามสถานะ: ทั้งหมด / รอชำระ / ชำระแล้ว / ยกเลิก
- เปลี่ยนสถานะการจอง (pending → paid → cancelled)
- ดูสลิปที่ผู้ใช้อัปโหลด
- ลบรายการจองทีละรายการ หรือลบทั้งหมด

#### 4.2.2 จัดการสนาม (`court_management.php`)
- ดูรายการสนามทั้งหมดพร้อมราคาและสถานะ
- เพิ่มสนามใหม่ (`court_add.php`) — กรอกหมายเลข, ชื่อ, ราคา, สถานะ
- แก้ไขข้อมูลสนาม (`court_edit.php`) — แก้ไขข้อมูลได้ทุก field
- ลบสนาม (`court_delete.php`)

#### 4.2.3 จัดการผู้ใช้ (`user_management.php`)
- ดูรายชื่อผู้ใช้ทั้งหมดพร้อมชื่อและระดับสิทธิ์
- แก้ไขข้อมูลผู้ใช้ (`um_edit.php`) — ชื่อ, รหัสผ่าน, เพศ, เบอร์, อีเมล, วันเกิด, ที่อยู่, level
- ลบผู้ใช้ (`um_delete.php`) — ไม่สามารถลบบัญชีของตัวเองได้

---

## บทที่ 5 โครงสร้างไฟล์และการทำงานร่วมกัน

### 5.1 รายการไฟล์ทั้งหมด

| ไฟล์ | หน้าที่ |
|------|--------|
| `config.php` | ตั้งค่าการเชื่อมต่อฐานข้อมูล |
| `database.sql` | SQL Script สร้างฐานข้อมูลและข้อมูลเริ่มต้น |
| `header.php` | Navbar + Session Start + แสดงเมนูตาม Level |
| `footer.php` | ส่วนท้ายเว็บไซต์ |
| `style.css` | สไตล์ชีทหลักของทั้งเว็บไซต์ |
| `index.php` | หน้าแรก (Welcome, Features, CTA) |
| `login.php` | หน้าฟอร์ม Login |
| `check-login.php` | ประมวลผล Login + เก็บ Session |
| `logout.php` | ลบ Session + redirect |
| `register.php` | หน้าฟอร์มสมัครสมาชิก |
| `check-register.php` | ประมวลผลสมัครสมาชิก |
| `court_booking.php` | ตารางจองสนาม (ดึงข้อมูล court + booking) |
| `court_booking_submit.php` | บันทึกการจอง |
| `my_booking.php` | การจองของผู้ใช้ + อัปโหลดสลิป + ยกเลิก |
| `booking_management.php` | Admin: จัดการการจองทั้งหมด |
| `bm_change_status.php` | Admin: เปลี่ยนสถานะการจอง |
| `bm_delete_one.php` | Admin: ลบการจอง 1 รายการ |
| `bm_delete_all.php` | Admin: ลบการจองทั้งหมด |
| `court_management.php` | Admin: รายการสนามทั้งหมด |
| `court_add.php` | Admin: เพิ่มสนามใหม่ |
| `court_edit.php` | Admin: แก้ไขข้อมูลสนาม |
| `court_delete.php` | Admin: ลบสนาม |
| `user_management.php` | Admin: รายชื่อผู้ใช้ทั้งหมด |
| `um_edit.php` | Admin: แก้ไขข้อมูลผู้ใช้ |
| `um_delete.php` | Admin: ลบผู้ใช้ |

### 5.2 Flow การจองสนาม

```
[ผู้ใช้เลือกวันที่] → [court_booking.php]
        │
        ▼
[กดปุ่มจอง] → POST → [court_booking_submit.php]
        │
        ├── ตรวจ Login? ──No──► redirect login.php
        ├── ดึงราคาจาก court table
        ├── ตรวจการจองซ้ำ? ──Yes──► $_SESSION['book_err']
        └── INSERT booking ──► redirect my_booking.php
```

### 5.3 Flow การสมัครสมาชิก

```
[กรอก Form] → POST → [check-register.php]
        │
        ├── ตรวจ username ซ้ำ? ──Yes──► redirect register.php
        ├── INSERT employee (firstname, lastname, phone, email, gender, birth_date)
        ├── รับ employee_id ที่ได้
        └── INSERT systemuser (username, password, level=2, employee_id)
                └──► redirect login.php
```

---

## บทที่ 6 การทดสอบระบบ

### 6.1 กรณีทดสอบ (Test Cases)

| ลำดับ | กรณีทดสอบ | ข้อมูลนำเข้า | ผลที่คาดหวัง | ผลจริง |
|-------|----------|------------|------------|--------|
| 1 | สมัครสมาชิกสำเร็จ | ข้อมูลครบถ้วน, username ไม่ซ้ำ | redirect login.php พร้อม success message | ผ่าน |
| 2 | สมัครสมาชิก username ซ้ำ | username ที่มีอยู่แล้ว | แสดงข้อความแจ้งเตือน | ผ่าน |
| 3 | Login สำเร็จ | username/password ถูกต้อง | redirect index.php + เก็บ Session | ผ่าน |
| 4 | Login ผิด | password ไม่ถูกต้อง | แสดง error message | ผ่าน |
| 5 | จองสนามสำเร็จ | วันที่/เวลา/สนามที่ว่าง | บันทึกลง DB, redirect my_booking | ผ่าน |
| 6 | จองสนามซ้ำ | เวลา/สนามที่จองแล้ว | แสดง error "เวลานี้ถูกจองแล้ว" | ผ่าน |
| 7 | อัปโหลดสลิป | ไฟล์รูปภาพ | บันทึก slip_image ลง DB + uploads/ | ผ่าน |
| 8 | ยกเลิกการจอง (pending) | การจองที่ยังไม่ชำระ | ลบการจอง + ลบไฟล์สลิป | ผ่าน |
| 9 | ยกเลิกการจอง (paid) | การจองที่ชำระแล้ว | แสดง error ยกเลิกไม่ได้ | ผ่าน |
| 10 | Admin เปลี่ยนสถานะ | เลือก paid/cancelled | update payment_status | ผ่าน |
| 11 | Admin เพิ่มสนาม | ข้อมูลสนามครบถ้วน | INSERT court record | ผ่าน |
| 12 | Admin เพิ่มสนามเลขซ้ำ | court_number ที่มีแล้ว | แสดง error | ผ่าน |
| 13 | เข้าหน้า Admin โดยไม่มีสิทธิ์ | Level 2 เข้า booking_management | redirect index.php | ผ่าน |

---

## บทที่ 7 ปัญหาและแนวทางแก้ไข

| ปัญหาที่พบ | สาเหตุ | แนวทางแก้ไข |
|----------|-------|-----------|
| สมัครสมาชิกแล้ว login ไม่ได้ | INSERT ลง `systemuser` ไม่มี `employee_id` ทำให้ JOIN ล้มเหลว | แก้ `check-register.php` ให้ INSERT `employee` ก่อน แล้วนำ `employee_id` ไป INSERT `systemuser` |
| `check-login.php` อาจเกิด Warning | `$row` ถูกใช้ก่อนถูก assign เมื่อ query ไม่พบข้อมูล | เพิ่ม `exit()` หลัง `header()` ทุกจุดเพื่อหยุดการทำงาน |
| ตาราง booking ถูกสร้างใน page code | ไม่ถูกต้องตามหลัก MVC | ย้าย CREATE TABLE ทั้งหมดไปไว้ใน `database.sql` |
| ชื่อสนามบน header ตาราง hardcode | ถ้าเพิ่ม/เปลี่ยนสนามต้องแก้ code | ดึงจากตาราง `court` แบบ Dynamic |
| ราคาไม่ถูกบันทึกตอนจอง | ไม่มี column `price_per_slot` | เพิ่ม column และดึงราคาจาก `court` ทุกครั้งที่จอง |

---

## บทที่ 8 สรุปและข้อเสนอแนะ

### 8.1 สรุปผล

โครงงานนี้สามารถพัฒนาระบบจองสนามแบดมินตันออนไลน์ที่ใช้งานได้จริง ครอบคลุมฟีเจอร์หลักครบถ้วน ได้แก่ การสมัครสมาชิก, เข้าสู่ระบบ, จองสนาม, อัปโหลดสลิป, จัดการการจอง, จัดการสนาม และจัดการผู้ใช้ ฐานข้อมูลมี 4 ตาราง รวม 31 fields มีความสัมพันธ์แบบ Foreign Key และ Index สำหรับเพิ่มประสิทธิภาพการค้นหา ระบบมีการแบ่งสิทธิ์การเข้าถึงชัดเจนระหว่างผู้ใช้ทั่วไปและผู้ดูแลระบบ

### 8.2 ข้อเสนอแนะสำหรับการพัฒนาต่อ

1. **เพิ่มการเข้ารหัสรหัสผ่าน** ด้วย `password_hash()` และ `password_verify()` เพื่อความปลอดภัย
2. **เพิ่ม Prepared Statements** เพื่อป้องกัน SQL Injection อย่างสมบูรณ์
3. **เพิ่มระบบแจ้งเตือน** ผ่าน Email หรือ LINE Notify เมื่อสถานะการจองเปลี่ยน
4. **เพิ่มหน้า Dashboard** สรุปสถิติการจองและรายได้สำหรับ Admin
5. **รองรับ Mobile** ด้วย Responsive Design เพื่อให้ใช้งานบนโทรศัพท์ได้สะดวก

---

## ภาคผนวก: ข้อมูลทางเทคนิค

### บัญชีทดสอบ

| Username | Password | Level | ประเภท |
|----------|----------|-------|--------|
| admin | admin | 3 | ผู้ดูแลระบบ |
| user1 | 1234 | 2 | ผู้ใช้ทั่วไป |
| user2 | 1234 | 2 | ผู้ใช้ทั่วไป |

### ข้อมูลสนาม

| สนาม | ชื่อ | ราคา/ชั่วโมง |
|------|------|------------|
| 1 | สนาม A | 100 บาท |
| 2 | สนาม B | 100 บาท |
| 3 | สนาม C | 120 บาท |
| 4 | สนาม D | 120 บาท |

### การติดตั้งระบบ (สรุปย่อ)

1. คัดลอกไฟล์ทั้งหมดไปที่ `C:\xampp\htdocs\`
2. เปิด XAMPP → Start **Apache** และ **MySQL**
3. เปิด `http://localhost/phpmyadmin` → Import `database.sql`
4. เปิดเบราว์เซอร์ → `http://localhost/`

---

*รายงานฉบับนี้จัดทำขึ้นเป็นส่วนหนึ่งของวิชา Web Programming*
