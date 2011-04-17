<html>
    <head>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon" />
        <title>PHP 11 Course Administrative Login</title>
    </head>
    <body>
        <form action="login.php" method="post">
            <input type="text" name="username" />
            <input type="password" name="password" />
            <input type="submit" name="loginForm" value="Login" />
        </form>
        <span style="color: red">{$errorText}</span>
    </body>
</html>