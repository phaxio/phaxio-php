<?php

/**
 * Simple autoloader for examples.
 * Use a proper PSR-0 autoloader implementation in production.
 */
function __autoload($class)
{
    require __DIR__ . '/../lib/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
}
