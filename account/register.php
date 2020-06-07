<?php

session_start();

require_once "../pdo.php";
require_once "../baseView.php";

$salt = 'XyZzy12*_';

if ( isset($_POST['cancel'] ) ) {
    header("Location: ../index.php");
    return;
} elseif ( isset($_POST['create']) ) {
    if ( strlen($_POST['email']) === 0 || strlen($_POST['password']) === 0 ||
        strlen($_POST['confirmPassword']) === 0 || strlen($_POST['userName']) === 0) {
        $_SESSION['error'] = "All fields are required";
        header("Location: register.php");
        return;
    } else {
       if (strpos($_POST['email'], "@") === false) {
           $_SESSION['error'] = "Email must have an at-sign (@)";
           header("Location: register.php");
           return;
       } elseif ($_POST['password'] !== $_POST['confirmPassword']) {
           $_SESSION['error'] = "Password confirmation does not match";
           header("Location: register.php");
           return;
       } else {
           $query = $pdo->prepare("
                SELECT user_id FROM users WHERE email = :email
           ");
           $query->execute(array(
               ":email" => htmlentities($_POST['email'])
           ));
           $account = $query->fetch(PDO::FETCH_ASSOC);

           if ($account == null) {
               $encryptedPassword = hash('md5', $salt.$_POST['password']);
               $query = $pdo->prepare("
                    INSERT INTO users(name, email, password) VALUES (:name, :email, :password)
               ");
               $query->execute(array(
                   ":name" => htmlentities($_POST['userName']),
                   ":email" => htmlentities($_POST['email']),
                   ":password" => htmlentities($encryptedPassword)
               ));

               $query = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
               $query->execute(array(
                   ":email" => htmlentities($_POST['email'])
               ));
               $account = $query->fetch(PDO::FETCH_ASSOC);

               $_SESSION['user_id'] = $account['user_id'];
               $_SESSION['success'] = "Your account was created successfully";
               header("Location: ../index.php");
               return;
           } else {
               $_SESSION['error'] = "This email address is already associated with an account.";
               header("Location: register.php");
               return;
           }
       }
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/login.css">
        <title>Create an Account</title>
    </head>
    <body>
        <form method="post" class="form-signin">
            <img src="../images/logo.png" alt="TYDYSHKA" width="150" height="150">
            <h1 class="h3 mb-3 font-weight-normal">Create an Account</h1>

            <?php
            if ( isset($_SESSION['error']) ) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>");
                unset($_SESSION['error']);
            } elseif ( isset($_SESSION['success']) ) {
                echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>");
                unset($_SESSION['success']);
            }
            ?>

            <input type="text" name="userName" id="userName" class="form-control" placeholder="Name">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            <input type="password" name="confirmPassword" id="confirmPassword"
                   class="form-control" placeholder="Confirm Password"><br/>
            <button type="submit" name="create" value="Create Account" class="btn btn-sm btn-primary btn-block">Create an Account</button>
            <button type="submit" name="cancel" value="Cancel"
                    class="btn btn-sm btn-outline-secondary btn-block">Cancel</button>
        </form>
    </body>
</html>
