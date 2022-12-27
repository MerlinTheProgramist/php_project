<?php
// function urlGET($url, $data)
// {
//     $url = "{$url}?";
//     $args = array(); 
//     foreach($data as $key=>$val)
//         array_push($args, $key.'='.$val);
//     return $url . join('&', $args);
// }

function urlGET($url,$params=array()) {
    if(!$params) return $url;
    $link = $url;
    if(strpos($link,'?') === false) $link .= '?'; //If there is no '?' add one at the end
    elseif(!preg_match('/(\?|\&(amp;)?)$/',$link)) $link .= '&amp;'; //If there is no '&' at the END, add one.
    
    $params_arr = array();
    foreach($params as $key=>$value) {
        if(gettype($value) == 'array') { //Handle array data properly
            foreach($value as $val) {
                $params_arr[] = $key . '[]=' . urlencode($val);
            }
        } else {
            $params_arr[] = $key . '=' . urlencode($value);
        }
    }
    $link .= implode('&amp;',$params_arr);
    
    return $link;
} 
// function httpGET($url, $data)
// {
//     flush();
//     ob_flush();
//     header("Location: " . urlGET($url,$data));
//     die();
// }
function set_n_head(string $url, array $data)
{
    foreach($data as $key=>$val)
        $_SESSION[$key] = $val;
    
    header("Location: ".$url);
    die();
}

function CreateUser($db,string $email, string $name, string $pass)
{
    mysqli_query($db, "INSERT INTO Profile(username) VALUES ('{$name}');");
    mysqli_query($db,"INSERT INTO User(email,username,password,profile_id) VALUES
                      ('{$email}','{$name}','{$pass}',LAST_INSERT_ID());");
}

?>