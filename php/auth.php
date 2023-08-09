<?php
session_start();

global $host, $dbname, $user, $password_db, $table_user;

include 'key_const.php';

function signIn()
{
    include 'config.php';

    if (isset($_POST['phone_email']) && isset($_POST['password'])) {

        $emailPhone = trim($_POST['phone_email']);
        $password = trim($_POST['password']);

        $selectStmt = $connectDb->prepare("SELECT id, password FROM $table_user WHERE email=:emailPhone OR phone=:emailPhone");
        $selectStmt->bindParam(':emailPhone', $emailPhone, PDO::PARAM_STR);
        $selectStmt->execute();
        $userDb = $selectStmt->fetch(PDO::FETCH_ASSOC);

        if ($userDb && password_verify($password, $userDb['password'])) {
            session_start();

            $_SESSION['id'] = $userDb['id'];
            header("Location: profile.php");
            exit();
        } else {
            echo "Неверный логин или пароль";
        }
    }
}

function check_captcha($token)
{
    $ch = curl_init();
    $args = http_build_query([
        "secret" => SMARTCAPTCHA_SERVER_KEY,
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],

    ]);
    curl_setopt($ch, CURLOPT_URL, "https://smartcaptcha.yandexcloud.net/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}

$token = $_POST['smart-token'];
if (check_captcha($token)) {
    signIn();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/auth.css">

</head>
<body>
<div class="authorize">
    <div class="authorize__wrapper">
        <h2 class="authorize__title">Авторизация</h2>
        <form class="form-authorize" id="authorize" name="authorize"
              method="POST">
            <input type="text" id="phone_email" name="phone_email" placeholder="Введите email или номер телефона"
                   type="text" required>
            <input type="password" id="password" name="password" placeholder="Пароль" type="password" required>
            <div id="captcha-container" class="smart-captcha"
                 data-sitekey=<?= DATA_SITEKEY ?>>
                <input type="hidden" name="smart-token" value="">
            </div>
            <input name="login" type="submit" value="Войти">
        </form>
        <a class="register__link" href="registration.php">Зарегистрироваться</a>
    </div>
</div>
<script src="https://smartcaptcha.yandexcloud.net/captcha.js" defer></script>
</body>
</html>