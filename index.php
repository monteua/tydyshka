<?php

session_start();

require_once "config/config.php";
require_once ROOT_PATH."header.php";
require_once ROOT_PATH."baseView.php";
require_once ROOT_PATH."config/pdo.php";
require_once ROOT_PATH."inspire.php";

if ( !isset($_SESSION['user_id']) ) {
    $stmt = $pdo->query("SELECT item_id, headline, description, priority, deadline 
        FROM Entities
        WHERE user_id = (SELECT user_id FROM users WHERE email = 'test@example.com') LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->query("SELECT item_id, headline, description, priority, deadline 
        FROM entities 
        WHERE user_id = $user_id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>TYDYSHKA</title>

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
                        You should <a href="'.BASE_URL.'account/login">Log In</a> or <a href="'.BASE_URL.'account/register">Create a New Account</a> in order to access all the site features
                    </div>
                    <p>Here\'s an inspiring quote, which would make your day brighter:</p><b>';
            echo (new Inspire)->getQuote();
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
                echo '<table class="table table-hover">';
                echo "<tr><td><b>Headline</b></td><td><b>Priority</b></td><td><b>Deadline</b></td>";
                foreach ($rows as $row) {
                    echo("<tr><td>");
                    echo('<a href="'.BASE_URL.'entity/view?item_id='.$row['item_id'].'">'
                        .htmlentities($row['headline']));
                    echo("</td><td>");
                    echo(htmlentities($row['priority']));
                    echo("</td><td>");
                    echo(htmlentities($row['deadline']));
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
            echo('<table class="table table-hover">');
            echo('<tr><td><b>#</b></td><td><b>Headline</b></td><td><b>Priority</b></td><td><b>Deadline</b></td><td><b>Action</b></td></tr>');
            $idx = 1;
            foreach ($rows as $row) {
                echo '<tr><td>'.$idx.'</td><td>';
                echo('<a href="'.BASE_URL.'entity/view?item_id='.$row['item_id'].'">'
                    .htmlentities($row['headline']));
                echo("</td><td>");
                echo(htmlentities($row['priority']));
                echo("</td><td>");
                echo(htmlentities($row['deadline']));
                echo('</td><td>');
                echo('<a class="btn btn-outline-primary" href="'.BASE_URL.'entity/edit?item_id='.$row['item_id'].'">Edit</a> ');
                echo('<a class="btn btn-outline-danger" href="'.BASE_URL.'entity/delete?item_id='.$row['item_id'].'">Delete</a>');
                echo('</td></tr>');
                $idx += 1;
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
</html>