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
use ZF\Console\Route;

/**
 * Class RollbackCommand
 * @package Dot\Migrations\Command
 */
class RollbackCommand extends BaseCommand
{
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

        // Let the user know that the roll back has started
        $console->writeLine('Rolling back to previous batch');

        // Run the Phinx command
        \exec(
            $this->shellPath . ' rollback ' .
            '-e ' . $this->env . ' ' .
            '-c ' . $this->configPath . ' ',
            $this->output,
            $this->failure
        );

        if (! $this->failure) {
            // Let the user know that the roll back has completed
            $console->writeLine('Finished rolling back tables');
        } else {
            $console->writeLine('An error occurred, try again', ColorInterface::RED);
        }
        $console->writeLine('');

        return 0;
    }
}
