<?php
    
    session_start();
    
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Twitter2 Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <div id="main">
            
            <?php if(isset($_SESSION["mess"])):?>
                <h3 id="warning"><?=$_SESSION["mess"]?></h3>
            <?php unset($_SESSION["mess"]); endif;?>
            
            <form method="POST" action="verify.php">
                <label>e-mail: </br><input type="email" name="email"></input></label></br>    
                <label>Login: </br><input type="text" name="login"></input></label></br>
                <label>Password: </br><input type="password" name="pass"></input></label></br>
                <label>Repeat password: </br><input type="password" name="pass1"></input></label></br>
                <input type="submit" name="sub" value="Next"></input></br>
            </form>

            <label>Masz już konto? Zaloguj się 
                <a href="login.php">tutaj</a>
            </label>
        </div>
    </body>
</html>