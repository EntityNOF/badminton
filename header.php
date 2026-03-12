<html>
    <link rel="stylesheet" href="style.css">
    <body>
        <header>
        <a href="index.php"><img src="images/cake-shop-icon-vector.jpg" alt="logo" class="logo"></a>
        <ul class="bar">
            <li><a href="index.php">หน้าแรก</a></li>


<?php
session_start(); 

require_once "config.php";
if (isset($_SESSION['username'])) {
    // Show menu based on level
    $level = $_SESSION['level'];
    
    if ($level == 2) {
        echo '<li><a href="my_booking.php">การจองของฉัน</a></li>';
        echo '<li><a href="court_booking.php">จองสนาม</a></li>';
    }
    
    if ($level == 3) {
        echo '<li><a href="my_booking.php">การจองของฉัน</a></li>';
        echo '<li><a href="court_booking.php">จองสนาม</a></li>';
        echo '<li><a href="court_management.php">จัดการสนาม</a></li>';
        echo '<li><a href="user_management.php">ระบบผู้ใช้</a></li>';
        echo '<li><a href="booking_management.php">จัดการการจอง</a></li>';
    }
    
    echo '<li><a href="logout.php">Logout - </a>';
    echo "<span class='user-desc'>&nbsp;[";
    echo $_SESSION['firstname']
    ." ".$_SESSION['lastname']
    ." - Level: ".$_SESSION['level'];
    echo "]</span></li>";
    echo '</ul>';
    echo '</header>';
    echo '</html>';
}else {
    echo '<li><a href="court_booking.php">จองสนาม</a></li>';
    echo '<li><a href="login.php">Login/Register</a></li>';
    echo '</ul>';
    echo '</header>';
    echo '</html>';
}
?>