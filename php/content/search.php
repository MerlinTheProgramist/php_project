<?php
session_start();

if (isset($_SESSION["user_id"]))
    header("login.php");

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
    <body>
        <?php include "./sidenav.html";?>
        <div id="main">
            <?php 
            $results = $_GET['results']; 
            $text = $_GET['text'];
            ?>
            <form method="GET">
                <div id="search-bar">
                    <input type="text" name="text"
                    value="<?=$_GET['text']?>"
                    ></input>
                    <button type="submit" name="ser">Search</button>
                    <fieldset>
                        <label> Wszystko
                        <input type="radio" name="results"
                        <?=(isset($results) && $results=="All")?"checked":""?>
                        value="All"></input></label>


                        <label> Najnowsze
                        <input type="radio" name="results"
                        <?=(isset($results) && $results=="live")?"checked":""?>
                        value="live"></input></label>

                        <label> Użytkownicy
                        <input type="radio" name="results"
                        <?=(isset($results) && $results=="Users")?"checked":""?>
                        value="Users"></input></label>

                        <label> Posty
                        <input type="radio" name="results"
                        <?=(isset($results) && $results=="Posts")?"checked":""?>
                        value="Posts"></input></label>

                    </fieldset>
                </div>
            </form>
            <?php
            if (!isset($_GET['ser']))
                die();
            $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);
            if ($results == "Users" or $results == "All") {
                $profile_results = mysqli_query($db, "SELECT p.id, p.username, p.profile_desc, i.image_path,
                                                  (SELECT count(1) FROM Follow f WHERE f.follower_id={$_SESSION['user_id']}) as following
                                                  FROM Profile p 
                                                  JOIN Image i ON i.id=p.profile_picture_id
                                                  WHERE p.username LIKE '%{$text}%' 
                                                  OR p.profile_desc LIKE '%{$text}%';");
                
                while ($user = mysqli_fetch_array($profile_results)) {
                    userTemplate($db, $user["id"], $user['image_path'], $user["username"], $user["profile_desc"], $user["following"]);
                }
            }

            if ($results == "Posts" or $results == "All") {
                $post_results = mysqli_query($db, "SELECT p.id, p.content, p.creation_date,  prof.username, p.creation_date, i.image_path
                                                FROM Post p 
                                                JOIN Profile prof ON p.author_id=prof.id
                                                JOIN Image i ON i.id=prof.profile_picture_id
                                                WHERE p.content LIKE '%{$text}%';");


            while ($post = mysqli_fetch_array($post_results)) {
                postTemplate($db, $post['id'], $post['image_path'], $post["username"], $post["creation_date"], $post["content"]);
            }
            }
            
            ?>

            
        </div>
    </body>
</html>