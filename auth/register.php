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
            <?php require $_SERVER['DOCUMENT_ROOT'] . "/auth/registerValidation.php" ?>
            <form action="register.php" method="post" class="formContainer">
                <h1 class="formTitle">Sign Up</h1>
                <div class="formInputContainer">
                    <input type="text" class="formInput" name="username" id="username" placeholder="Username">
                    <div id="usernameErrorMessage" class="usernameErrorMessage">
                        <?php echo isset($_POST["username"]) ?  validateUsername() : ""; ?>
                    </div>
                    <input type="password" class="formInput" name="password" id="password" placeholder="Password">
                    <input type="password" class="formInput" name="password2" id="password2" placeholder="Password again">
                    <div id="passwordErrorMessage" class="usernameErrorMessage">
                        <?php echo isset($_POST["username"]) ?  validatePassword() : ""; ?>
                    </div>
                </div>
                <div class="formButton">
                    <button type="submit">Register</button>
                </div>
                <div class="formLink">
                    <footer>Already a member? <a href="login.php">Login here</a></footer>
                </div>
            </form>
        </div>
    </div>
    </div>
    <?php registerUser() ?>
    <script src="../static/js/registerValidate.js"></script>
</body>

</html>