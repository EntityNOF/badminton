<?php
session_start();
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

$del_id = intval($_POST['user_id']);

// ใช้ user_id จาก session ที่เก็บไว้ตอน login โดยตรง
$my_id = intval($_SESSION['user_id']);

// ป้องกันลบตัวเอง
if ($del_id == $my_id) {
    $_SESSION['errors_msg'] = "ไม่สามารถลบบัญชีของตัวเองได้";
    header("Location: user_management.php");
    exit;
}

// ลบ user
if (mysqli_query($connect, "DELETE FROM systemuser WHERE user_id = " . $del_id)) {
    $_SESSION['success_msg'] = "ลบบัญชีผู้ใช้เรียบร้อยแล้ว";
} else {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาด: " . mysqli_error($connect);
}

header("Location: user_management.php");
exit;
?>
