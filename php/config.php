<?php
$host = 'localhost';
$user = 'root';
$password_db = '';
$dbname = 'only';
$table_user = 'only_user';
try {
    $connectDb = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password_db);
}catch (PDOException $e) {

    echo "Ошибка: " . $e->getMessage();
}
