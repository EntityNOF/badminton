<?php
session_start();
include_once "config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$userQuery = "SELECT * FROM systemuser AS u 
              INNER JOIN employee AS e ON u.employee_id = e.employee_id 
              WHERE u.username = '$username'";
$result = mysqli_query($connect, $userQuery);

if (!$result) {
    die("Could not successfully run the query: " . mysqli_error($connect));
}

if (mysqli_num_rows($result) == 0) {
    $_SESSION['errors_msg'] = "Username or Password is incorrect.";
    header("Location: login.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

if ($row['password'] == $password) {
    $_SESSION['username']  = $row['username'];
    $_SESSION['firstname'] = $row['firstname'];
    $_SESSION['lastname']  = $row['lastname'];
    $_SESSION['level']     = $row['level'];
    $_SESSION['user_id']   = $row['user_id'];
    header("Location: index.php");
} else {
    $_SESSION['errors_msg'] = "Username or Password is incorrect.";
    header("Location: login.php");
}
exit();
?>