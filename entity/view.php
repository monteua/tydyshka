<?php

session_start();

require_once "../pdo.php";
require_once "../baseView.php";
require_once "../header.php";

if ( isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $stmt = $pdo -> prepare('SELECT item_id, headline, description, priority, deadline FROM entities 
                    WHERE item_id = :item_id');
    $stmt -> execute(array('item_id' => htmlentities($_GET['item_id'])));
    $entity = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $entity == null ) {
        $_SESSION['error'] = "Selected record does not exist";
        header("Location: ./");
        return;
    }
} elseif ( isset($_GET['done']) ) {
    header("Location: ./");
    return;
}

?>

<html>
    <head>
        <title>Item Details</title>
    </head>
    <div class="container">
        <h1>Record Information</h1>

        <?php
        echo("<div><br>");
            echo('<b>Headline:</b><br> '.htmlentities($entity['headline']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Description:</b><br> '.htmlentities($entity['description']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Priority:</b> '.htmlentities($entity['priority']));
        echo('</div>');
        echo("<div><br>");
            echo('<b>Deadline:</b> '.htmlentities($entity['deadline']));
        echo('</div>');
        ?>

        <div>
            <br><a class="btn btn-primary" href="../">Done</a><br/>
        </div>

</html>
