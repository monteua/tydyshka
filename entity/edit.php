<?php

session_start();

require_once "../config/config.php";
require_once "../config/pdo.php";
require_once "../baseView.php";
require_once "../header.php";
require_once "../components/datepicker.php";


if ( !isset($_SESSION['user_id']) ) {
    die('Not logged in');
} elseif ( isset($_GET['item_id']) && is_numeric($_GET['item_id'])) {
    $stmt = $pdo -> prepare('SELECT item_id, user_id, headline, description, priority, deadline 
                    FROM Entities 
                    WHERE item_id = :item_id AND user_id = :user_id');
    $stmt -> execute(array('item_id' => htmlentities($_GET['item_id']), 'user_id' => $_SESSION['user_id']));
    $entity = $stmt->fetch(PDO::FETCH_ASSOC);

    if ( $entity == null ) {
        $_SESSION['error'] = "Selected record does not exist";
        header("Location: ".BASE_URL);
        return;
    } elseif ( $_SESSION['user_id'] !== $entity['user_id'] ) {
        $_SESSION['error'] = "Current user does not own this record. Unable to edit";
        header("Location: ".BASE_URL);
        return;
    } else {
        if ( isset($_POST['headline']) && isset($_POST['description'])
            && isset($_POST['priority']) && isset($_POST['deadline']) ) {
            if (strlen($_POST['headline']) > 0 && strlen($_POST['description']) > 0
                && strlen($_POST['priority']) > 0 && strlen($_POST['deadline']) > 0 ) {

                if ( is_numeric($_POST['priority']) ) {
                    $update = $pdo->prepare('UPDATE Entities SET
                    headline = :headline,
                    description = :description,
                    priority = :priority,
                    deadline = :deadline
                    WHERE item_id = :item_id AND user_id = :user_id');

                    $update->execute(array(
                            ':headline' => htmlentities($_POST['headline']),
                            ':description' => htmlentities($_POST['description']),
                            ':priority' => htmlentities($_POST['priority']),
                            ':deadline' => htmlentities($_POST['deadline']),
                            ':item_id' => htmlentities($_GET['item_id']),
                            ':user_id' => $_SESSION['user_id'])
                    );

                    $_SESSION['success'] = "Record updated";
                    header("Location: ".BASE_URL);
                    return;
                } else {
                    $_SESSION['error'] = "Priority should be a number";
                    header("Location: ".BASE_URL."entity/edit.php?item_id=".htmlentities($_GET['item_id']));
                    return;
                }
            } else {
                $_SESSION['error'] = "All fields are required";
                header("Location: ".BASE_URL."entity/edit.php?item_id=".htmlentities($_GET['item_id']));
                return;
            }
        } elseif ( isset($_POST['cancel'])) {
            header("Location: ".BASE_URL);
            return;
        }
    }
}

?>

<html>
    <head>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <title>Edit Item</title>
    </head>
    <body>
        <div class="container addItem">
        <?php
            if ( isset($_SESSION['error']) ) {
                echo('<br><p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }
        ?>
            <form method="post">
                <div>
                    <label for="headline">Headline:</label><br>
                    <input type="text" name="headline" id="headline" size="78" maxlength="100"
                           value="<?php echo htmlentities($entity["headline"])?>">
                </div><br>
                <div>
                    <label for="priority">Priority:</label><br>
                    <input type="number" name="priority" id="priority" size="78"
                           value="<?php echo htmlentities($entity["priority"])?>">
                </div><br>
                <div>
                    <label for="deadline">Deadline:</label><br>
                    <input type="text" name="deadline" id="datepicker" placeholder="YYYY-MM-DD" size="78" autocomplete="off"
                           value="<?php echo htmlentities($entity["deadline"])?>">
                </div>
                <div>
                    <br>
                    <label for="description">Description:</label>
                    <br>
                    <textarea type="text" name="description" id="description" rows="8"
                              cols="80"><?php echo htmlentities($entity["description"])?></textarea>
                    <br/>
                </div>
                <button class="btn btn-success" id="add" type="submit" name="update" value="Update">Update</button>
                <a class="btn btn-outline-danger" id="cancel" href="../">Cancel</a>
                </p>
            </form>
        </div>
    </body>
</html>