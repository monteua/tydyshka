<?php

session_start();

require_once ("../config/config.php");
require_once ("../config/pdo.php");
require_once ("../baseView.php");


if ( isset($_POST['cancel'] ) ) {
    header("Location: ".BASE_URL);
    return;
}

if ( isset($_POST['email']) && isset($_POST['password']) ) {

    if ( strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) {
        $_SESSION['error'] = "Email address and password are required";
        header("Location: ".BASE_URL."account/login");
        return;
    } elseif (strpos($_POST['email'], '@') ==! true ) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: ".BASE_URL."account/login");
        return;
    } else {
        $password = hash('md5', SALT.$_POST['password']);
        $stmt = $pdo->prepare("SELECT user_id, email, password FROM users
            WHERE email = :email AND password = :password");
        $stmt->execute(array(":email" => $_POST['email'], ":password" => $password));
        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if ( $account !== null && $account['password'] === $password ) {
            $_SESSION['success'] = "Login success ".$_POST['email'];
            $_SESSION['user_id'] = $account['user_id'];
            error_log("Login success ".$_POST['email']);
            header("Location: ".BASE_URL);
            return;
        } else {
            $_SESSION['error'] = "Incorrect email address or password";
            header("Location: ".BASE_URL."account/login");
            error_log("Login fail ".$_POST['email']." $password");
            return;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="<?php echo (BASE_URL.'css/style.css')?>">
    <link rel="stylesheet" href="<?php echo (BASE_URL.'css/login.css')?>">
    <title>Account Login</title>
</head>
<body>
    <form method="post" class="form-signin">
        <a href="<?php echo (BASE_URL)?>">
            <img src="<?php echo (BASE_URL.'images/logo.png')?>" alt="TYDYSHKA" width="150" height="150">
        </a>
        <h1 class="h3 mb-3 font-weight-normal">Please Log In</h1>
        <?php
        if ( isset($_SESSION['error']) ) {
            error_log($_SESSION['error']);
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>");
            unset($_SESSION['error']);
        } elseif ( isset($_SESSION['success']) ) {
            echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>");
            unset($_SESSION['success']);
        }
        ?>

        <input type="email" name="email" id="email" class="form-control" placeholder="Email Address">
        <input type="password" name="password" id="password" class="form-control" placeholder="Password"><br/>
        <button type="submit" onclick="return doValidate();" value="Log In" class="btn btn-sm btn-primary btn-block">Log In</button>
        <button type="submit" name="cancel" value="Cancel" class="btn btn-sm btn-outline-secondary btn-block">Cancel</button>
    </form>

    <!--script>
        function doValidate() {
            console.log('Validating...');

            let addr;
            let pw;

            try {
                addr = document.getElementById('email').value;
                pw = document.getElementById('password').value;
                console.log("Validating addr=" + addr + " pw=" + pw);
                if (addr == null || addr === "" || pw == null || pw === "") {
                    alert("Both fields must be filled out");
                    return false;
                }
                if (addr.indexOf('@') === -1) {
                    alert("Invalid email address");
                    return false;
                }
                return true;
            } catch (e) {
                return false;
            }
        }
    </script-->
</body>
</html>