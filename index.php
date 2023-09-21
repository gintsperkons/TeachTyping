<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/main.css">
    <title>TeachTyping|Homepage</title>
</head>

<body class="homebody">
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/checkUser.php" ?>
    <?php $result = checkUserStatus(); ?>
    <?php if (!$result) { ?>
        <div class="homeContainer">
            <div class="monitor">
                <div class="display">
                    <div class="homeTitleWelcome">Welcome to</div>
                    <div class="homeTitle">TeachTyping<i class="titleCursor">|</i></div>
                </div>
                <div class="stand-p1"></div>
                <div class="stand-p2"></div>
            </div>
            <div class="desk">
                <div class="homekeyboard">
                    <div class="textContainer">
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                        <span class="textCharacter active"></span>
                    </div>
                    <div class="homeButtonContainer">
                        <div id="loginContainer">
                            <button onclick="location.href='./auth/login.php'" id="loginBtn" class="homeButton">Login</button>
                        </div>
                        <div id="registerContainer">
                            <button onclick="location.href='./auth/register.php'" id="registerBtn" class="homeButton">Register</button>
                        </div>
                    </div>

                </div>
                <div class="homeMouse">
                    <div class="homemousebuttons"></div>
                    <div class="hommemousescrollwheel"></div>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($result) { ?>
        <div id="titleBar" class="titleContainer">
            <div id="welcomeText" class="welcomeText">
                <?php if ($result) { ?>
                    <h4>Welcome <?php echo $result ?> </h4>

                <?php } ?>
            </div>
            <div id="title" class="title">TeachTyping<i class="titleCursor">|</i></div>
            <div class="navContainer">
                <div id="navBar" class="navButtons">
                    <?php if ($result) { ?>
                        <div id="loginContainer">
                            <a href="./auth/logout.php" id="logoutBtn">Logout</a>
                        </div>
                    <?php } ?>
                    <?php if (checkIsAdmin()) { ?>
                        <div id="adminContainer">
                            <a href="./admin.php" id="adminBtn">Administrator</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="mainContainer">
            <?php
            require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");

            $statsSQL = "SELECT * FROM stats WHERE username = '" . $result . "'";
            $userStats = $conn->query($statsSQL);
            $statsData = [];
            while ($row = $userStats->fetch_assoc()) {

                $statsData[$row["lessonNumber"]] = (array)[
                    'wordPerMinute' => $row["wordPerMinute"],
                    'accuracy' => $row["accuracy"],
                    'actualAccuracy' => $row["actualAccuracy"]
                ];
            }


            $categorySQL = "SELECT * FROM lessoncategories";
            $categoryResult = $conn->query($categorySQL);
            if ($categoryResult->num_rows > 0) {
                $totalCategories = $categoryResult->num_rows;
                $currentCategoryIndex = 0;

                while ($categoryRow = $categoryResult->fetch_assoc()) {
                    $categoryId = $categoryRow['ID'];
                    $lessonSQL = "SELECT lessons.id as id, lessons.title as title, lessons.lessonNumber as num, lessoncategories.name as category FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.id WHERE lessons.categoryid = $categoryId ORDER BY lessoncategories.name, lessons.lessonNumber";
                    $lessonResult = $conn->query($lessonSQL);
            ?>
                    <div class="catalogContainer">
                        <div class="catagoryTitle"><?php echo $categoryRow['name'] ?></div>
                        <div class="cardContainer">
                            <?php
                            if ($lessonResult->num_rows > 0) {
                                while ($lessonRow = $lessonResult->fetch_assoc()) {
                                    if ($lessonRow['category'] === $categoryRow['name']) {
                            ?>
                                        <a href="lesson.php?id=<?php echo $lessonRow['id']; ?>" class="lessonLink">
                                            <div class="cardNumber"><?php echo $lessonRow['num'] ?></div>
                                            <?php
                                            if (isset($statsData[$lessonRow['id']])) {
                                            ?>
                                                <div class="stats">
                                                    <div class="wordPerMinute"><?php echo $statsData[$lessonRow['id']]['wordPerMinute']; ?><br>w/p</div>
                                                    <div class="accuracy"><?php echo $statsData[$lessonRow['id']]['accuracy']; ?><br>acc</div>
                                                    <div class="actualAccuracy"><?php echo $statsData[$lessonRow['id']]['actualAccuracy']; ?><br>t-acc</div>
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="categoryTitle"><?php echo $lessonRow['category'] ?></div>
                                            <div class="cardTitle"><?php echo $lessonRow['title'] ?></div>
                                        </a>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </div>
                        <?php
                        $currentCategoryIndex++;
                        if ($currentCategoryIndex < $totalCategories) {
                            echo '<div class="line"></div>';
                        }
                        ?>
                    </div>
            <?php
                }
            }
            ?>

        </div>
    <?php } ?>
</body>

</html>