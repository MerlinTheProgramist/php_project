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
    $result = mysqli_query($db, 
       "SELECT u.username, i.image_path, p.id 
        FROM User u 
        JOIN Profile p ON u.profile_id=p.id 
        JOIN Image i ON i.id=p.profile_picture_id 
        WHERE u.password='{$pass}' 
        AND (u.username='{$login}' OR u.email='{$login}');
    ");
    $data = mysqli_fetch_array($result);
    if(!$data)
        set_n_head("./login.php", array('mess'=>"Nieprawidłowy login lub email lub hasło."));
    
    $_SESSION["auth"] = true;
    $_SESSION["user_id"] = $data["id"];
    $_SESSION["login"] = $data["login"];
    $_SESSION["profile_pic"] = $data["image_path"];

    header("Location: ./home.php");
} 
else if ($_POST["sub"] == "Next") {
    echo "Singing in";
    $email = $_POST["email"];
    $pass2 = sha1($_POST["pass1"]);

    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        set_n_head("./register.php", array('mess' => "e-mail jest niepoprawny."));

    if(!validatePassword($pass))
        set_n_head("./register.php", array('mess' => 
            "Haslo jest zbyt słabe, wymagane jest aby posiadało przynajmniej 8 znaków w tym małe i duże litery, cyfry oraz znaki specjalne."));

    

    if ($pass != $pass2)
        set_n_head("./register.php", array('mess' => "Hasła nie były takie same"));

    $db = mysqli_connect("db", "mysql_user", "mysql_pass", "app", 3306);
    // create account
    
    // check if user already created
    $email_present = mysqli_query($db,"SELECT count(1) FROM User u WHERE u.email='{$email}'");
    if (mysqli_fetch_array($email_present)[0]>0) // user with this email already exists
        set_n_head("./register.php", array('mess'=>"Konto z tym e-mailem już istnieje"));
    $username_present = mysqli_query($db,"SELECT count(1) FROM User u WHERE u.username='{$login}'");
    if (mysqli_fetch_array($username_present)[0]>0) // user with this name already exists
        set_n_head("./register.php", array('mess'=>"Konto z tym loginem już istnieje"));
    
    
    $user_id = CreateUser($db, $email, $login, $pass);
    
    // get default picture
    $result = mysqli_query($db, "SELECT p.username as login, i.image_path FROM Profile p
                                 LEFT JOIN Image i ON i.id=p.profile_picture_id 
                                 WHERE p.id={$user_id}");
    if (!$result)
        set_n_head("./register.php", array('mess' => $result));
    $data = mysqli_fetch_array($result);
    
    $_SESSION["auth"] = true;
    $_SESSION["user_id"] = $user_id;
    $_SESSION["login"] = $data["login"];
    $_SESSION["profile_pic"] = $data["image_path"];

    header("Location: ./home.php");
}
else{
    set_n_head("./login.php",array('mess'=>"404"));
}



?>