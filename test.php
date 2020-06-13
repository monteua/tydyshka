<?php

require_once './config/config.php';

$password = hash('md5', SALT.'test@example.com');
echo($password);
