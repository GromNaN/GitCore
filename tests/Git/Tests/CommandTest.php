<?php

namespace Git\Tests;

require_once __DIR__.'/TestCase.php';

use Git\Command;

class CommandTest extends TestCase
{
    /**
     * @expectedException Git\Exception\GitRuntimeException
     */
    public function testInvalidExecutable()
    {
        $command = new Command(sys_get_temp_dir(), '/bin/wtf', false);
        $command->run('status');
    }
}

