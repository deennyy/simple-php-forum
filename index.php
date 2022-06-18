<?php
session_start();

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();
}
?>

<html>
    <head><title>Forum</title><h1>Forum</h1></head>
    <body>
        <?php
        if (isset($_SESSION["username"])) {
            echo "Hello, " . $_SESSION["username"] . "!<br/>";

            if (isset($_SESSION["thread_created"])) {
                echo "Thread created!";
                unset($_SESSION["thread_created"]);
            }

            echo "<form method='POST'> <input value='Logout' type='submit' name='logout'/> </form>";
            echo "<form method='POST' action='create_thread.php'> <input value='Create thread' type='submit'/> </form>";

            echo "<h1>Threads</h1>";

            require_once("connect.php");

            $query = $conn->query('SELECT * FROM threads');
            while ($result = $query->fetch_assoc()) {
                echo "Title: " . $result['title'] . "<br/>";
                echo "Content: " . $result['content'] . "<br/>";

                $users = $conn->query('SELECT * FROM users WHERE uid=?', [$result['uid']]);

                echo "Posted by: " . $users->fetch_assoc()['username'] . "<br/>";
                echo "Posted on: " . $result['date'] . " UTC";

                echo "<form method='POST' action='replies.php?t=".$result['id']."'> <input value='View replies' type='submit'/> </form>";

                echo "<br/><br/><br/><br/>";
            }
        } else {
            if (isset($_SESSION["registered"])) {
                echo "Registration successful!";
                unset($_SESSION["registered"]);
            }

            echo "<form method='POST' action='login.php'> <input value='Login' type='submit'/> </form>";
            echo "<form method='POST' action='register.php'> <input value='Register' type='submit'/> </form>";
        }
        ?>
    </body>
</html>