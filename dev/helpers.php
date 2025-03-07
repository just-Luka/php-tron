<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

if (!function_exists('warning')) {
    function warning($message, $context = []): void {
        $logger = new Logger('MeinLog');
        $logger->pushHandler(new StreamHandler(__DIR__ . '/../log/warning.log', Logger::WARNING));
        $logger->warning($message, $context);
    }
}