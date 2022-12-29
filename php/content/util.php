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

function userTemplate($db, int $id, string $image_path, string $username, string $desc, bool $following)
{
    ?>
    <div class="profile">
        <img class="avatar" src="<?=('./profile_pics/'.$image_path)?>">
        <div class="meta">
            
            <b><?=$username?></b>
            <button type="submit" class="follow"
            value=<?=($following)?"1":"0"?>>
            </button>
        </div>
        <p><?=$desc?></p>
        <hr>
    </div> 
    <?php
}

function postTemplate($db, int $id, ?string $image_path, string $author,string $creation_time, string $text){
    ?>
    <div class="post">
        <div class="meta">
            <a class="author">
                <img class="avatar" src="<?=('./profile_pics/'.$image_path)?>">
                <?=$author?>
                ·
                <small><?=$creation_time?></small>
            </a> 
            
        </div>
        <p>
            <?=$text?>
        </p>
        <hr>
        <?php
        $url = urlGET('/post.php',array('id'=>$id));
        $is_hearting = mysqli_fetch_array(mysqli_query($db, "SELECT count(*) FROM Heart h WHERE {$_SESSION['user_id']}=h.profile_id AND h.post_id={$id};"))[0];
        if (isset($_POST['Heart']) && $_POST['Heart']==$id) {
            $h_id = $_POST['Heart'];
        
            if($is_hearting) // unheart
                mysqli_query($db, "DELETE FROM Heart h WHERE h.profile_id={$_SESSION['user_id']} AND h.post_id={$h_id};");
            else // heart
                mysqli_query($db, "INSERT INTO Heart (profile_id, post_id) VALUES ({$_SESSION['user_id']},{$h_id});");
            $is_hearting = !$is_hearting;

        }
        $hearths = mysqli_fetch_array(mysqli_query($db, "SELECT count(1) as num FROM Heart h WHERE h.post_id={$id};"));            
        ?>
        <table class="actions">
            <td>
                <a href="<?=$url?>">
                Comm
                </a>
            </td>
            <td>
                <h3><?=$hearths['num']?></h3>
                <form method='POST'>
                    <button onclick="" type="submit" name="Heart" value="<?=$post['id']?>" class="heart" <?=(($is_hearting)?"fill":"")?>></button>
                </form>
            </td>
            <td>
                <button onclick="copyText('<?=$url?>')">
                Share <!-- copy link to clickboard -->
                </button>
            </td>
        </table>
    </div>
    <?php
}

?>