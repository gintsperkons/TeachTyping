<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/main.css">

    <title>TeachTyping|Register</title>
</head>

<body>
    <div>
        <div>
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/loginValidation.php" ?>
            <div class="titleForm">
                <div>TeachTyping<i class="titleFormCursor">|</i></div>
            </div>
            <form action="login.php" method="post" class="formContainer">
                <h1 class="formTitle">Login</h1>
                <div class="formInputContainer">
                    <input type="text" class="formInput" name="username" id="username" placeholder="Username">
                    <div id="usernameErrorMessage" class="usernameErrorMessage">
                        <?php echo isset($_POST["username"]) ?  validateUsername() : "";  ?>
                    </div>
                    <input type="password" class="formInput" name="password" id="password" placeholder="Password">
                    <div id="passwordErrorMessage" class="usernameErrorMessage">
                        <?php echo isset($_POST["password"]) ?  validatePassword() : ""; ?>
                    </div>
                </div>
                <div class="formButton">
                    <button type="submit">Login</button>
                </div>
                <div class="formLink">
                    <footer>Not a member? <a href="register.php">Register here</a></footer>
            </form>
        </div>
    </div>
    <?php loginUser() ?>
    <script src="../static/js/registerValidate.js"></script>
</body>

</html>