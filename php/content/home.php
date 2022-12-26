<?php
session_start();

if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
    header("index.html");
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
        $result = mysqli_query($db, "SELECT prof.username as author, i.image_path as prof, p.content as cont, p.creation_date as cre_time FROM Posts p JOIN Profile prof ON prof.id=p.author_id JOIN Image i ON i.id=p.image_id ORDER BY p.creation_date;");    
    ?>

    <body>
        <?php include "./sidenav.html" ?>
        
        <div id="main">
            <h1>Główna</h1>
            <?php while($post=mysqli_fetch_array($result)): ?>
                <div class="post">

                    <a class="author">
                        <img class="prof_pic" src='<?=$data["prof"]?>'></img>
                        <?=$data["author"]?>
                    </a>
                    <small><?=$data["cre_time"]?></small>
                    <p>
                        <?=$data["cont"]?>
                    </p>
                </div>
            <?endwhile;?>
        </div>
    </body>
</html>