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
    $out = mysqli_query($db, "SELECT 1 FROM User u WHERE u.password='{$pass}' AND u.login='{$login}';");
    if($out===false)
        set_n_head("./login.php", array('mess'=>"login or password was incorrect"));
    
} 
else if ($_POST["sub"] == "Next") {
    echo "Singing in";
    $email = $_POST["email"];
    $pass2 = sha1($_POST["pass1"]);

    if ($pass != $pass2)
        

    $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);
    $out = mysqli_query($db, "INSERT INTO User(email,username,password) VALUES ('{$email}', '{$login}','{$pass}');");
    
    if($out===false)
        set_n_head("./register.php", array('mess'=>"typed passwords didnt match"));

}
else{set_n_head("./login.php",array('mess'=>"just an ERROR"));}

header("Location: ./index.php");

?>