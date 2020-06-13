<?php

$dbHost = "localhost";
$dbport = "3307";
$dbName = "";
$dbUserName = "";
$dbPassword = "";

$pdo = new PDO('mysql:host='.$dbHost.';port='.$dbport.';dbname='.$dbName, $dbUserName, $dbPassword);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);