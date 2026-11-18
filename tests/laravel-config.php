<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Rmunate\SqlServerLite\Exceptions\SQLServerException;
use Rmunate\SqlServerLite\SQLServer;

require dirname(__DIR__).'/vendor/autoload.php';

$app = new Application(dirname(__DIR__));
$app->instance('config', new Repository([
    'database' => [
        'connections' => ['malformed' => 'not an array'],
    ],
]));

foreach (['connection' => 'missing', 'status' => 'malformed'] as $method => $connection) {
    try {
        SQLServer::$method($connection);
    } catch (SQLServerException $exception) {
        if (strpos($exception->getMessage(), $connection) === false
            || strpos($exception->getMessage(), 'config/database.php') === false) {
            throw $exception;
        }

        continue;
    }

    throw new RuntimeException("{$method}() accepted the invalid '{$connection}' configuration.");
}

echo 'Configuration tests passed on Laravel '.Application::VERSION.PHP_EOL;
