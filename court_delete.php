<?php
session_start();
require_once "config.php";

// เฉพาะ admin level 3 เท่านั้น
if (!isset($_SESSION['username']) || $_SESSION['level'] != 3) {
    header("Location: index.php");
    exit;
}

$court_id = intval($_POST['court_id']);

if (mysqli_query($connect, "DELETE FROM court WHERE court_id = $court_id")) {
    $_SESSION['success_msg'] = "ลบสนามเรียบร้อยแล้ว";
} else {
    $_SESSION['errors_msg'] = "เกิดข้อผิดพลาด: " . mysqli_error($connect);
}

header("Location: court_management.php");
exit;
?>
