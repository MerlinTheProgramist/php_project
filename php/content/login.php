<?php
    if(!isset($_GET["type"]))
        $_GET["t"] = "Log";

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
        <link rel="stylesheet" href="">
    </head>
    <body>
        <div id="main">
            
            <form method="POST" action="verify.php">
                <label>Login: <input type="text" name="login"></input></label></br>
                <label>Passsword: <input type="password" name="pass"></input></label></br>
                <input type="submit" name="sub[]" value="log"></input></br>
            </form>

        </div>
    </body>
</html>