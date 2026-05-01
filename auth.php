<?php
session_start();
require_once __DIR__ . '/api/models/User.php';

$userModel = new User();

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
    global $userModel;
    if ($userModel->verifyPassword($email, $password)) {
        $_SESSION["user_email"] = $email;
        $user = $userModel->findByEmail($email);
        logAuth($email, 'SUCCESS_LOGIN');
        return true;
    }
    logAuth($email, 'FAIL_LOGIN');
    return false;
}

function logout() {
    if (isset($_SESSION['user'])) {
        logAuth($_SESSION['user'], 'LOGOUT');
        unset($_SESSION['user']);
        session_destroy();
    }
}