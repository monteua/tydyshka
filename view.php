<?php

session_start();

require_once "pdo.php";

if ( isset($_GET['profile_id']) && is_numeric($_GET['profile_id'])) {
    $stmt = $pdo -> prepare('SELECT profile_id, first_name, last_name, email, headline, summary FROM Profile 
                    WHERE profile_id = :profile_id');
    $stmt -> execute(array('profile_id' => htmlentities($_GET['profile_id'])));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $profile == null ) {
        $_SESSION['error'] = "Selected PROFILE does not exist";
        header("Location: index.php");
        return;
    }
}

?>

<html>
    <head>
        <?php require_once "bootstrap.php"; ?>
        <link rel="stylesheet" href="css/style.css">
        <title>Vadym Stavskyi's Profile Information</title>

    </head>
    <div class="container">
        <h1>Profile Information</h1>

        <?php
        echo("<div><br>");
            echo('<b>First name:</b> '.htmlentities($profile['first_name']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Last name:</b> '.htmlentities($profile['last_name']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Email:</b> '.htmlentities($profile['email']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Headline:</b><br> '.htmlentities($profile['headline']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Summary:</b><br> '.htmlentities($profile['summary']));
        echo('</div>');
        ?>

        <div>
            <br><a href="index.php">Done</a><br/>
        </div>

</html>
