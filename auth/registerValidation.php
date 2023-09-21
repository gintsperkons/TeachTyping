<?php
define('ROOT_PATH', dirname(__DIR__) . '/');
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");
$usernameIsValid = false;
$passwordIsValid = false;

function validateUsername()
{
    global $usernameIsValid;
    if (!isset($_POST["username"])) {
        return "username can not be empty";
    }
    if (mb_strlen($_POST["username"]) < 3) {
        return "Username needs at least 3 characters long";
    }
    if (mb_strlen($_POST["username"]) > 50) {
        return "Username needs less then 50 characters long";
    }
    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $_POST["username"])) {
        return "Username can only contain letters, numbers, and underscores";
    }
    $usernameIsValid = true;
}

function validatePassword()
{
    global $passwordIsValid;
    if (!isset($_POST["password"]) || !isset($_POST["password2"])) {
        return "Empty password field";
    }
    if ($_POST["password"] != $_POST["password2"]) {
        return "Passwords doesn't match";
    }
    if (mb_strlen($_POST["password"]) < 5) {
        return "Password needs at least 5 characters long";
    }
    if (mb_strlen($_POST["password"]) > 50) {
        return "Password needs less then 50 characters long";
    }
    $passwordIsValid = true;
}

function registerUser()
{
    global $conn;
    global $passwordIsValid;
    global $usernameIsValid;
    if (!$usernameIsValid || !$passwordIsValid) {
        return;
    }
    $hashed_password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password)
    VALUES (?, ?)");
    $stmt->bind_param("ss", $_POST["username"], $hashed_password);
    $passSQL = "SELECT username,password FROM users WHERE username='" . $_POST["username"] . "'";
    $result = $conn->query($passSQL);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($_POST["username"] == $row["username"]) {
                echo "User exists try to login";
            } else {
            }
        }
    } else {
        if ($stmt->execute() === TRUE) {
            header("Location: ./login.php");
            exit();
        } else {
            echo "Error: ";
        }
    }
}
