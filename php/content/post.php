<?php
session_start();

if (!isset($_SESSION["user_id"]))
header("Location: login.php");

require("util.php");


$id = $_GET['id'];

$db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
$result = mysqli_query($db, 
    "SELECT prof.username as author,
    i.image_path as prof,
    p.content as cont,
    p.creation_date as cre_time 
    FROM Post p
    LEFT JOIN Profile prof ON prof.id=p.author_id
    LEFT JOIN Image i ON prof.profile_picture_id=i.id
    WHERE p.id={$id}
    LIMIT 1 
");
$post = mysqli_fetch_array($result);
if (!$post) {
    header("Location: home.php");
    die();
}
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

    <body>
        <div id="main">
        <?php include "./sidenav.html" ?>
        
        <div class="post">
            <div class="meta">
                <a class="author">
                    <img class="prof_pic" src="<?=('./profile_pics/'.$post['prof'])?>">
                    <?=$post["author"]?>
                    ·
                    <small><?=$post["cre_time"]?></small>
                </a> 
                
            </div>
            <p>
                <?=nl2br($post["cont"])?>
            </p>
        </div>

        

        <div id="commets">

            <!-- New comment -->
            <form method="POST" action="<?=urlGET('./post.php',array('id'=>$_GET['id']))?>">
                <textarea type="text"  name="content"></textarea>
                <button type="submit" name="sub">Comment</button>
            </form>
            <?php
                if(isset($_POST["sub"]))
                {
                    $text = $_POST['content'];
                if (!empty($text))
                    mysqli_query($db, "INSERT INTO Comment (author_id, post_id, content) values({$_SESSION['user_id']},{$id},'{$text}');");
                }
            ?>

            <!-- Comments and deletion -->
            <?php
            if (isset($_POST['delete']))
                mysqli_query($db, "DELETE FROM Comment c WHERE c.id={$_POST['delete']};");

            $result = mysqli_query($db, "SELECT c.content as cont,
                                         c.id as id,
                                         prof.id as author_id,
                                         prof.username as author,
                                         prof_img.image_path as prof_pic,
                                         c.creation_date as cre_time
                                         FROM Comment c
                                         JOIN Profile prof ON prof.id=c.author_id
                                         JOIN Image prof_img ON prof.profile_picture_id=prof_img.id
                                         WHERE c.post_id={$id};");
            while ($comm = mysqli_fetch_array($result)) {
                commentTemplate($comm['author'], $comm['prof_pic'], $comm['author_id'], $comm['cre_time'], $comm['cont'], $comm['id'], $id);
            }
            ?>
        </div>
        <script>
            if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
            }
        </script>
        </div>
    </body>
</html>