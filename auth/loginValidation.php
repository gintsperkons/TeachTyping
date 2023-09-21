<?php
define('ROOT_PATH', dirname(__DIR__) . '/');
//link to config/database.php
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
    if (!isset($_POST["password"])) {
        return "Empty password field";
    }
    if (mb_strlen($_POST["password"]) < 5) {
        return "Password needs at least 5 characters long";
    }
    if (mb_strlen($_POST["password"]) > 50) {
        return "Password needs less then 50 characters long";
    }
    $passwordIsValid = true;
}

function loginUser()
{
    global $conn;
    global $passwordIsValid;
    global $usernameIsValid;
    if (!$usernameIsValid || !$passwordIsValid) {
        return;
    }
    $passSQL = "SELECT ID,username,password FROM users WHERE username='" . $_POST["username"] . "'";
    $result = $conn->query($passSQL);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($_POST["username"] == $row["username"] && password_verify($_POST["password"], $row["password"])) {
                $cookie_name = "userRemember";
                $cookie_value = $row["ID"];
                if (!isset($_COOKIE[$cookie_name])) {
                    $str = rand();
                    $cookie_value = hash("sha256", $str);
                    $sql = "UPDATE users SET UserAccKey='" . $cookie_value . "' WHERE id=" . $row["ID"];



                    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
                    if ($conn->query($sql) === TRUE) {
                        header("Location: ../index.php");
                        exit();
                    } else {
                        echo "Error updating record: " . $conn->error;
                    }
                } else {
                }
            } else {
            }
        }
    } else {
        echo "No User Found";
    }
}
