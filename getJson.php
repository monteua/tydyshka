<?php

session_start();

require_once "config/config.php";
require_once "config/pdo.php";


header('Content-Type: application/json; charset=utf-8');

if ( !isset($_SESSION['user_id']) ) {
    $stmt = $pdo->query("SELECT item_id, headline, description, priority, deadline 
FROM Entities
WHERE user_id = (SELECT user_id FROM users WHERE email = 'test@example.com') LIMIT 5");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->query("SELECT item_id, headline, description, priority, deadline 
FROM Entities 
WHERE user_id = $user_id");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($rows, JSON_PRETTY_PRINT);
