<!DOCTYPE html>
<html lang="en">
<?php require "./auth/checkUser.php" ?>
<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");
if (!isset($_COOKIE['userRemember'])) {
    header("Location: /");
    exit();
}
$getLessonId = $_GET["id"];
if (!isset($getLessonId)) {
    header("Location: /");
    exit();
}
$passSQL = "SELECT lessons.title as title, lessons.ID as id, lessoncategories.name as category, lessons.text as text FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.id WHERE lessons.id = '$getLessonId'";
$dataResult = $conn->query($passSQL);
if ($dataResult->num_rows > 0) {
    $row = $dataResult->fetch_assoc();
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/main.css">
    <title>TeachTyping|Lesson-<?php echo $row["title"] ?></title>
</head>

<body>
    <input id="lessonNumber" type="hidden" value="<?php echo $row["id"] ?>">
    <?php $userResult = checkUserStatus(); ?>
    <div id="titleBar" class="titleContainer">
        <div id="welcomeText" class="welcomeText">
            <?php if ($userResult) { ?>
                <h4>Welcome <?php echo $userResult ?> </h4>

            <?php } ?>
        </div>
        <div id="title" class="title"><?php echo $row["title"] ?><i class="titleCursor">|</i></div>
        <div id="navBar" class="navContainer">
            <div id="homeContainer" class="navButtons">
                <a href="index.php" id="homeBtn">Home</a>

                <?php if (!$userResult) { ?>
                    <div id="loginContainer">
                        <a href="/auth/login.php" id="loginBtn">Login</a>
                    </div>
                    <div id="registerContainer">
                        <a href="/auth/register.php" id="registerBtn">Register</a>
                    </div>
                <?php } ?>
                <?php if ($userResult) { ?>
                    <div id="loginContainer">
                        <a href="/auth/logout.php" id="logoutBtn">Logout</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="resizeButtonContainer">
        <button class="resizeButton" onclick="resizeInput(1)">x1</button>
        <button class="resizeButton" onclick="resizeInput(2)">x0.75</button>
        <button class="resizeButton" onclick="resizeInput(3)">x0.5</button>
        <button class="resizeButton" onclick="resizeInput(4)">x0.25</button>
    </div>
    <div class="mainTextContainer">
        <div class="tutorial">
            <h2>How to use</h2>
            <div class="info">
                <span class="keyIconInfo">a</span>
                <p>Press the corresponding letter on your keyboard.</p>
            </div>
            <div class="info">
                <span class="keyIconInfo spaceIconInfo"></span>
                <p>This symbol represents a space character.</p>
            </div>
        </div>
        <div class="keyboard">
            <div id="textContainer" class="textContainer">
                <?php $firstLetter = true; ?>
                <?php $wordList = preg_split('/\s+/', $row["text"]) ?>
                <?php for ($i = 0; $i < count($wordList); $i++) { ?>
                    <span class="textWord" wordNumber="<?php echo $i + 1 ?>">
                        <?php foreach (mb_str_split($wordList[$i]) as $character) { ?>
                            <?php if ($firstLetter) { ?>
                                <span class="textCharacter active"><?php echo $character ?></span>
                            <?php } ?>
                            <?php if (!$firstLetter) { ?>
                                <span class="textCharacter upcoming"><?php echo $character ?></span>
                            <?php } ?>
                            <?php $firstLetter = false; ?>
                        <?php } ?>

                        <?php if (array_key_exists($i + 1, $wordList)) { ?>
                            <span class="textCharacter space"> </span>
                        <?php } ?>
                    </span>
                <?php } ?>

            </div>
            <div id="spacebar" class="spaceBar">
                <a href="index.php" id="goBack" class="goBack">You may now return back to the home page</a>
            </div>
        </div>
        <div class="mouse">
            <div class="mousebuttons"></div>
            <div class="mousescrollwheel"></div>
            <div class="infoContainer" id="results">
                <p id="wordsPerMinute"> </p>
                <p id="acuracy"> </p>
                <p id="actualAcuracy"> </p>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="./static/js/lessonProcesing.js"></script>
    <script>
        var r = document.querySelector(':root');

        function resizeInput(size) {
            if (size == 1) {
                r.style.setProperty('--type-letter-box-size', '55px');
                r.style.setProperty('--type-letter-box-radius', '15px');
                r.style.setProperty('--type-letter-size', '40px');
                r.style.setProperty('--type-anim-f1-size', '40px');
                r.style.setProperty('--type-anim-f2-size', '35px');
                r.style.setProperty('--type-anim-f3-size', '50px');
            }
            if (size == 2) {
                r.style.setProperty('--type-letter-box-size', '45px');
                r.style.setProperty('--type-letter-box-radius', '10px');
                r.style.setProperty('--type-letter-size', '30px');
                r.style.setProperty('--type-anim-f1-size', '30px');
                r.style.setProperty('--type-anim-f2-size', '25px');
                r.style.setProperty('--type-anim-f3-size', '40px');
            }
            if (size == 3) {
                r.style.setProperty('--type-letter-box-size', '35px');
                r.style.setProperty('--type-letter-box-radius', '5px');
                r.style.setProperty('--type-letter-size', '20px');
                r.style.setProperty('--type-anim-f1-size', '20px');
                r.style.setProperty('--type-anim-f2-size', '15px');
                r.style.setProperty('--type-anim-f3-size', '30px');
            }
            if (size == 4) {
                r.style.setProperty('--type-letter-box-size', '25px');
                r.style.setProperty('--type-letter-box-radius', '5px');
                r.style.setProperty('--type-letter-size', '18px');
                r.style.setProperty('--type-anim-f1-size', '10px');
                r.style.setProperty('--type-anim-f2-size', '5px');
                r.style.setProperty('--type-anim-f3-size', '20px');
            }
        };

        function countTotalChar(){
            var totalChar = 0;
            var textContainer = document.getElementById("textContainer");
            var textWords = textContainer.getElementsByClassName("textWord");
            for (var i = 0; i < textWords.length; i++) {
                var textCharacters = textWords[i].getElementsByClassName("textCharacter");
                for (var j = 0; j < textCharacters.length; j++) {
                    totalChar++;
                }
            }
            return totalChar;
        }

        function autoResize(){
            var totalChar = countTotalChar();
            if(totalChar >= 300){
                resizeInput(4);
                return;
            }
            if(totalChar >= 170){
                resizeInput(3);
                return;
            }
            if(totalChar >= 100){
                resizeInput(2);
                return;
            }
            resizeInput(1);
        }

    </script>
</body>

</html>