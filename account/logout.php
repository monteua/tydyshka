<?php

require_once ("../config/config.php");

session_start();
unset($_SESSION['name']);
unset($_SESSION['user_id']);
header('Location: '.BASE_URL);