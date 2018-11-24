<?php
declare(strict_types=1);

require_once __DIR__ . '/../autoloader.php';

session_start();
if (!empty($_SESSION['user'])) {
    $_SESSION['user'] = null;
    session_destroy();
}
redirect('/login.php');