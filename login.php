<?php require "auth.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <ul class="menu">
        <li><a href="index.html">Главная</a></li>
        <li><a href="catalog.html">Каталог</a></li>
        <li><a href="cart.html">Корзина</a></li>
        <li><a href="login.php">Вход</a></li>
    </ul>
    <hr>
    <h2>Авторизация</h2>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'] ?? '';
        $pass = $_POST['password'] ?? '';
        if (login($login, $pass)) {
            header('Location: dashboard.php');
            exit;
        } else {
            echo "<p style='color:red'>Неверный логин или пароль</p>";
        }
    }
    ?>
    <form method="post">
        <label>Логин: <input type="text" name="login" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <button type="submit">Войти</button>
    </form>
</body>
</html>