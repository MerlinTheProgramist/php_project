<?php
session_start();

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
                        <?=(!isset($results) || $results=="All")?"checked":""?>
                        value="All"></input></label>


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
                                                  f.followed_id as following
                                                  FROM Profile p 
                                                  JOIN Image i ON i.id=p.profile_picture_id
                                                  LEFT JOIN Follow f ON f.follower_id={$_SESSION['user_id']} AND f.followed_id=p.id
                                                  WHERE p.username LIKE '%{$text}%' 
                                                  OR p.profile_desc LIKE '%{$text}%';");

                if (mysqli_num_rows($profile_results) > 0) {
                    echo "<h3>Użytkownicy</h3>";
                    while ($user = mysqli_fetch_array($profile_results)) {
                        userTemplate($db, $user["id"], $user['image_path'], $user["username"], $user["profile_desc"], isset($user["following"]));
                    }
                }
            }

            if ($results == "Posts" or $results == "All") {
                
                $post_results = mysqli_query($db, "SELECT 
                                                   p.id, 
                                                   p.content, 
                                                   p.creation_date,  
                                                   prof.username, 
                                                   p.creation_date, 
                                                   prof_pic.image_path as prof_pic, 
                                                   image.image_path as pic,
                                                   prof.id as prof_id
                                                   FROM Post p 
                                                   INNER JOIN Profile prof ON p.author_id=prof.id
                                                   INNER JOIN Image prof_pic ON prof_pic.id=prof.profile_picture_id
                                                   LEFT JOIN Image image ON image.id = p.image_id
                                                   WHERE p.content LIKE '%{$text}%' 
                                                   OR prof.username LIKE '%{$text}%'
                                                   ORDER BY p.creation_date DESC;");

                if (mysqli_num_rows($post_results) > 0) {
                    echo "</br><h3>Posty</h3>";
                    while ($post = mysqli_fetch_array($post_results)) {
                        postTemplate($db, $post['id'], $post['prof_id'], $post['pic'], $post['prof_pic'], $post["username"], $post["creation_date"], $post["content"]);
                    }
                }
            }

            
            
            ?>
        </div>
    </body>
</html>