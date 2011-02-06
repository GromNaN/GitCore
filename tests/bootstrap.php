<?php

define('FIXTURES', __DIR__.'/fixtures/');
define('DEBUG', false);

if (file_exists($file = __DIR__.'/../autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/../autoload.php.dist')) {
    require_once $file;
}
