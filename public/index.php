<?php
declare(strict_types=1);

require_once __DIR__ . '/../autoloader.php';

session_start();
if (empty($_SESSION['user'])) {
    redirect('/login.php');
}

require __DIR__ . '/../views/index.php';
