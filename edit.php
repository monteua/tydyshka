<?php

session_start();

require_once "pdo.php";

if ( ! isset($_SESSION['user_id']) ) {
    die('Not logged in');
} elseif ( isset($_GET['profile_id']) && is_numeric($_GET['profile_id'])) {
    $stmt = $pdo -> prepare('SELECT profile_id, user_id, user_id, first_name, last_name, email, headline, summary 
                    FROM Profile 
                    WHERE profile_id = :profile_id AND user_id = :user_id');
    $stmt -> execute(array('profile_id' => htmlentities($_GET['profile_id']), 'user_id' => $_SESSION['user_id']));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $profile == null ) {
        $_SESSION['error'] = "Selected PROFILE does not exist";
        header("Location: index.php");
        return;
    } elseif ( $_SESSION['user_id'] !== $profile['user_id'] ) {
        $_SESSION['error'] = "Current user does not own this profile. Unable to edit";
        header("Location: index.php");
        return;
    } else {
        if ( isset($_POST['first_name']) && isset($_POST['last_name'])
            && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {
            if (strlen($_POST['first_name']) > 0 && strlen($_POST['last_name']) > 0
                && strlen($_POST['email']) > 0 && strlen($_POST['headline']) > 0 && strlen($_POST['summary']) > 0) {
                if (strpos($_POST['email'], '@') == !true) {
                    $_SESSION['error'] = "Email must have an at-sign (@)";
                    header("Location: edit.php?profile_id=".htmlentities($_GET['profile_id']));
                    return;
                } else {
                    $update = $pdo->prepare('UPDATE Profile SET
                        first_name = :first_name,
                        last_name = :last_name,
                        email = :email,
                        headline = :headline,
                        summary = :summary
                        WHERE profile_id = :profile_id AND user_id = :user_id');

                    $update->execute(array(
                            ':first_name' => $_POST['first_name'],
                            ':last_name' => $_POST['last_name'],
                            ':email' => $_POST['email'],
                            ':headline' => $_POST['headline'],
                            ':summary' => $_POST['summary'],
                            ':profile_id' => $_GET['profile_id'],
                            ':user_id' => $_SESSION['user_id'])
                    );

                    $_SESSION['success'] = "Profile updated";
                    header("Location: index.php");
                    return;
                }
            } else {
                $_SESSION['error'] = "All fields are required";
                header("Location: edit.php?profile_id=".htmlentities($_GET['profile_id']));
                return;
            }
        } elseif ( isset($_POST['cancel'])) {
            header("Location: index.php");
            return;
        }
    }
}

?>

<html>

<head>
    <?php require_once "bootstrap.php"; ?>
    <link rel="stylesheet" href="css/style.css">
    <title>Vadym Stavskyi's Profile Add</title>

</head>
<body>
<?php
$stmt = $pdo->prepare("SELECT name FROM users
            WHERE user_id = :user_id");
$stmt->execute(array(":user_id" => $_SESSION['user_id']));
$name = $stmt->fetch(PDO::FETCH_ASSOC);

echo('<div class="container">');
echo("<h1>Editing Profile for ".htmlentities($name['name']));

if ( isset($_SESSION['error']) ) {
    echo('<br><p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>

<div class="container">

    <form method="POST">
        <div>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" size="60"
                   value="<?php echo $profile["first_name"]?>">
        </div><br>
        <div>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" size="60"
                   value="<?php echo $profile["last_name"]?>">
        </div><br>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" size="30"
                   value="<?php echo $profile["email"]?>">
        </div>

        <div>
            <br>
            <label for="headline">Headline:</label>
            <br>
            <input type="text" name="headline" id="headline" size="80"
                   value="<?php echo $profile["headline"]?>">
        </div>
        <div>
            <br>
            <label for="summary">Summary:</label>
            <br>
            <textarea type="text" name="summary" id="summary" rows="8" cols="80""><?php echo $profile["summary"]?></textarea>
            <br/>
        </div>
        <p>
            <input type="submit" name="save" value="Save"/>
            <input type="submit" name="cancel" value="Cancel"/>
        </p>
    </form>
</div>
</html>


