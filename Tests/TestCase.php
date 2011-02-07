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
        $dir = \sys_get_temp_dir().'/php-git-core/'.\uniqid();
        \mkdir($dir, 0777, true);

        // Options defined in config.php
        $debug = defined('GIT_DEBUG') && GIT_DEBUG;
        $options = defined('GIT_EXEC') ? array('git_executable' => GIT_EXEC) : array();

        $r = Repository::create($dir, false, $options);

        // Repository configuration is required to get the same sha1 hashes
        $r->git('config user.name "Jérôme Tamarelle"');
        $r->git('config user.email "jerome@tamarelle.net"');

        // Init the repository
        \file_put_contents($dir.'/FILE1', 'FILE1');
        $r->git('add FILE1');
        $r->git('commit -m "CREATE FILE1"');
        $r->git('branch parallel');
        \file_put_contents($dir.'/FILE2', 'FILE2');
        $r->git('add FILE2');
        \file_put_contents($dir.'/FILE1', 'FILE1+');
        $r->git('add FILE1');
        $r->git('commit -m "MODIFY FILE1 AND CREATE FILE2"');
        $r->git('checkout parallel');
        \file_put_contents($dir.'/FILE3', 'FILE3');
        $r->git('add FILE3');
        $r->git('commit -m "CREATE FILE3" --author="Amélie Bideau <amelie.bideau@foo.net>"');
        $r->git('checkout master');
        $r->git('merge parallel -m "MERGE BRANCH"');
        \mkdir($dir.'/FOLDER');
        \file_put_contents($dir.'/FOLDER/FILE4', 'FILE4');
        $r->git('add FOLDER/FILE4');
        $r->git('commit -m "CREATE FOLDER/FILE4"');
        $r->git('rm FILE3');
        $r->git('commit -m "DELETE FILE3"');
        $r->git('gc'); // Optimization


        /***********************************************************************
         * The resulting "git log --graph --pretty=fuller" looks like this:

        * commit 99eff111afab71f78bbc5a837658240e0623cf27
        | Author:     Jérôme Tamarelle <jerome@tamarelle.net>
        | AuthorDate: Sun Feb 6 23:21:47 2011 +0100
        | Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
        | CommitDate: Sun Feb 6 23:21:47 2011 +0100
        |
        |     DELETE FILE3
        |
        * commit 588798833d6a5f3d421b679bbfa17689b4dbc458
        | Author:     Jérôme Tamarelle <jerome@tamarelle.net>
        | AuthorDate: Sun Feb 6 23:21:47 2011 +0100
        | Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
        | CommitDate: Sun Feb 6 23:21:47 2011 +0100
        |
        |     CREATE FOLDER/FILE4
        |
        *   commit 9d4c1c31589e1f47ade5aac044ba83f6f598f4c9
        |\  Merge: 5dd6590 1473c26
        | | Author:     Jérôme Tamarelle <jerome@tamarelle.net>
        | | AuthorDate: Sun Feb 6 23:21:47 2011 +0100
        | | Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
        | | CommitDate: Sun Feb 6 23:21:47 2011 +0100
        | |
        | |     MERGE BRANCH
        | |
        | * commit 1473c265a406ebc1b6123e282c25e66ad9b851c1
        | | Author:     Amélie Bideau <amelie.bideau@foo.net>
        | | AuthorDate: Sun Feb 6 23:21:47 2011 +0100
        | | Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
        | | CommitDate: Sun Feb 6 23:21:47 2011 +0100
        | |
        | |     CREATE FILE3
        | |
        * | commit 5dd659031b7cdac9a53e18388444b14875c223e0
        |/  Author:     Jérôme Tamarelle <jerome@tamarelle.net>
        |   AuthorDate: Sun Feb 6 23:21:47 2011 +0100
        |   Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
        |   CommitDate: Sun Feb 6 23:21:47 2011 +0100
        |
        |       MODIFY FILE1 AND CREATE FILE2
        |
        * commit f85f31bf14eea6d5e7a1bba0c7a72d6e9a21ab71
          Author:     Jérôme Tamarelle <jerome@tamarelle.net>
          AuthorDate: Sun Feb 6 23:21:47 2011 +0100
          Commit:     Jérôme Tamarelle <jerome@tamarelle.net>
          CommitDate: Sun Feb 6 23:21:47 2011 +0100

              CREATE FILE1

        ***********************************************************************/

        self::$repository = new Repository($dir, $debug, $options);
    }

    public static function tearDownAfterClass()
    {
        //exec('rm -fr '.\escapeshellarg(self::$repository->getDir()));
    }
}
