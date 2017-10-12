<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\ColorInterface;
use Zend\Db\Adapter\Adapter;
use ZF\Console\Route;

/**
 * Class ResetCommand
 * @package Dot\Migrations\Command
 */
class ResetCommand extends BaseCommand
{
    protected $adapter;
    protected $database;

    public function __construct(bool $isProduction, Adapter $adapter, string $database)
    {
        parent::__construct($isProduction);

        $this->adapter = $adapter;
        $this->database = $database;
    }

    /**
     * @param Route $route
     * @param AdapterInterface $console
     *
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        // Check for production
        if ($this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Fetch command arguments
        $matches = $route->getMatches();
        $hard = ! empty($matches['hard']);

        // Let the user know that the command has started
        if ($hard) {
            $console->writeLine('Hard-resetting tables; Dumping and recreating the schema');
            $this->hardReset();
        } else {
            $console->writeLine('Rolling back all migrations');
            $this->softReset();
        }

        if (! $this->failure) {
            // Let the user know that the command has finished
            $console->writeLine('Finished resetting tables');
        } else {
            if (! is_array($this->output)) {
                $console->writeLine('');
                $console->writeLine($this->output, ColorInterface::RED);
                $console->writeLine();
            }
            $console->writeLine('An error occurred, try again', ColorInterface::RED);
        }
        $console->writeLine('');

        return 0;
    }

    protected function hardReset()
    {
        // Drop and recreate the schema
        try {
            $this->adapter->query('DROP DATABASE ' . $this->database)->execute();
            $this->adapter->query('CREATE DATABASE ' . $this->database)->execute();
        } catch (\Exception $e) {
            $this->output = $e->getMessage();
            $this->failure = true;
        }
    }

    protected function softReset()
    {
        // Run the Phinx command
        \exec(
            $this->shellPath . ' rollback -t 0 -f ' .
            '-e ' . $this->env . ' ' .
            '-c ' . $this->configPath . ' ',
            $this->output,
            $this->failure
        );
    }
}
