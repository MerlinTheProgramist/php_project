<?php
session_start();
ob_start();

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

    <?php
        $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
    ?>

    <body>
        <div id="main">
        <?php include "./sidenav.php" ?>
        
        <form method="POST" id="newPost" enctype="multipart/form-data">
            <textarea type='text' name="cont"></textarea>
            <input id="file" onclick="showImage()" type="file" name="upload_image" accept=".jpg,.png,.jpeg,.gif,.webp">Dodaj obraz</input>
            <button type='submit' name="sub">Zapostuj</button>
            <img id="file_preview">
        </form>
        <?php
        if (isset($_POST["sub"])) {
            $text = $_POST['cont'];
            if (!empty($text) && $text!="") {
                $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);

                $image_id = uploadImage_getId($db);

                $image_id = ($image_id == -1) ? 'null' : $image_id;
                mysqli_query($db, "INSERT INTO Post
                                   (author_id, content, image_id) 
                                   VALUES ({$_SESSION['user_id']},'{$text}',{$image_id});");
                $post_id = mysqli_insert_id($db);
                GET_n_head('./post.php', array('id' => $post_id));
            }
        }

        ?>
        </div>
    </body>
    <script>
        var file = document.getElementById('file').files[0];
        var preview = document.getElementById('file_preview')
        function showImage() {    
            var reader  = new FileReader();
            // it's onload event and you forgot (parameters)
            reader.onload = function(e)  {
                if(result)
                // the result image data
                preview.src = e.target.result;
            }
            // you have to declare the file loading
            reader.readAsDataURL(file);
        }

        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
</html>