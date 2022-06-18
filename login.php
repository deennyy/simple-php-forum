<?php
session_start();
$status = "";
if (isset($_POST["login"])) {
    $username = $_POST["username"];
    if (strlen($username) == 0) {
        $status = "Username must not be empty!";
    } else {
        $password = $_POST["password"];
        if (strlen($password) == 0) {
            $status = "Password must not be empty!";
        } else {
            require_once("connect.php");
            $query = $conn->query('SELECT * FROM users WHERE username=?', [$username]);
            if (mysqli_num_rows($query) == 0) {
                $status = "Incorrect username/password!";
            } else {
                if (password_verify($password, $query->fetch_assoc()['password'])) {
                    $_SESSION["username"] = $username;
                    header("Location: index.php");
                    exit;
                } else {
                    $status = "Incorrect username/password!";
                }
            }
        }
    }
}
?>

<html>
    <head><title>Login</title><h1>Login</h1></head>
    <body>
        <form method="POST">
            <?php echo $status;?> <br/>
            Username: <input name="username" type="text"/> <br/>
            Password: <input name="password" type="password"/> <br/>
            <input type="submit" value="Login" name="login"/>
        </form>
        <a href="register.php">Don't have an account?</a>
    </body>
</html>