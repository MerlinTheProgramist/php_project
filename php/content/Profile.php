<?php
session_start();

if (!isset($_SESSION["user_id"]))
header("Location: login.php");

require("util.php");


if (!isset($_GET['id']))
    header("Location: home.php");

if ($_GET['id'] == $_SESSION['user_id']) {
    header("Location: MyProfile.php");
}

$db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
$profile_results = mysqli_query($db, "SELECT
                                      p.username, 
                                      p.profile_desc, 
                                      i.image_path as prof_pic,
                                      f.followed_id as following
                                      FROM Profile p 
                                      INNER JOIN Image i ON i.id=p.profile_picture_id
                                      LEFT JOIN Follow f ON f.follower_id={$_SESSION['user_id']} AND f.followed_id=p.id
                                      WHERE p.id = {$_GET['id']};");   

$row = mysqli_fetch_array($profile_results);


$result = mysqli_query($db, "SELECT 
                             prof.username as author, 
                             prof_i.image_path as prof_pic,
                             p.content as cont, 
                             p.creation_date as cre_time, 
                             p.id as id, 
                             prof.id as prof_id
                             FROM Post p 
                             INNER JOIN Profile prof ON prof.id=p.author_id 
                             INNER JOIN Image prof_i ON prof_i.id=prof.profile_picture_id 
                             WHERE prof.id = {$_GET['id']}
                             ORDER BY p.creation_date DESC;");  



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
        <style>
            #name{
                font-size: 100px;
                height: 200px;
            }
            #napis{
                width: 400px;
            }
            #opis{
                font-size: 50px;
                height: 80px;
            }
        </style>
    </head>

    <body>
        <div id="main">
        <?php include "./sidenav.html" ?>

        <div>
            <img>
        </div>
            <img class='avatar' id="icon" src="<?=AVATAR_DIR.$row['prof_pic']?>"></img>
            <h1><?=$row['username']?></h1> 
            <div id="opis">
                <?= $row['profile_desc'] ?>
            </div>
            

            <div id="napis">
                <h2>Posts</h2>
            </div>

            <?php while($post=mysqli_fetch_array($result)){
                $url = urlGET('/post.php',array('id'=>$post['id']));
                postTemplate($db, $post['id'], $post['prof_id'], $post['pic'], $post['prof_pic'], $post['author'], $post['cre_time'],$post['cont']);    
            }
        ?>
        </div>
    </body>
</html>