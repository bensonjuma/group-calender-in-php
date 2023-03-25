<?php
ini_set("session.cookie_httponly", 1);
session_start();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

// destroy session
session_destroy();
// unset all session variables
unset($_SESSION["user_id"]);
unset($_SESSION["user_name"]);
unset($_SESSION["user_email"]);
unset($_SESSION["csrf_token"]);

echo "success";
