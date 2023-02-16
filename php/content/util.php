<script>
    function copyText(text)
    {
        navigator.clipboard.writeText(window.location.hostname+":"+window.location.port+text);
        alert('skopiowano link to schowka');
    }
</script>

<?php
// CONSTANTS
const UPLOAD_DIR = "images/";
const AVATAR_DIR = "profile_pics/";
const MAX_QUERRY_POSTS = 10;



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

function GET_n_head(string $url, array $params){
    header("Location: " . urlGet($url, $params));
    die();
}

function set_n_head(string $url, array $data)
{
    foreach($data as $key=>$val)
        $_SESSION[$key] = $val;
    
    header("Location: ".$url);
    die();
}

function CreateUser($db,string $email, string $name, string $pass): int
{
    mysqli_query($db, "INSERT INTO Profile(username) VALUES ('{$name}');");
    mysqli_query($db,"INSERT INTO User(email,username,password,profile_id) VALUES
                      ('{$email}','{$name}','{$pass}',LAST_INSERT_ID());");
    return mysqli_insert_id($db);
}

function userTemplate($db, int $id, string $image_path, string $username, string $desc, bool $following)
{
    if (isset($_POST['follow']) && $_POST['follow']==$id) {

        if($following) // unheart
            mysqli_query($db, "DELETE FROM Follow f WHERE f.follower_id={$_SESSION['user_id']} AND f.followed_id={$id};");
        else // heart
            mysqli_query($db, "INSERT INTO Follow (follower_id, followed_id) VALUES ({$_SESSION['user_id']},{$id});");
        $following = !$following;
    }

    ?>
    <div class="profile">
        <div class="meta">
            <a href=<?=urlGET('/Profile.php',array('id'=>$id))?>> 
                <img class="avatar" src="<?=('./profile_pics/'.$image_path)?>">
                <h3><?=$username?> </h3>
            </a>
            <form method="POST">
                <button type="submit" class="follow" name="follow"
                    value="<?=$id?>"
                    following=<?=($following)?"1":"0"?>>
                </button>
            </form>
        </div>
        <p><?=nl2br($desc)?></p>
        <hr>
    </div> 
    <?php
}

function postTemplate($db, int $id, int $profile_id, ?string $image_path, string $prof_image_path, string $author,string $creation_time, string $text){
    ?>
    <div class="post">
        <div class="meta">
            <div class="author" >
                <a href="<?=urlGET('/Profile.php',array('id'=>$profile_id))?>">
                    <img class="avatar" src="<?=(AVATAR_DIR.$prof_image_path)?>">
                    <?=$author?>
                </a>
                ·
                <small><?=$creation_time?></small>
            </div> 
        </div>
        </br>
        <div class="content">
            <?=nl2br($text)?>
        </div>
        <?php if(!empty($image_path)): ?>
            
            <img src="<?=UPLOAD_DIR.$image_path?>"></img>
        <?php endif; ?>
        <hr>
        <?php
        $url = urlGET('/post.php',array('id'=>$id));
        $is_hearting = mysqli_fetch_array(mysqli_query($db, "SELECT count(*) FROM Heart h WHERE {$_SESSION['user_id']}=h.profile_id AND h.post_id={$id};"))[0];
        if (isset($_POST['Heart']) && $_POST['Heart']==$id) {
        
            if($is_hearting) // unheart
                mysqli_query($db, "DELETE FROM Heart h WHERE h.profile_id={$_SESSION['user_id']} AND h.post_id={$id};");
            else // heart
                mysqli_query($db, "INSERT INTO Heart (profile_id, post_id) VALUES ({$_SESSION['user_id']},{$id});");
            $is_hearting = !$is_hearting;

        }
        $hearths = mysqli_fetch_array(mysqli_query($db, "SELECT count(1) as num FROM Heart h WHERE h.post_id={$id};"));            
        ?>

        <table class="actions">
            <td>
                <a href="<?=$url?>">
                <img class="icon" src="./res/comment_icon.png"/>
                </a>
            </td>
            <td>
                <h3><?=$hearths['num']?></h3>
                <form method='POST'>
                    <button onclick="" type="submit" name="Heart" value="<?=$id?>" class="heart" <?=(($is_hearting)?"fill":"")?>></button>
                </form>
            </td>
            <td>
                <button onclick="copyText('<?=$url?>');"><!-- copy link to clickboard -->
                    <img class="icon" src="./res/share_icon.png"/>  
                </button>
            </td>
        </table>
    </div>
    <?php
}

function commentTemplate(string $author, string $prof_pic, int $author_id, string $date, string $content, int $comm_id, int $post_id)
{
    ?>
    <div class="comment">
        <div class="meta">
            <div class="author">
                <a href="<?=urlGET('/Profile.php',array('id'=>$author_id))?>">
                    <img class="avatar" src="<?=(AVATAR_DIR.$prof_pic)?>">
                    <?=$author?>
                </a>
                ·
                <small><?=$date?></small>
            </div> 
        </div>
        <p style="float:left;"><?=nl2br($content)?></p>
        <?php
        if ($author_id == $_SESSION['user_id']):
        ?>
        <form method="POST" action="<?=urlGET('./post.php',array('id'=>$post_id))?>">
            <button style="float:right;" type="submit" name="delete" value="<?=$comm_id?>">Delete</button>
        </form>
        <?php endif;?>
    </div>
    <?php
}

function validatePassword(string $password)
{
$uppercase = preg_match('#[A-Z]+#', $password) !==false;
$lowercase = preg_match('#[a-z]+#', $password) !==false;
$number    = preg_match('#[0-9]+#', $password) !==false;
$specialChars = preg_match('#[^\w]+#', $password) !==false;

return $uppercase && $lowercase && $number && $specialChars && strlen($password) >= 8;
}

function uploadImage_getId($db) : int
{
    if ($_FILES['upload_image']['error'] <= UPLOAD_ERR_OK && getimagesize($_FILES["upload_image"]["tmp_name"]) !== false) {

        $path_parts = pathinfo($_FILES["upload_image"]["name"]);
        $extension = $path_parts['extension'];

        $source = $_FILES["upload_image"]["tmp_name"];
        $name = uniqid() . "-" . time() .".". $extension;
        $destination = UPLOAD_DIR . $name;

        move_uploaded_file($source, $destination);
        mysqli_query($db, "INSERT INTO Image (image_path) VALUES ('{$name}');");
        return mysqli_insert_id($db);
    }
    return -1;
}

?>