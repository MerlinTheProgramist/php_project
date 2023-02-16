<?php

session_start();

if (!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    die();
}

require("util.php");

if(!isset($_GET['page']))
    GET_n_head('home.php',array('page'=>'0'));
$page_num = intval($_GET['page']);


?>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <![endif]-->



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
        $post_tot_count = mysqli_fetch_assoc(mysqli_query($db, "SELECT count(1) as c from Post p"))['c'];

        $start = $page_num*MAX_QUERRY_POSTS;
        $end = ($page_num+1)*MAX_QUERRY_POSTS;
        
        $result = mysqli_query($db, "SELECT prof.username as author, 
                                     prof_pic.image_path as prof_pic,
                                     p.content as cont, 
                                     p.creation_date as cre_time, 
                                     p.id as id, 
                                     prof.id as prof_id,
                                     image.image_path as image_path
                                     FROM Post p 
                                     INNER JOIN Profile prof ON prof.id=p.author_id 
                                     INNER JOIN Image prof_pic ON prof_pic.id = prof.profile_picture_id 
                                     LEFT JOIN Image image ON image.id=p.image_id
                                     ORDER BY p.creation_date DESC
                                     LIMIT {$start}, {$end} 
        ;");    
    ?>

    <body>
        <?php include "./sidenav.php";?>
        
        <div id="main">
            <h1>Główna</h1>
            <?php while($post=mysqli_fetch_array($result)){
                $url = urlGET('/post.php',array('id'=>$post['id']));
                postTemplate($db, $post['id'],$post['prof_id'], $post['image_path'], $post['prof_pic'], $post['author'], $post['cre_time'],$post['cont']);    
            }
            ?>
            <div>
                <?php if($start>0):?>
                    <a href="<?=urlGET('home.php',array('page'=>$page_num-1))?>">poprzednia</a>
                <?php endif; if($end<$post_tot_count):?>
                    <a href="<?=urlGET('home.php',array('page'=>$page_num+1))?>">następna</a>
                <?php endif;?>
            </div>
        </div>
    </body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>