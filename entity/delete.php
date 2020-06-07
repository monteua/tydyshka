<?php

session_start();

require_once "../pdo.php";
require_once "../baseView.php";
require_once "../header.php";

if ( ! isset($_SESSION['user_id']) ) {
    die('Not logged in');
} elseif ( isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $stmt = $pdo->prepare('SELECT item_id, headline, user_id, description, priority, deadline 
                    FROM entities 
                    WHERE item_id = :item_id AND user_id = :user_id');
    $stmt->execute(array('item_id' => htmlentities($_GET['item_id']), 'user_id' => $_SESSION['user_id']));
    $entity = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($entity == null) {
        $_SESSION['error'] = "Selected Item does not exist";
        header("Location: ../");
        return;
    } elseif ($_SESSION['user_id'] !== $entity['user_id']) {
        $_SESSION['error'] = "Current user does not own this item. Unable to remove";
        header("Location: ../");
        return;
    } else {
        if ( isset($_POST['delete']) ) {
            $stmt = $pdo->prepare('DELETE FROM entities WHERE item_id = :item_id 
                      AND user_id = :user_id');
            $stmt->execute(array('item_id' => htmlentities($_GET['item_id']), 'user_id' => $_SESSION['user_id']));
            $_SESSION['success'] = "Item removed successfully";
            header("Location: ../");
            return;
        } elseif ( isset($_POST['cancel'])) {
            header("Location: ../");
            return;
        }
    }
}

?>

<html>
<head>
    <title>Remove Item</title>
</head>
<div class="container">
    <h1>Remove Item</h1>
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

    <form method="post">
        <p>
            <button type="button" name="deleteConfirm" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
            <button type="submit" name="cancel" class="btn btn-secondary">Cancel</button>
        </p>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalTitle">Removing the Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to remove the item?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</html>