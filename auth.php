<?php
session_start();


// Хранилище пользователей
$users = [
    'admin' => password_hash('admin123', PASSWORD_DEFAULT),
    'user' => password_hash('pass456', PASSWORD_DEFAULT),
];

function logAuth($login, $action) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $time = date('Y-m-d H:i:s');
    $logLine = "$time | ip=$ip | login=$login | action=$action" . PHP_EOL;
    file_put_contents(__DIR__ . "/logs/auth.log", $logLine, FILE_APPEND);
}

function isLoggedIn() {
    return isset($_SESSION["user"]);
}

function login($login, $password) {
    global $users;
    if (isset($users[$login]) && password_verify($password, $users[$login])) {
        $_SESSION["user"] = $login;
        logAuth($login, 'SUCCES_LOGIN');
        return true;
    }
    logAuth($login, 'FAIL_LOGIN');
    return false;
}

function logout() {
    if (isset($_SESSION['user'])) {
        logAuth($_SESSION['user'], 'LOGOUT');
        unset($_SESSION['user']);
        session_destroy();
    }
}