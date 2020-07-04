<?php

require_once "config/pdo.php";


function removeItem($item_id) {
    global $pdo;

    $stmt = $pdo->prepare('SELECT item_id, headline, user_id, description, priority, deadline 
                    FROM Entities 
                    WHERE item_id = :item_id AND user_id = :user_id');
    $stmt->execute(array('item_id' => htmlentities($item_id), 'user_id' => $_SESSION['user_id']));
    $entity = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($entity == null || $_SESSION['user_id'] !== $entity['user_id']) {
        $_SESSION['error'] = "You don't have access to this item";
        unset($_SESSION['user_id']);
        header("Location: ".BASE_URL."/login");
        return;
    }

    $stmt = $pdo->prepare('DELETE FROM Entities WHERE item_id = :item_id 
                  AND user_id = :user_id');
    $stmt->execute(array('item_id' => htmlentities($item_id), 'user_id' => $_SESSION['user_id']));
    $_SESSION['success'] = "Item removed successfully";
    return;
}
?>

<div>
    <form method="post">
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
                        <button type="submit" name="delete" class="btn btn-danger"">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
