<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

// ลบไฟล์สลิปทั้งหมด
$r = mysqli_query($connect, "SELECT slip_image FROM booking WHERE slip_image != ''");
while ($row = mysqli_fetch_assoc($r)) {
    if (file_exists("uploads/" . $row['slip_image'])) {
        unlink("uploads/" . $row['slip_image']);
    }
}

mysqli_query($connect, "DELETE FROM booking");
$_SESSION['book_msg'] = "ลบข้อมูลการจองทั้งหมดแล้ว";

header("Location: booking_management.php");
exit;
?>
