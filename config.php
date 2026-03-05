<?php
$server = "localhost";
$user = "root";
$password = "";
$dbname = "badminton_db";

$connect = mysqli_connect($server, $user, $password, $dbname);

if (!$connect)
{
    die ("Error: Cannot connect to database $dbname on server $server using username $user (" .mysqli_connect_errno(). ", ".mysqli_connect_error(). ")");
}
?>