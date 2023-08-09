<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/registration.css">
</head>
<body>

<?php

include 'config.php';

session_start();

if (isset($_SESSION['id'])) {

    $userId = $_SESSION['id'];

    $selectStmt = $connectDb->prepare("SELECT * FROM $table_user WHERE id = :userId");
    $selectStmt->bindParam(':userId', $userId, PDO::PARAM_STR);
    $selectStmt->execute();
    $userDb = $selectStmt->fetch(PDO::FETCH_ASSOC);
} else {
    header("Location: ../index.php");
    exit();
}

if (isset($_POST['update'])) {
    $userId = $_SESSION['id'];
    $username = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $newPassword = $_POST['password'];
    $newPasswordConf = $_POST['password_confirm'];

    if ($_POST['password'] !== $_POST['password_confirm']) {
        echo "Пароли не совпадают";
        exit;
    }

    $password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $updateUserStmt = $connectDb->prepare("UPDATE $table_user SET user_name=:username, phone=:phone, email=:email, password=:password WHERE id=:id");
    $updateUserStmt->bindParam(':id', $userId);
    $updateUserStmt->bindParam(':username', $username);
    $updateUserStmt->bindParam(':phone', $phone);
    $updateUserStmt->bindParam(':email', $email);
    $updateUserStmt->bindParam(':password', $password_hash);
    try {
        $updateUserStmt->execute();
        echo "Ваши данные обновлены успешно.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<?php
if (isset($userDb)): ?>

    <div class="register">
        <div class="register__wrapper">
            <h1 class="register__title">Профиль <?= htmlspecialchars($userDb['user_name']) ?></h1>
            <h2 class="register__subtitle">Редактирование профиля пользователя</h2>
            <form class="form-register" id="register" name="register"
                  method="POST">
                <input type="text" id="name" name="name" placeholder="Введите ваше имя" type="text"
                       value="<?= $userDb['user_name'] ?>" required>
                <input type="text" id="phone" name="phone" placeholder="Введите номер телефона" type="tel"
                       value="<?= $userDb['phone'] ?>" required>
                <input type="text" id="email" name="email" placeholder="Введите email" type="email"
                       value="<?= $userDb['email'] ?>" required>
                <input type="password" id="password" name="password" placeholder="Новый пароль" type="password"
                       required>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Повторите пароль"
                       type="password" required>
                <input type="submit" name="update" value="Редактировать">
                <p><a href="logout.php">Выйти из профиля</a>
            </form>
        </div>
    </div>

<?php else: ?>

    <p><a href="auth.php">Log in</a> or <a href="register.php">sign up</a></p>

<?php endif; ?>
</body>
</html>










