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
 * Class MigrateCommand
 * @package Dot\Migrations\Command
 */
class SeedCommand extends BaseCommand
{
    /**
     * @param Route $route
     * @param AdapterInterface $console
     *
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        // Fetch command arguments
        $matches = $route->getMatches();

        // Check for production, unless the --force flag is set
        if (empty($matches['force']) && $this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Let the user know that the command has started
        $console->writeLine('Seeding tables');

        // Prepare the command
        $command = $this->shellPath . ' seed:run ' .
            '-e ' . $this->env . ' ' .
            '-c ' . $this->configPath;

        if (! empty($matches['path'])) {
            $command .= ' -s "' . $matches['path'] . '"';
        };

        // Execute the command
        \exec($command, $this->output, $this->failure);

        if (! $this->failure) {
            // Let the user know that the tables has been seeded
            $console->writeLine('Finished seeding tables');
        } else {
            $console->writeLine('An error occurred, try again', ColorInterface::RED);
        }
        $console->writeLine('');

        return 0;
    }
}
