<?php
session_start();
ob_start();

if (!isset($_SESSION["user_id"]))
header("Location: login.php");

require("util.php");
?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->



<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/style.css">
    </head>

    <?php
        $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
    ?>

    <body>
        <div id="main">
        <?php include "./sidenav.html" ?>
        
        <form method="POST" id="newPost">
            <textarea type='text' name="cont"></textarea>
            <button type='submit' name="sub">Zapostuj</button>
        </form>
        <?php
        if (isset($_POST["sub"])) {
            $text = $_POST['cont'];
            if (!empty($text)) {
                $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);
                $result = mysqli_query($db, "INSERT INTO Post(author_id,content) VALUES ({$_SESSION['user_id']},'{$text}');");
                $post_id = mysqli_insert_id($db);
                GET_n_head('./post.php', array('id' => $post_id));
            }
        }

        ?>
        </div>
    </body>
    <script>
        if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>