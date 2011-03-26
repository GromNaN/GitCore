<?php

namespace Git\Tests;

use Git\Repository;

class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Git\Repository
     */
    protected static $repository;

    public static function setUpBeforeClass()
    {
        $dir = \sys_get_temp_dir().'/phpunit/GitCore';

        if(!\file_exists($dir.'/.git/HEAD')) {
            \mkdir($dir, 0777, true);
            exec('git clone git://github.com/GromNaN/test-fixture.git '.escapeshellarg($dir));
            exec('cd '.escapeshellarg($dir).' && git checkout parallel');
            exec('cd '.escapeshellarg($dir).' && git checkout master');
        }

        // Options defined in config.php
        $debug = defined('GIT_DEBUG') && GIT_DEBUG;
        $options = defined('GIT_EXEC') ? array('git_executable' => GIT_EXEC) : array();

        self::$repository = new Repository($dir, false, $options);
    }
}
