<?php
function urlGET($url, $data)
{
    $url = "{$url}?";
    $args = array(); 
    foreach($data as $key=>$val)
        array_push($args, $key.'='.$val);
    return $url . join('&', $args);
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

?>