<?php

global $host, $dbname, $user, $table_user, $password_db;

include 'config.php';

// проверяем переданы ли поля
if (!isset($_POST['name']) && isset($_POST['phone']) && isset($_POST['email'])
    && isset($_POST["password"]) && isset($_POST["password_confirm"])) {
    echo 'Не все поля заполнены.';
    exit;
}

$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
// Валидация email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo 'Неправильный формат email.';
    exit;
}
// Валидация номера телефона
if (!preg_match('/^\d{11}$/', $phone)) {
    echo 'Неправильный формат номера телефона. Введите 11 цифр без разделителей.';
    exit;
}
if ($_POST['password'] !== $_POST['password_confirm']) {
    echo 'Пароли не совпадают';
    exit;
}
$password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);


$selectStmt = $connectDb->prepare("SELECT COUNT(*) FROM $table_user WHERE user_name=:name OR email=:email OR phone=:phone");
$selectStmt->bindParam(':name', $name);
$selectStmt->bindParam(':phone', $phone);
$selectStmt->bindParam(':email', $email);
$selectStmt->execute();
$count = $selectStmt->fetchColumn();
// если есть email или телефон, то:
if ($count > 0) {
    echo "Пользователь с такими данными уже существует.";
    exit;
    // иначе добавляем нового пользователя.
} else {

    $newUserStmt = $connectDb->prepare("INSERT INTO $table_user (user_name,phone, email, password) VALUES (:name, :phone, :email, :password)");
    $newUserStmt->bindParam(':name', $name);
    $newUserStmt->bindParam(':phone', $phone);
    $newUserStmt->bindParam(':email', $email);
    $newUserStmt->bindParam(':password', $password_hash);
    try {
        $newUserStmt->execute();
        echo "Данные нового пользователя сохранены успешно.";
    } catch (PDOException $e) {
        echo "Ошибка при сохранении данных пользователя: " . $e->getMessage();
    }
}
// Закрываем соединение с базой данных
$connectDb = null;


