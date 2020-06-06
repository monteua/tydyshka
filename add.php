<?php

session_start();

require_once "pdo.php";

if ( ! isset($_SESSION['user_id']) ) {
    die('Not logged in');
} elseif ( isset($_POST['add']) ) {
    if ( isset($_POST['first_name']) && isset($_POST['last_name'])
            && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {
        if (strlen($_POST['first_name']) > 0 && strlen($_POST['last_name']) > 0
            && strlen($_POST['email']) > 0 && strlen($_POST['headline']) > 0 && strlen($_POST['summary']) > 0) {
            if (strpos($_POST['email'], '@') == !true) {
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: add.php");
                return;
            } else {
                $stmt = $pdo->prepare('INSERT INTO Profile 
                    (user_id, first_name, last_name, email, headline, summary)
                    VALUES ( :uid, :fn, :ln, :em, :he, :su)');

                $stmt->execute(array(
                        ':uid' => $_SESSION['user_id'],
                        ':fn' => $_POST['first_name'],
                        ':ln' => $_POST['last_name'],
                        ':em' => $_POST['email'],
                        ':he' => $_POST['headline'],
                        ':su' => $_POST['summary'])
                );

                $_SESSION['success'] = "Record added";
                header("Location: index.php");
                return;
            }
        } else {
            $_SESSION['error'] = "All fields are required";
            header("Location: add.php");
            return;
        }
    }
} elseif ( isset($_POST['cancel'])) {
    header("Location: index.php");
    return;
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
        echo("<h1>Adding Profile for ".htmlentities($name['name']));

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
                       value="<?php echo isset($_POST["first_name"]) ? $_POST["first_name"] : ''; ?>">
            </div><br>
            <div>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" size="60"
                       value="<?php echo isset($_POST["last_name"]) ? $_POST["last_name"] : ''; ?>">
            </div><br>
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" size="30"
                       value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ''; ?>">
            </div>

            <div>
                <br>
                    <label for="headline">Headline:</label>
                <br>
                    <input type="text" name="headline" id="headline" size="80"
                           value="<?php echo isset($_POST["headline"]) ? $_POST["headline"] : ''; ?>">
            </div>
            <div>
                <br>
                    <label for="summary">Summary:</label>
                <br>
                    <textarea type="text" name="summary" id="summary" rows="8" cols="80"
                              value="<?php echo isset($_POST["summary"]) ? $_POST["summary"] : ''; ?>"></textarea>
                <br/>
            </div>
            <p>
                <input type="submit" name="add" value="Add"/>
                <input type="submit" name="cancel" value="Cancel"/>
            </p>
        </form>
    </div>
</html>