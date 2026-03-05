<?php
session_start();
include_once "config.php";

$firstname  = trim($_POST['firstname']);
$lastname   = trim($_POST['lastname']);
$phone      = trim($_POST['phone']);
$email      = trim($_POST['email']);
$gender     = $_POST['gender'];
$birth_date = $_POST['birth_date'];
$username   = trim($_POST['username']);
$password   = trim($_POST['password']);

// ตรวจสอบข้อมูลครบถ้วน
if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
    $_SESSION['errors_msg'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    header("Location: register.php");
    exit();
}

// ตรวจสอบ username ซ้ำ
$checkQuery  = "SELECT user_id FROM systemuser WHERE username = '$username'";
$checkResult = mysqli_query($connect, $checkQuery);

if (!$checkResult) {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาดในการตรวจสอบ: " . mysqli_error($connect);
    header("Location: register.php");
    exit();
}

if (mysqli_num_rows($checkResult) > 0) {
    $_SESSION['errors_msg'] = "ชื่อผู้ใช้นี้มีอยู่ในระบบแล้ว";
    header("Location: register.php");
    exit();
}

// บันทึกข้อมูลชื่อ-นามสกุลในตาราง employee ก่อน
$empQuery  = "INSERT INTO employee (firstname, lastname, phone, email, gender, birth_date) VALUES ('$firstname', '$lastname', '$phone', '$email', '$gender', " . ($birth_date ? "'$birth_date'" : 'NULL') . ")";
$empResult = mysqli_query($connect, $empQuery);

if (!$empResult) {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . mysqli_error($connect);
    header("Location: register.php");
    exit();
}

$employee_id = mysqli_insert_id($connect);

// บันทึกบัญชีผู้ใช้ระบบ (level 2 = ผู้ใช้ทั่วไป)
$insertQuery  = "INSERT INTO systemuser (username, password, level, employee_id) VALUES ('$username', '$password', 2, $employee_id)";
$insertResult = mysqli_query($connect, $insertQuery);

if ($insertResult) {
    $_SESSION['success_msg'] = "สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ";
    header("Location: login.php");
} else {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาดในการสมัครสมาชิก: " . mysqli_error($connect);
    header("Location: register.php");
}
exit();
?>