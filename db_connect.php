<?php
$dbhost = 'sql312.infinityfree.com';
$dbuser = 'if0_39879664';
$dbpass = 'dxm4nKUyU1LHRv';
$dbname = 'if0_39879664_attendance_db';

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8");
?>
