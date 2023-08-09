<!DOCTYPE html>
<html>
<head>
	<title>Форма ввода</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../style/registration.css">
</head>
<body>
	<div class="register">
        <div class="register__wrapper">
            <h2 class="register__title">Регистрация</h2>
            <form class="form-register" id="register" name="register" action="register.php"
                  method="POST">
                <input type="text" id="name" name="name" placeholder="Введите ваше имя" type="text" required>
                <input type="text" id="phone" name="phone" placeholder="Введите номер телефона" type="tel" required>
                <input type="text" id="email" name="email" placeholder="Введите email" type="email" required>
                <input type="password" id="password" name="password" placeholder="Введите пароль" type="password" required>
                <input type="password" id="password_confirm" name="password_confirm" placeholder="Повторите пароль"type="password" required>
                <input type="submit" value="Зарегистрироваться">
            </form>
            <a class="register__link" href="auth.php">Войти</a>
        </div>
	</div>
</body>
</html>
																								   