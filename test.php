<?php

$salt = 'XyZzy12*_';

$password = hash('md5', $salt.'test@example.com');
echo($password);
