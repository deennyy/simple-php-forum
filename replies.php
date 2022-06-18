<?php
session_start();
$status = "";
if (isset($_POST["reply"])) {
    if (!isset($_SESSION["username"])) {
        header("Location: login.php");
        exit;
    } else {
        if (strlen($_POST['content']) > 500) {
            $status = "Reply content must not be longer than 500 characters!<br/>";
        } else {
            require_once("connect.php");
            $query = $conn->query('SELECT * FROM users WHERE username=?', [$_SESSION["username"]]);
            $conn->query('INSERT INTO replies (uid, tid, reply, date) VALUES (?,?,?,now())', [$query->fetch_assoc()['uid'], $_GET['t'], $_POST['content']]);
        }
    }
}
?>

<html>
    <head><title>Replies</title><h1>Replies</h1></head>
    <body>
        <form method="POST">
            <?php echo $status;?>
            Content: <input name="content" type="text"/>
            <input type="submit" value="Reply" name="reply"/>
        </form>
        <?php
        require_once("connect.php");
        $query = $conn->query('SELECT * FROM replies WHERE tid=?', [$_GET['t']]);
        while ($result = $query->fetch_assoc()) {
            echo "Content: " . $result['reply'] . "<br/>";
            
            $users = $conn->query('SELECT * FROM users WHERE uid=?', [$result['uid']]);

            echo "Replied by: " . $users->fetch_assoc()['username'] . " on " . $result['date'] . " UTC";

            echo "<br/><br/><br/><br/>";
        }
        ?>
    </body>
</html>