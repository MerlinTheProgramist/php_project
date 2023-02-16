<?php
session_start();

if (!isset($_SESSION["user_id"]))
    header("Location: login.php");

require("util.php");

$id = $_SESSION['user_id'];

$db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
$profile_results = mysqli_query($db, "SELECT
                                      p.username, 
                                      p.profile_desc, 
                                      u.email,
                                      i.image_path as prof_pic,
                                      i.id as prof_id,
                                      f.followed_id as following
                                      FROM Profile p 
                                      INNER JOIN Image i ON i.id=p.profile_picture_id
                                      INNER JOIN User u ON u.profile_id=p.id
                                      LEFT JOIN Follow f ON f.follower_id={$id} AND f.followed_id=p.id
                                      WHERE p.id = {$_GET['id']};");   

$row = mysqli_fetch_array($profile_results);

if (isset($_POST['sub'])) {

    // if avatar is to be changed
    $image_id = uploadImage_getId($db);
    if($image_id==-1) $image_id = $row['prof_id'];
    

    $out = mysqli_query($db, "UPDATE Profile 
                        INNER JOIN User ON User.profile_id = Profile.id
                        SET 
                            User.username='{$_POST['login']}',
                            Profile.profile_picture_id={$image_id},
                            Profile.username='{$_POST['login']}',
                            User.email='{$_POST['email']}',
                            Profile.profile_desc='{$_POST['desc']}'
                        WHERE User.id={$id};
                        ");
    if($out)
    {
        $_SESSION['login'] = $_POST['login'];
        urlGET('Profile.php',array('id'=>$id));
    }
    else
    {
        
    }
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

            <img id='big_avatar' id="icon" src="<?=AVATAR_DIR.$row['prof_pic']?>"/>

            <div id="change">
                <form id="edit_profile" method="POST" action="myProfile.php">
                    Zmień avatar: <input id="file" onclick="showImage()" type="file" name="upload_image" accept=".jpg,.png,.jpeg,.gif,.webp"></br>
                    Zmień nazwę: </br><input placeholder="login" type="text" name="login" value="<?=$row['username']?>"></input></br>
                    Zmień e-mail: </br><input placeholder="e-mail" type="email" name="email" value="<?=$row['email']?>"></input></br>
                    Zmień opis: </br><textarea placeholder="opisz siebie" value="<?=$row['profile_desc']?>" name="desc"></textarea></br>
                    <button type="submit" name="sub">Zapisz</button>
                </form>
            </div>
            
        </div>
    </body>
</html>