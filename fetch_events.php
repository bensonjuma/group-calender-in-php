<?php
ini_set("session.cookie_httponly", 1);
session_start();
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
// echo $_SESSION["csrf_token"];
// include database connection
include "config.php";

// select all data from database for this user
$uid = $_SESSION["user_id"];
$query = "SELECT * FROM events WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();
$events = array();
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}
echo json_encode($events);
