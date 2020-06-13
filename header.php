<?php
require_once "config/config.php";
require_once "config/pdo.php";
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo BASE_URL."css/style.css"?>">
        <div>
            <?php
            if ( !isset($_SESSION['user_id']) ) {
                echo '<a href="'.BASE_URL.'account/register" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Create an Account</a>';
                echo '<a href="'.BASE_URL.'account/login" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Log in</a>';
            } else {
                $stmt = $pdo->prepare("SELECT name FROM users
                WHERE user_id = :user_id");
                $stmt->execute(array(":user_id" => $_SESSION['user_id']));
                $name = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<a href="'.BASE_URL.'account/logout" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Log out</a>';
                echo '<a href="'.BASE_URL.'entity/add" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Add Item</a>';
                echo('<div style="float: right; margin: 1em; padding-top: 1em">Logged in as <b>'.htmlentities($name['name']).'</b></div>');
            }
            ?>
        </div>
        <a href="./">
            <img src="<?php echo BASE_URL;?>images/logo.png" width="170" height="170">
        </a>
    </head>
</html>