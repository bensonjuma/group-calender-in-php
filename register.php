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
        echo "Invalid CSRF token.";
        die("Invalid CSRF token.");
    }
    $username = cleanData($_POST["name"]);
    $email = cleanData($_POST["email"]);
    $password = cleanData($_POST["password"]);

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the username or email already exists
    $query = "SELECT * FROM users WHERE name = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username or email already exists.";
    } else {
        // Insert the user
        $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "User registered successfully. success";
        } else {
            echo "Error: " . $query . "<br>" . $conn->error;
        }
    }

    $stmt->close();
}
