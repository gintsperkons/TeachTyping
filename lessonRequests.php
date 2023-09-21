    
    <?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/checkUser.php" ?>
    <?php
    require_once($_SERVER['DOCUMENT_ROOT'] . "/config/database.php");
    ?><?php
        header('Content-Type: application/json');

        $aResult = array();

        if (!isset($_POST['functionname'])) $aResult['error'] = 'No function name!';
        if (!isset($_POST['lessonID'])) $aResult['error'] = 'No lesson id!';
        if (!isset($_POST['lessonNumber'])) $aResult['error'] = 'No lesson number!';
        if (!isset($_POST['categoryName'])) $aResult['error'] = 'No category name!';
        if (!isset($_POST['lessonTitle'])) $aResult['error'] = 'No lesson title!';
        if (!isset($_POST['lessonText'])) $aResult['error'] = 'No lesson text!';
        if (!isset($aResult['error'])) {
            switch ($_POST['functionname']) {
                case "get":
                    if ($_POST['lessonID'] == "") {
                        $aResult['error'] = 'No lesson id!';
                        break;
                    };
                    $lessonSQL = "SELECT lessons.ID as id, lessons.title as title, lessons.lessonNumber as num, lessons.text as text, lessoncategories.name as category FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.id WHERE lessons.ID = '" . $_POST['lessonID'] . "'";
                    $lessonResult = $conn->query($lessonSQL);
                    $row = $lessonResult->fetch_assoc();
                    $aResult['lessonNumber'] = $row["num"];
                    $aResult['categoryName'] = $row["category"];
                    $aResult['lessonTitle'] = $row["title"];
                    $aResult['lessonText'] = $row["text"];
                    break;
                case "insert":
                    if ($_POST['lessonNumber'] == "") {
                        $aResult['error'] = 'No lesson number!';
                        break;
                    };
                    if ($_POST['categoryName'] == "") {
                        $aResult['error'] = 'No category name!';
                        break;
                    };
                    if ($_POST['lessonTitle'] == "") {
                        $aResult['error'] = 'No lesson title!';
                        break;
                    };
                    if ($_POST['lessonText'] == "") {
                        $aResult['error'] = 'No lesson text!';
                        break;
                    };
                    $stmtInsertData = $conn->prepare("INSERT INTO lessons (lessonNumber, text, title, categoryID) VALUES (?, ?, ?, ?)");
                    $stmtInsertData->bind_param("ssss", $lessonNumber, $text, $title, $categoryID);


                    $categoryID = getCategoryID();
                    $lessonNumber = $_POST["lessonNumber"];
                    $text = $_POST["lessonText"];
                    updateNumberAdd($lessonNumber, $categoryID);
                    $title = $_POST["lessonTitle"];
                    $stmtInsertData->execute();
                    $stmtInsertData->close();
                    break;
                case "update":
                    if ($_POST['lessonID'] == "") {
                        $aResult['error'] = 'No lesson id!';
                        break;
                    };
                    if ($_POST['lessonNumber'] == "") {
                        $aResult['error'] = 'No lesson number!';
                        break;
                    };
                    if ($_POST['categoryName'] == "") {
                        $aResult['error'] = 'No category name!';
                        break;
                    };
                    if ($_POST['lessonTitle'] == "") {
                        $aResult['error'] = 'No lesson title!';
                        break;
                    };
                    if ($_POST['lessonText'] == "") {
                        $aResult['error'] = 'No lesson text!';
                        break;
                    };
                    $stmtUpdateData = $conn->prepare("UPDATE lessons set text = ?, title = ?, categoryID= ?, lessonNumber = ? WHERE ID = ?");
                    $stmtUpdateData->bind_param("sssss", $text, $title, $categoryID, $lessonNumber, $lessonID);


                    $lessonSQL = "SELECT lessons.title as title, lessons.lessonNumber as num, lessons.categoryid as category, lessons.text as text FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.name WHERE lessons.id = '" . $_POST["lessonID"] . "'";
                    $lessonResult = $conn->query($lessonSQL);
                    if ($lessonResult->num_rows < 1) {
                        $aResult['error'] = 'Wrong lesson number!';
                        break;
                    }
                    $row = $lessonResult->fetch_assoc();
                    $previousNumber = $row["num"];
                    $lessonID = $_POST["lessonID"];
                    $categoryID = getCategoryID();
                    $lessonNumber = $_POST["lessonNumber"];
                    $text = $_POST["lessonText"];
                    $title = $_POST["lessonTitle"];
                    updateNumberSub($previousNumber, $categoryID);
                    updateNumberAdd($lessonNumber, $categoryID);
                    $stmtUpdateData->execute();
                    $stmtUpdateData->close();
                    removeUnusedCategories();
                    break;
                case "delete":
                    if ($_POST['lessonID'] == "") {
                        $aResult['error'] = 'No lesson id!';
                        break;
                    }
                    $lessonSQL = "SELECT lessons.title as title, lessons.lessonNumber as num, lessons.categoryid as category, lessons.text as text FROM lessons LEFT JOIN lessoncategories ON lessons.categoryid = lessoncategories.name WHERE lessons.id = '" . $_POST["lessonID"] . "'";
                    $lessonResult = $conn->query($lessonSQL);
                    if ($lessonResult->num_rows < 1) {
                        $aResult['error'] = 'Wrong lesson number!';
                        break;
                    }
                    $row = $lessonResult->fetch_assoc();
                    $previousNumber = $row["num"];
                    $categoryID = $row["category"];
                    updateNumberSub($previousNumber, $categoryID);
                    $stmtDeleteData = $conn->prepare("DELETE FROM lessons WHERE id = ?");
                    $stmtDeleteData->bind_param("s", $lessonID);
                    $lessonID = $_POST["lessonID"];
                    $stmtDeleteData->execute();
                    $stmtDeleteData->close();
                    removeUnusedCategories();
                    break;
                default:
                    $aResult['error'] = 'Not found function ' . $_POST['functionname'] . '!';
                    break;
            }
        }
        if (!isset($aResult['error'])) {
        }

        echo json_encode($aResult);

        function updateNumberSub($currentID, $categoryID)
        {
            global $conn;
            $lessonSQL = "SELECT lessons.id as id, lessons.lessonNumber as ln FROM lessons WHERE lessons.lessonNumber = '" . ($currentID + 1) . "' AND  lessons.categoryID = '" . $categoryID . "'";
            $lessonResult = $conn->query($lessonSQL);
            $lessonNumberUpdate = "";
            $updateNumber = "";
            $stmtUpdateLessonNumber = $conn->prepare("UPDATE  lessons SET lessonNumber=? WHERE lessonNumber = ? AND categoryID = ?");
            $stmtUpdateLessonNumber->bind_param("sss", $lessonNumberUpdate, $updateNumber, $categoryID);
            if ($lessonResult->num_rows > 0) {
                $lessonNumberUpdate = $currentID - 1;
                $updateNumber = $currentID;
                $stmtUpdateLessonNumber->execute();
                updateNumberSub($currentID + 1, $categoryID);
            } else {
                $lessonNumberUpdate = $currentID - 1;
                $updateNumber = $currentID;
                $stmtUpdateLessonNumber->execute();
            }
            $stmtUpdateLessonNumber->close();
        }

        function updateNumberAdd($currentID, $categoryID)
        {
            global $conn;
            $lessonSQL = "SELECT lessons.id as id, lessons.lessonNumber as ln FROM lessons WHERE lessons.lessonNumber = '" . ($currentID + 1) . "' AND  lessons.categoryID = '" . $categoryID . "'";
            $lessonResult = $conn->query($lessonSQL);
            $lessonNumberUpdate = "";
            $updateNumber = "";
            $stmtUpdateLessonNumber = $conn->prepare("UPDATE  lessons SET lessonNumber=? WHERE lessonNumber = ?  AND categoryID = ?");
            $stmtUpdateLessonNumber->bind_param("sss", $lessonNumberUpdate, $updateNumber, $categoryID);
            if ($lessonResult->num_rows > 0) {
                updateNumberAdd($currentID + 1, $categoryID);
                $lessonNumberUpdate = $currentID + 1;
                $updateNumber = $currentID;
                $stmtUpdateLessonNumber->execute();
            } else {
                $lessonNumberUpdate = $currentID + 1;
                $updateNumber = $currentID;
                $stmtUpdateLessonNumber->execute();
            }
            $stmtUpdateLessonNumber->close();
        }

        function removeUnusedCategories()
        {
            global $conn;
            $stmtRemoveCategory = $conn->prepare("DELETE FROM lessoncategories WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE lessons.categoryID = lessoncategories.ID )");
            $stmtRemoveCategory->execute();
            $stmtRemoveCategory->close();
        }
        function getCategoryID()
        {
            global $conn;
            global $_POST;
            $categoryName = "";

            $stmtInsertCategory = $conn->prepare("INSERT INTO lessoncategories (name) VALUES (?)");
            $stmtInsertCategory->bind_param("s", $categoryName);
            $categorySQL = "SELECT * FROM lessoncategories WHERE name = '" . $_POST["categoryName"] . "'";
            $categoryResult = $conn->query($categorySQL);
            $categoryName = $_POST["categoryName"];
            if ($categoryResult->num_rows > 0) {
                $row = $categoryResult->fetch_assoc();
                $stmtInsertCategory->close();
                return $row["ID"];
            } else {
                $stmtInsertCategory->execute();
                $categorySQL = "SELECT * FROM lessoncategories WHERE name = '" . $_POST["categoryName"] . "'";
                $categoryResult = $conn->query($categorySQL);
                if ($categoryResult->num_rows > 0) {
                    $row = $categoryResult->fetch_assoc();
                    $stmtInsertCategory->close();
                    return $row["ID"];
                }
            }
            $stmtInsertCategory->close();
        }
        ?>

