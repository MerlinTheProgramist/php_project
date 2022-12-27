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

    <?php
        $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
        $result = mysqli_query($db, "SELECT prof.username as author, i.image_path as prof,
            p.content as cont, p.creation_date as cre_time, p.id as id
            FROM Post p 
            LEFT JOIN Profile prof ON prof.id=p.author_id 
            LEFT JOIN Image i ON i.id=p.image_id ORDER BY p.creation_date;");    
    ?>

    <body>
        <?php include "./sidenav.html";?>
        
        <div id="main">
            <h1>Główna</h1>
            <?php while($post=mysqli_fetch_array($result)): 
                    $url = urlGET('/post.php',array('id'=>$post['id']));
                    echo $_SESSION["user_id"];
                    echo $_SESSION["login"];     
                    echo $_SESSION["profile_pic"];
            ?>
                <div class="post">
                    <div class="meta">
                        <a class="author">
                            <img class="prof_pic" src="<?=('./profile_pics/'.$post['prof'])?>">
                            <?=$post["author"]?>
                            ·
                            <small><?=$post["cre_time"]?></small>
                        </a>
                    </div>
                    <p>
                        <?=$post["cont"]?>
                    </p>
                    <hr>
                    <table class="actions">
                        <td>
                            <a href="<?=$url?>">
                            Comm
                            </a>
                        </td>
                        <td>
                            <?php
                                $is_hearting = mysqli_fetch_array(mysqli_query($db, "SELECT count(*)  FROM Heart h WHERE {$_SESSION['user_id']}=h.profile_id AND h.post_id={$post['id']};"))[0];

                                if (isset($_POST['Heart']) && $_POST['Heart']==$post['id']) {
                                    $h_id = $_POST['Heart'];
                                    unset($_POST['Heart']);

                                    
                                    if($is_hearting) // unheart
                                        mysqli_query($db, "DELETE FROM Heart h WHERE h.profile_id={$_SESSION['user_id']} AND h.post_id={$h_id};");
                                    else // heart
                                        mysqli_query($db, "INSERT INTO Heart (profile_id, post_id) VALUES ({$_SESSION['user_id']},{$h_id});");
                                    $is_hearting = !$is_hearting;
                                }
                                
                                $hearths = mysqli_fetch_array(mysqli_query($db, "SELECT count(1) as num FROM Heart h WHERE h.post_id={$post['id']};"));
                                
                            ?>

                            <form method='POST'>
                                <button onclick="" type="submit" name="Heart" value="<?=$post['id']?>">
                                    <?= $hearths['num']." ".(($is_hearting)?"+":"")?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <button onclick="copyText('<?=$url?>')">
                            Share <!-- copy link to clickboard -->
                            </button>
                        </td>
                    </table>
                    
                </div>
            <?php endwhile;?>
        </div>
    </body>
    <script>
        function copyText(text)
        {
            navigator.clipboard.writeText(window.location.hostname+":"+window.location.port+text);
        }
    </script>
</html>