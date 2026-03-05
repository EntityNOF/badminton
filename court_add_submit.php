<?php
session_start();
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

// ต้องเป็น POST เท่านั้น
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: court_add.php");
    exit;
}

$court_number   = intval($_POST['court_number']);
$court_name     = $_POST['court_name'];
$price_per_hour = $_POST['price_per_hour'];
$is_active      = intval($_POST['is_active']);

// ตรวจสอบข้อมูลครบถ้วน
if (empty($court_name) || $court_number <= 0 || $price_per_hour <= 0) {
    $_SESSION['errors_msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    header("Location: court_add.php");
    exit;
}

// ตรวจสอบหมายเลขสนามซ้ำ
$check = mysqli_query($connect, "SELECT court_id FROM court WHERE court_number = $court_number");
if (mysqli_num_rows($check) > 0) {
    $_SESSION['errors_msg'] = "หมายเลขสนามนี้มีอยู่ในระบบแล้ว";
    header("Location: court_add.php");
    exit;
}

// บันทึกข้อมูลสนาม
$sql = "INSERT INTO court (court_number, court_name, price_per_hour, is_active)
        VALUES ($court_number, '$court_name', $price_per_hour, $is_active)";

if (mysqli_query($connect, $sql)) {
    $_SESSION['success_msg'] = "เพิ่มสนามเรียบร้อยแล้ว";
    header("Location: court_management.php");
} else {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาด: " . mysqli_error($connect);
    header("Location: court_add.php");
}
exit;
?>
