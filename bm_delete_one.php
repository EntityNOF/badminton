<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

$booking_id = intval($_POST['booking_id']);
$redirect   = isset($_POST['redirect']) ? $_POST['redirect'] : 'booking_management.php';

// ลบไฟล์สลิปด้วยถ้ามี
$r   = mysqli_query($connect, "SELECT slip_image FROM booking WHERE booking_id=$booking_id");
$row = mysqli_fetch_assoc($r);
if ($row && $row['slip_image'] && file_exists("uploads/" . $row['slip_image'])) {
    unlink("uploads/" . $row['slip_image']);
}

mysqli_query($connect, "DELETE FROM booking WHERE booking_id=$booking_id");
$_SESSION['book_msg'] = "ลบรายการจองแล้ว";

header("Location: " . $redirect);
exit;
?>
