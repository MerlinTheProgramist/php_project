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
$profile_results = mysqli_query($db, "SELECT p.username, p.profile_desc, i.image_path,
                                f.followed_id as following
                                FROM Profile p 
                                JOIN Image i ON i.id=p.profile_picture_id
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
        <style type="text/css">
            .nazwa{
                font-size: 100px;
            }
        </style>
    </head>

    <body>
        <div id="main">
        <?php include "./sidenav.html" ?>
        
        <div>
            <img>
        </div>
            <div id="nazwa">
                <?= $row['username'] ?>
            </div> 
        </div>
    </body>
</html>