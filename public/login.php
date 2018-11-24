<?php
declare(strict_types=1);

require_once __DIR__ . '/../autoloader.php';

session_start();
if (!empty($_SESSION['user'])) {
    redirect('/index.php');
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$error = false;

if (!empty($username) || !empty($password)) {
    $userService = $container->getUserService();
    $user = $userService->findByUsername($username);
    if (null === $user || !$user->comparePassword($password)) {
        $error = true;
    } else {
        $_SESSION['user'] = $username;
        redirect('/index.php');
    }
}

require __DIR__ . '/../views/login.php';
