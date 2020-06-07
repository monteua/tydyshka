<?php
    if ( file_exists("../images/logo.png") && file_exists("../css/style.css") ) {
        $ext = "../";
    } else {
        $ext = "";
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="<?php echo $ext;?>css/style.css">
        <div>
            <?php
            if ( !isset($_SESSION['user_id']) ) {
                echo '<a href="'.$ext.'account/register" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Create an Account</a>';
                echo '<a href="'.$ext.'account/login" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Log in</a>';
            } else {
                $stmt = $pdo->prepare("SELECT name FROM users
                WHERE user_id = :user_id");
                $stmt->execute(array(":user_id" => $_SESSION['user_id']));
                $name = $stmt->fetch(PDO::FETCH_ASSOC);

                echo '<a href="'.$ext.'account/logout" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Log out</a>';
                echo '<a href="'.$ext.'entity/add" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Add Item</a>';
                echo('<div style="float: right; margin: 1em; padding-top: 1em">Logged in as <b>'.htmlentities($name['name']).'</b></div>');
            }
            ?>
        </div>
        <a href="./">
            <img src="<?php echo $ext;?>images/logo.png" width="170" height="170">
        </a>
    </head>
</html>