<?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/checkUser.php" ?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");
?>
<?php $userResult = checkUserStatus(); ?>
<?php
print_r($_POST);
header('Content-Type: application/json');
$aResult = array();
if (!isset($_POST['lessonNumber'])) {
    $aResult['error'] = 'No lessonNumber arguments!';
}
if (!isset($_POST['wordPerMinute'])) {
    $aResult['error'] = 'No wordPerMinute arguments!';
}
if (!isset($_POST['accuracy'])) {
    $aResult['error'] = 'No accuracy arguments!';
}
if (!isset($_POST['actualAccuracy'])) {
    $aResult['error'] = 'No actualAccuracy arguments!';
}
if (!isset($aResult['error'])) {
    $getExistSQL = "SELECT * FROM stats 
        WHERE username = '" . $userResult . "' AND lessonNumber = '" . $_POST['lessonNumber'] . "'";
    $updateSQL = "UPDATE stats 
        SET wordPerMinute='" . $_POST['wordPerMinute'] . "', accuracy='" . $_POST['accuracy'] . "', actualAccuracy='" . $_POST['actualAccuracy'] . "'
        WHERE username = '" . $userResult . "' AND lessonNumber = '" . $_POST['lessonNumber'] . "'";
    $insertSQL = "INSERT INTO stats (username, lessonNumber, wordPerMinute, accuracy, actualAccuracy)
        VALUES ( '" . $userResult . "','" .  $_POST['lessonNumber'] . "','" . $_POST['wordPerMinute'] . "','" . $_POST['accuracy'] . "','" . $_POST['actualAccuracy'] . "')";



    $dataResult = $conn->query($getExistSQL);
    if ($dataResult->num_rows > 0) {
        $dataResult = $conn->query($updateSQL);
    } else {
        $dataResult = $conn->query($insertSQL);
    }
}
echo json_encode($aResult); ?>