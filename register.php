<?php
session_start();
$status = "";
if (isset($_POST["register"])) {
    $username = $_POST["username"];
    if (strlen($username) == 0) {
        $status = "Username must not be empty!";
    } else {
        $password = $_POST["password"];
        if (strlen($password) == 0) {
            $status = "Password must not be empty!";
        } else {
            $c_password = $_POST["c_password"];
            if (strcmp($password, $c_password) != 0) {
                $status = "Passwords do not match!";
            } else {
                require_once("connect.php");
                $query = $conn->query('SELECT * FROM users WHERE username=?', [$username]);
                if (mysqli_num_rows($query) == 0) {
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $conn->query('INSERT INTO users (username, password) VALUES (?,?)', [$username, $password_hash]);

                    $query = $conn->query('SELECT * FROM users WHERE username=?', [$username]);
                    if (mysqli_num_rows($query) == 1) {
                        $_SESSION["registered"] = true;
                        header("Location: index.php");
                        exit;
                    } else {
                        $status = "Registration failed!";
                    }
                } else {
                    $status = "Username is already taken!";
                }
            }
        }
    }
}
?>

<html>
    <head><title>Register</title><h1>Register</h1></head>
    <body>
        <form method="POST">
            <?php echo $status;?> <br/>
            Username: <input name="username" type="text"/> <br/>
            Password: <input name="password" type="password"/> <br/>
            Confirm password: <input name="c_password" type="password"/> <br/>
            <input type="submit" value="Register" name="register"/>
        </form>
        <a href="login.php">Already registered?</a>
    </body>
</html>