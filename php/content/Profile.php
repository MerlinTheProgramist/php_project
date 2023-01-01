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
        
        
        
        <script>
            if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
            }
        </script>
        </div>
    </body>
</html>