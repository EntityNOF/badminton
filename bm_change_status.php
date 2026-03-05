<?php
session_start();
require_once "config.php";

// ตรวจสอบสิทธิ์
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

$booking_id = intval($_POST['booking_id']);
$new_status = $_POST['new_status'];
$redirect   = isset($_POST['redirect']) ? $_POST['redirect'] : 'booking_management.php';

// อนุญาตเฉพาะสถานะที่กำหนด
$allowed = ['pending', 'paid', 'cancelled'];
if (in_array($new_status, $allowed)) {
    mysqli_query($connect, "UPDATE booking SET payment_status = '$new_status' WHERE booking_id = $booking_id");
    $_SESSION['book_msg'] = "อัปเดตสถานะเรียบร้อยแล้ว";
} else {
    $_SESSION['book_err'] = "สถานะไม่ถูกต้อง";
}

header("Location: " . $redirect);
exit;
?>
