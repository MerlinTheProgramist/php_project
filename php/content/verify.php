<?php
ob_start();
session_start();
require("util.php");


if (!isset($_POST["sub"]))
{
    header("Location: login.php");
    die();
}


$login = $_POST["login"];
$pass = sha1($_POST["pass"]);

if ($_POST["sub"] == "Login") {
    echo "Loging";
    $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app",3306);
    $result = mysqli_query($db, "SELECT u.login, i.image_path FROM User u JOIN Profile p ON u.profile_id=p.id JOIN Image i ON i.id=p.profile_picture_id WHERE u.password='{$pass}' AND (u.login='{$login}' OR u.email='{$login}');");
    $data;
    if(!($data = mysqli_fetch_array($result)))
        set_n_head("./login.php", array('mess'=>"login or password was incorrect"));
    
    $_SESSION["auth"] = true;
    $_SESSION["login"] = $data["login"];
    $_SESSION["profile_pic"] = $data["image_path"];
} 
else if ($_POST["sub"] == "Next") {
    echo "Singing in";
    $email = $_POST["email"];
    $pass2 = sha1($_POST["pass1"]);

    if ($pass != $pass2)
        set_n_head("./register.php", array('mess' => "passwords didn't match"));

    $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);
    // create account
    mysqli_query($db, "INSERT INTO User(email,username,password) VALUES ('{$email}', '{$login}','{$pass}');");
    // get default picture
    $result = mysqli_query($db, "SELECT u.login, i.image_path FROM User u JOIN Profile p ON u.profile_id=p.id JOIN Image i ON i.id=p.profile_picture_id WHERE u.password='{$pass}' AND u.email='{$email}');");

    $data = mysqli_fetch_array($result);
    $_SESSION["auth"] = true;
    $_SESSION["login"] = $data["login"];
    $_SESSION["profile_pic"] = $data["image_path"];
}
else{
    set_n_head("./login.php",array('mess'=>"404"));
}

header("Location: ./home.php");

?>