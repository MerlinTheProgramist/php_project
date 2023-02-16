<!-- Icons -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<div id="sidenav">
    <a class="button" href="./home.php">
        <img src="./res/logo.png"/>
    </a>
    <a class="button" href="./search.php">
        <img src="./res/search.png"></img>
    </a>
    <a class="button" href="./notifications.php">
    </a>
    <!--<a class="button" href="./bookmarks.php">
    </a>-->
    <a class="button" href="<?= urlGET('./Profile.php', array('id'=>$_SESSION['user_id'])) ?>">
        <img src="<?= AVATAR_DIR.$_SESSION['profile_pic'] ?>"/>
    </a>
    <a class="button"id="newPost" href="./newPost.php">
        +
    </a>
    <a class="button end" href="./logout.php">
        <img class='icon' src="./res/logout_icon.png"/>
    </a>
</div>