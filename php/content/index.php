<!doctype html>
<html lng="en">
<head>
    <meta charset="utf-8">
    <title>Docker Test</title>
    <meta name="description" content="Docker Test">
    <meta name="author" content="Merlin">
</head>
<!-- STRONA GŁÓWNA, tak ta której miało nie być-->
<body>
    <h1>Docker Test</h1>
    <div class=".db-table">
        <table>
            <tr>
                <th>Id</th>
                <th>Message</th>
            </tr>
            <?php
                $user = 'mysql_user';
                $pass = 'mysql_pass';

                try{
                    $dbh = new PDO('mysql:host=db;port=3306;dbname=app', $user, $pass);
                    foreach ($dbh->query('SELECT * from post') as $post):?>        
                    <div class="post">
                        <h2>By: <?= $post["author"]  ?></h2>
                        <quote>
                            <?= $post["content"] ?>  
                        </quote>
                    </div>
                    <?php
                    endforeach;
                    $dbh = null;
                } catch (PDOException $e) {
                    print "Error!: " . $e->getMessage() . "<br/>";
                    die();
                }
            ?>
        </table>
    </div>
</body>

</html>
