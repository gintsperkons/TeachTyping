<?php
echo $_SERVER['DOCUMENT_ROOT'];
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");
function checkUserStatus()
{
    global $conn;
    $cookie_name = "userRemember";
    if (isset($_COOKIE[$cookie_name])) {

        $sql = "SELECT username FROM users WHERE UserAccKey='" . $_COOKIE[$cookie_name] . "'";
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["username"];
            }
        } else {
            return false;
        }
    }
}
function checkIsAdmin()
{
    global $conn;
    $cookie_name = "userRemember";
    if (isset($_COOKIE[$cookie_name])) {

        $sql = "SELECT username, isadmin FROM users WHERE UserAccKey='" . $_COOKIE[$cookie_name] . "'";
        $result = $conn->query($sql);


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                return $row["isadmin"];
            }
        } else {
            return false;
        }
    }
}
