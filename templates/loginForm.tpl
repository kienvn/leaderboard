<!DOCTYPE HTML>
<html>
    <head>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <title>PHP 11 Course Administrative Login</title>
    </head>
    <body>
        Log In
        <hr />
        <form action="login.php" method="post">
            <label for="username">Потребителско име:</label>
            <br />
            <input type="text" name="username" />
            <br />
            <label for="password">Парола</label>
            <br />
            <input type="password" name="password" />
            <br />
            <input type="submit" name="loginForm" value="Login" />
        </form>
        <span style="color: red">{$errorText}</span>
    </body>
</html>