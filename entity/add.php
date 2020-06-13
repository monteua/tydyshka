<?php

session_start();

require_once "../config/config.php";
require_once "../config/pdo.php";
require_once "../baseView.php";
require_once "../header.php";
require_once "../components/datepicker.php";


if ( !isset($_SESSION['user_id']) ) {
    die('Not logged in');
} elseif ( isset($_POST['add']) ) {
    if ( isset($_POST['headline']) && isset($_POST['description'])
        && isset($_POST['priority']) && isset($_POST['deadline']) ) {
        if (strlen($_POST['headline']) > 0 && strlen($_POST['description']) > 0
            && strlen($_POST['priority']) > 0 && strlen($_POST['deadline']) > 0 ) {

            if ( is_numeric($_POST['priority']) ) {
                $stmt = $pdo->prepare('INSERT INTO Entities 
                (user_id, headline, description, priority, deadline)
                VALUES ( :user_id, :headline, :description, :priority, :deadline )');

                $stmt->execute(array(
                        ':headline' => htmlentities($_POST['headline']),
                        ':description' => htmlentities($_POST['description']),
                        ':priority' => htmlentities($_POST['priority']),
                        ':deadline' => htmlentities($_POST['deadline']),
                        ':user_id' => $_SESSION['user_id'])
                );

                $_SESSION['success'] = "Record added";
                header("Location: ".BASE_URL, true, 301);
                return;
            } else {
                $_SESSION['error'] = "Priority should be a number";
                header("Location: ".BASE_URL."entity/add");
                return;
            }
        } else {
            $_SESSION['error'] = "All fields are required";
            header("Location: ".BASE_URL."entity/add");
            return;
        }
    }
} elseif ( isset($_POST['cancel'])) {
    header("Location: ".BASE_URL);
    return;
}

?>

<html>
    <head>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <title>Add Item</title>
    </head>
    <body>
        <div class="container addItem">
            <form method="post">
                <h1>Add Item</h1>
                    <?php
                    if ( isset($_SESSION['error']) ) {
                        echo('<br><p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                        unset($_SESSION['error']);
                    }
                    ?>
                <div>
                    <label for="headline">Headline:</label><br>
                    <input type="text" name="headline" id="headline" size="78" maxlength="80">
                </div><br>
                <div>
                    <label for="priority">Priority:</label><br>
                    <input type="number" name="priority" id="priority" size="78">
                </div><br>
                <div>
                    <label for="deadline">Deadline:</label><br>
                    <input type="text" name="deadline" id="datepicker" placeholder="YYYY-MM-DD" size="78" autocomplete="off">
                </div>
                <div>
                    <br>
                    <label for="description">Description:</label>
                    <br>
                    <textarea name="description" id="description" rows="8" cols="80"></textarea>
                    <br/>
                </div>
                    <button class="btn btn-success" id="add" type="submit" name="add" value="Add">Add</button>
                    <button class="btn btn-outline-danger" id="cancel" type="submit" name="cancel" value="Cancel">Cancel</button>
                </p>
            </form>
        </div>
</html>