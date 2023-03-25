<?php
ini_set("session.cookie_httponly", 1);
session_start();

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

/* if(!hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])) {
    die("Invalid CSRF token.");
} */

if (isset($_SESSION["user_id"])) {
    echo "success";
} else {
    echo "failure";
}
