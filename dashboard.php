<?php 
require 'auth.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <ul class="menu">
        <li><a href="index.html">Главная</a></li>
        <li><a href="catalog.html">Каталог</a></li>
        <li><a href="cart.html">Корзина (<span id="cart-counter">0</span>)</a></li>
        <li><a href="logout.php">Выход</a></li>
    </ul>
    <hr>
    <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
    <p>Это защищённая страница.</p>
    <div id="cart-items"></div>
    <div id="cart-total"></div>
    <script>
        // Переиспользуем JavaScript из старого проекта для отображения корзины
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof showCart === 'function') showCart();
        });
    </script>
</body>
</html>