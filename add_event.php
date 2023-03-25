<?php
ini_set("session.cookie_httponly", 1);
session_start();
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
// echo $_SESSION["csrf_token"];
// include database connection
include "config.php";

// Function to clean data
function cleanData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var_dump($_POST);
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        // echo "Invalid CSRF token.";
        die("Invalid CSRF token.");
    }
    $title = cleanData($_POST["title"]);
    $date = cleanData($_POST["date"]);
    $time = cleanData($_POST["time"]);
    $description = cleanData($_POST["description"]);
    $user_id = $_SESSION["user_id"];

    // check if the event already exists from the user and date
    $query = "SELECT * FROM events WHERE user_id = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $user_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();
    // if the event already exists update the event
    if ($result->num_rows > 0) {
        $query = "UPDATE events SET title = ?, time = ?, description = ? WHERE user_id = ? AND date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssis", $title, $time, $description, $user_id, $date);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
        die();
    } else {
        // insert event
        $query = "INSERT INTO events (user_id, title, date, time, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issss", $user_id, $title, $date, $time, $description);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }
    }
    $stmt->close();
}
