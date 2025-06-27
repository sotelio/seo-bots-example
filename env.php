<?php

# put .env variables in PHP environment.

$envFile = file_get_contents(__DIR__ . '/.env');

$envArray = explode(PHP_EOL, $envFile);

foreach ($envArray as $line) {
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line);
        
        # here
        putenv("$key=$value");
    }
}
