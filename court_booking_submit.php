<?php
session_start();
require_once "config.php";

// ต้อง login ก่อน
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// ต้องเป็น POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: court_booking.php");
    exit;
}

$slot   = $_POST['time_slot'];
$bdate  = $_POST['book_date'];
$court  = intval($_POST['court_number']);
$uname  = $_SESSION['username'];

// ดึงราคาต่อชั่วโมงจากตาราง court
$courtRow = mysqli_fetch_assoc(mysqli_query($connect, "SELECT price_per_hour FROM court WHERE court_number=$court AND is_active=1"));
if (!$courtRow) {
    $_SESSION['book_err'] = "ไม่พบข้อมูลสนามที่เลือก";
    header("Location: court_booking.php?date=$bdate");
    exit;
}
$price = $courtRow['price_per_hour'];

// เช็คว่าจองซ้ำหรือเปล่า (ยกเว้นที่ยกเลิก)
$chk = mysqli_query($connect, "SELECT booking_id FROM booking WHERE booking_date='$bdate' AND time_slot='$slot' AND court_number=$court AND payment_status != 'cancelled'");
if (mysqli_num_rows($chk) > 0) {
    $_SESSION['book_err'] = "เวลานี้ถูกจองแล้ว กรุณาเลือกเวลาอื่น";
    header("Location: court_booking.php?date=$bdate");
    exit;
}

// บันทึกการจอง พร้อมราคา
mysqli_query($connect, "INSERT INTO booking (username, booking_date, time_slot, court_number, price_per_slot) VALUES ('$uname', '$bdate', '$slot', $court, $price)");

$_SESSION['book_msg'] = "จองสำเร็จ! สนาม $court เวลา $slot วันที่ $bdate (ราคา $price บาท)";
header("Location: my_booking.php");
exit;
?>
