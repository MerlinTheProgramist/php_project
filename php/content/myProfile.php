<?php
session_start();

if (!isset($_SESSION["user_id"]))
header("Location: login.php");

require("util.php");


if (!isset($_GET['id'])){
    header("Location: home.php");
}

$id = $_GET['id'];

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
                height: 100px;
            }
            #opis{
                font-size: 50px;
                height: 80px;
            }
        </style>
    </head>

    <body>
        <div id="main">
        <?php include "./sidenav.php" ?>

            <img class='avatar' id="icon" src="<?=AVATAR_DIR.$row['prof_pic']?>"></img>

            <div id="change">
                <form id="edit_profile" method="POST" action="<?=urlGET('Profile.php',array('id'=>$id))?>">
                    Zmień nazwę: </br><input type="text" name="login" value="<?=$row['username']?>"></input>
                    Zmień opis: <textarea value="<?=$row['profile_desc']?>" name="desc"></textarea>
                    <input type="submit" name="sub"></input>
                </form>
            </div>
            <?php
            if (isset($_POST['sub'])) {
                mysqli_query($db, "UPDATE Profile 
                                    INNER JOIN User 
                                    ON User.profile_id = Profile.id
                                    SET 
                                        User.username='{$_POST['login']}',
                                        Profile.username='{$_POST['login']}',
                                        Profile.profile_desc='{$_POST['desc']}'
                                    WHERE User.id={$id};
                                    ");
                $_SESSION['login'] = $_POST['login'];
            }
            ?>
        </div>
    </body>
</html>