<?php
session_start();
$status = "";
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST["create"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];

    if (strlen($title) > 100) {
        $status = "Title must not be longer than 100 characters!<br/>";
    } else {
        if (strlen($content) > 500) {
            $status = "Content must not be longer than 500 characters!<br/>";
        } else {
            require_once("connect.php");
            $query = $conn->query('SELECT * FROM users WHERE username=?', [$_SESSION["username"]]);
            $uid = $query->fetch_assoc()["uid"];
            $conn->query('INSERT INTO threads (uid, title, content, date) VALUES (?,?,?,now())', [$uid, $title, $content]);
            $_SESSION["thread_created"] = true;
            header("Location: index.php");
            exit;
        }
    }
}
?>

<html>
    <head><title>Create thread</title><h1>Create thread</h1></head>
    <body>
        <form method="POST">
            <?php echo $status;?>
            Title: <input name="title" type="text"/> <br/>
            Content: <input name="content" type="text"/> <br/>
            <input type="submit" value="Create thread" name="create"/>
        </form>
    </body>
</html>