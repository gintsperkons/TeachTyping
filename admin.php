<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./static/css/main.css">
    <title>TeachTyping|Homepage</title>
</head>

<body>
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/checkUser.php" ?>
    <?php $result = checkUserStatus(); ?>
    <div id="titleBar" class="titleContainer">
        <div id="welcomeText" class="welcomeText">
            <?php if ($result) { ?>
                <h4>Welcome <?php echo $result ?> </h4>

            <?php } ?>
        </div>
        <div id="title" class="title">TeachTyping<i class="titleCursor">|</i></div>
        <div class="navContainer">
            <div id="navBar" class="navButtons">
                <div id="homeContainer" class="navButtons">
                    <a href="index.php" id="homeBtn">Home</a>
                    <?php if (!$result) { ?>
                        <div id="loginContainer">
                            <a href="./auth/login.php" id="loginBtn">Login</a>
                        </div>
                        <div id="registerContainer">
                            <a href="./auth/register.php" id="registerBtn">Register</a>
                        </div>
                    <?php } ?>
                    <?php if ($result) { ?>
                        <div id="loginContainer">
                            <a href="./auth/logout.php" id="logoutBtn">Logout</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php if (!$result) { ?>
        <div class="textContainer">Login to view more.</div>
    <?php } ?>
    <?php if (checkIsAdmin()) { ?>
        <div class="mainContainer">
            <div class="categoryContainer">
                <?php
                require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");

                ?>
                <div class="rowContainerAdmin">
                    <label for="lessonID" class="adminLabel">Lesson ID</label>
                    <input id="lessonID" list='lessons' type='number' name="lessonID" class="formInput adminInput" placeholder="Pick a lesson or type a new ID to start" />
                    <datalist id="lessons">
                        <?php
                        $lessonsSQL = "SELECT lessons.title as title, lessons.ID as id, lessoncategories.name as category, lessons.text as text FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.id";
                        $lessonsData = $conn->query($lessonsSQL);

                        if ($lessonsData->num_rows > 0) {
                            while ($row = $lessonsData->fetch_assoc()) {
                        ?>
                                <option style="color:black" value="<?php echo $row['id'] ?>">
                                    <div><?php echo $row['title'] ?></div>
                                    <div><?php echo $row['category'] ?></div>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </datalist>
                    <button id="getLessonData" data="get" class="adminButton">Get data</button>
                </div>
                <div class="rowContainerAdmin">
                    <label for="lessonID" class="adminLabel">Lesson number</label>
                    <input id="lessonNumber" type='number' name="lessonNumber" class="formInput adminInput" placeholder="Example: 41" />
                </div>
                <div class="rowContainerAdmin">
                    <label for="lessonID" class="adminLabel">Category name</label>
                    <input id="categoryName" type='text' list="categories" name="categoryName" class="formInput adminInput" placeholder="Example: Beginner" />
                    <datalist id="categories">
                        <?php
                        $categorySQL = "SELECT * FROM lessoncategories";
                        $categoryData = $conn->query($categorySQL);

                        if ($categoryData->num_rows > 0) {
                            while ($row = $categoryData->fetch_assoc()) {
                        ?>
                                <option class="formInput adminInput" value="<?php echo $row['name'] ?>"></option>
                        <?php
                            }
                        }
                        ?>
                    </datalist>
                </div>
                <div class="rowContainerAdmin">
                    <label for="lessonID" class="adminLabel">Lesson title</label>
                    <input id="lessonTitle" type='text' name="lessonTitle" class="formInput adminInput" placeholder="Example: Lesson #3" />
                </div>
                <div class="rowContainerAdmin">
                    <label for="lessonID" class="adminLabel">Lesson text</label>
                    <textarea id="lessonText" type='text' name="lessonText" class="adminInputTextarea formInput" placeholder="Example: This sentence will be typed out!"></textarea>
                </div>
                <div class="rowContainerAdmin">
                    <button id="insertData" data="insert" class="adminButton">Insert</button>
                    <button id="updateData" data="update" class="adminButton">Update</button>
                    <button id="deleteData" data="delete" class="adminButton">Delete</button>
                </div>
                <div id="errorText" class="usernameErrorMessage adminError"></div>
            </div>
        <?php } else { ?>
            <div class="error">You are not Admin!</div>
        <?php } ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script src="static/js/admin.js"></script>
</body>

</html>