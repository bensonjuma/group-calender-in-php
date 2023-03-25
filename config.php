<?php
// database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "group_calendar";

// connect to database
$conn = mysqli_connect($host, $user, $pass, $db);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
