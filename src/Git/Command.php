<?php

/*
 * This file is part of the GitCore for PHP5.3
 *
 * (c) Jérôme Tamarelle <jerome@tamarelle.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Git;

/**
 * File versioned in a Git repository.
 *
 * @author    Thibault Duplessis <thibault.duplessis at gmail dot com>
 *            Jérôme Tamarelle <jerome@tamarelle.net>
 */
use Git\Exception\GitRuntimeException;

class Command
{

    /**
     * @var string Real filesystem path of the repository
     */
    protected $dir;
    /**
     * @var string Git command to run
     */
    protected $commandString;
    /**
     * @var boolean Whether to enable debug mode or not
     * When debug mode is on, commands and their output are displayed
     */
    protected $debug;

    /**
     * Instanciate a new Git command
     *
     * @param   string $dir real filesystem path of the repository
     * @param   array $options
     */
    public function __construct($dir, $commandString, $debug)
    {
        $commandString = trim($commandString);

        $this->dir = $dir;
        $this->commandString = $commandString;
        $this->debug = $debug;
    }

    public function run()
    {
        $commandToRun = sprintf('cd %s && %s', escapeshellarg($this->dir), $this->commandString);

        if ($this->debug) {
            print $commandToRun."\n";
        }

        ob_start();
        passthru($commandToRun, $returnVar);
        $output = ob_get_clean();

        if ($this->debug) {
            print $output."\n";
        }

        if (0 !== $returnVar) {
            // Git 1.5.x returns 1 when running "git status"
            if (1 === $returnVar && 0 === strncmp($this->commandString, 'git status', 10)) {
                // it's ok
            } else {
                if (127 == $returnVar && empty($output)) { // Help for debugging
                    $output = 'Invalid Git executable';
                }
                var_dump($output);
                throw new GitRuntimeException(sprintf(
                    'Command %s failed with code %s: %s', $commandToRun, $returnVar, $output
                  ), $returnVar);
            }
        }

        return $output;
    }

}
