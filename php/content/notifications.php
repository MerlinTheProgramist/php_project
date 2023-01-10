<?php
session_start();

if (!isset($_SESSION["user_id"]))
header("Location: login.php");

require("util.php");

$db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
$results = mysqli_query($db, 
    "SELECT 
    p.id, 
    p.content, 
    p.creation_date,
    i.image_path as image,
    prof.username,
    prof_img.image_path as prof_image,
    prof.id as prof_id
    FROM Post p 
    INNER JOIN Profile prof ON prof.id=p.author_id
    INNER JOIN Image prof_img ON prof.profile_picture_id=prof_img.id
    INNER JOIN Follow f ON f.followed_id = prof.id
    LEFT JOIN Image i ON p.image_id=i.id
    WHERE f.follower_id={$_SESSION['user_id']}
    ORDER BY p.creation_date DESC;
    ;");
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
        <?php include "./sidenav.php";?>
        <h1>Powiadomienia</h1>
        <?php
        while ($post = mysqli_fetch_array($results)) {
            postTemplate($db, $post['id'], $post['prof_id'], $post['image'], $post['prof_image'], $post["username"], $post["creation_date"], $post["content"]);
        }
        ?>
        </div>
    </body>
</html>