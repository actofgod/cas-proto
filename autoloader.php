<?php
declare(strict_types=1);

require_once __DIR__ . '/src/Util/ClassLoader.php';

$classLoader = new \App\Util\ClassLoader('App\\', __DIR__ . '/src/');
spl_autoload_register([$classLoader, 'load']);

$container = new \App\Service\ServiceLocatorService(include(__DIR__ . '/config/config.php'));

function redirect(string $path): void
{
    header('HTTP/1.0 302 Redirect');
    header('Location: ' . $path);

    exit();
}
