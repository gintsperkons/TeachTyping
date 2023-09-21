<?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/checkUser.php" ?>
<?php $result = checkUserStatus(); ?>
<?php if (!$result) {
    header("Location: /");
    exit();
} ?>
<?php if ($result) {
    unset($_COOKIE['userRemember']);
    setcookie('userRemember', null, -1, '/');
    header("Location: /");
    exit();
} ?>