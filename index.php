<?php

session_start();

require_once "pdo.php";
require_once "baseView.php";
require_once "inspire.php";

if ( !isset($_SESSION['user_id']) ) {
    $stmt = $pdo->query("SELECT profile_id, first_name, last_name, headline 
        FROM profile
        WHERE user_id = (SELECT user_id FROM users WHERE email = 'test@example.com')");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline 
        FROM profile 
        WHERE user_id = $user_id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <title>TYDYSHKA</title>
    <div>
        <?php
        if ( !isset($_SESSION['user_id']) ) {
            echo '<a href="account/register" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Create an Account</a>';
            echo '<a href="account/login" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Log in</a>';
        } else {
            echo '<a href="account/logout" class="btn btn-lg btn-primary" style="float: right; margin: 1em">Log out</a>';
            echo '<a href="add" class="btn btn-lg btn-primary" style="float: right; margin-top: 1em">Add New Entry</a>';
        }
        ?>
    </div>
    <img src="images/logo.png" width="170" height="170">
</head>
<body>
    <div class="container">
        <?php

        if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        } elseif ( isset($_SESSION['success']) ) {
            echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
            unset($_SESSION['success']);
        }

        if ( !isset($_SESSION['user_id']) ) {
            echo '
                <div class="quote">
                    <div class="alert alert-info" role="alert">
                        You should <a href="account/login">Log In</a> or <a href="account/register">Create a New Account</a> in order to access all the site features
                    </div>
                    <p>Here\'s an inspiring quote, which would make your day brighter:</p><b>';
            echo getQuote();
            echo '</b></div>
                <div id="accordion" class="fixed-bottom">
                  <div class="card">
                    <div class="card-header" id="headingOne">
                      <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          Demo Table View (showing data from the test account - l/p: test@example.com
                        </button>
                      </h5>
                    </div>
                
                    <div id="collapseOne" class="collapse hide" aria-labelledby="headingOne" data-parent="#accordion">
                      <div class="card-body">
                ';

            if ( $rows != null ) {
                echo '<table border="1" class="form">';
                echo "<tr><td><b>Name</b><td><b>Headline</b></td></td>";
                foreach ($rows as $row) {
                    echo("<tr><td>");
                    echo('<a href="view?profile_id='.$row['profile_id'].'">'
                        .htmlentities($row['first_name'] . ' ' . $row['last_name']).'</a>');
                    echo("</td><td>");
                    echo(htmlentities($row['headline']));
                    echo("</td></tr>\n");
                }
                echo '</table>';
            }

            echo '
                      </div>
                    </div>
                  </div>
                </div>
            ';

        } elseif ( isset($_SESSION['user_id']) && $rows != null ) {
            echo('<table border="1" class="form">');
            echo("<tr><td><b>Name</b><td><b>Headline</b></td></td><td><b>Action</b><td>");
            foreach ($rows as $row) {
                echo "<tr><td>";
                echo('<a href="view?profile_id='.$row['profile_id'].'">'
                    .htmlentities($row['first_name'] . ' ' . $row['last_name']).'</a>');
                echo("</td><td>");
                echo(htmlentities($row['headline']));
                echo("</td><td>");
                echo('<a href="edit?profile_id='.$row['profile_id'].'">Edit</a> / ');
                echo('<a href="delete?profile_id='.$row['profile_id'].'">Delete</a>');
                echo("</td></tr>\n");
            }
            echo '</table>';
        } else {
            echo '
                <div class="alert alert-warning" role="alert">
                    Hm... Nothing was added yet.
                </div>
            ';
        }
        ?>
    </div>
</body>