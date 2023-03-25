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
    var_dump($_POST);
    if (!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
        // echo "Invalid CSRF token.";
        die("Invalid CSRF token.");
    }
    $email = cleanData($_POST["email"]);
    $password = cleanData($_POST["password"]);

    // login user
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];
        if (password_verify($password, $hashed_password)) {
            // echo "Login successful";
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["user_name"] = $row["name"];
            $_SESSION["user_email"] = $row["email"];
            echo "success";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User does not exist";
    }
}
