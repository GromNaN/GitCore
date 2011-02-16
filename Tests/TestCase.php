<?php

namespace Git\Core\Tests;

require_once __DIR__.'/../Ressources/autoload.php';

use Git\Core\Repository;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Git\Core\Repository
     */
    protected static $repository;

    public static function setUpBeforeClass()
    {
        $dir = \sys_get_temp_dir().'/phpunit/GitCore';

        if(!\is_dir($dir)) {
            \mkdir($dir, 0777, true);
            exec('git clone git://github.com/GromNaN/test-fixture.git '.escapeshellarg($dir));
        }

        // Options defined in config.php
        $debug = defined('GIT_DEBUG') && GIT_DEBUG;
        $options = defined('GIT_EXEC') ? array('git_executable' => GIT_EXEC) : array();

        self::$repository = Repository::create($dir, false, $options);
    }
}
