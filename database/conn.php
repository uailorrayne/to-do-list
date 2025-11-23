<?php

$hostname = 'localhost';
$database = 'to_do_list';
$username = 'postgres';
$password = '38082259';

try {
    $pdo = new PDO("pgsql:host=$hostname;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}
