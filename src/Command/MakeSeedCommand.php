<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Zend\Console\ColorInterface;
use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class MakeCommand
 * @package Dot\Migrations\Command
 */
class MakeSeedCommand extends BaseCommand
{
    /**
     * @param Route $route
     * @param AdapterInterface $console
     *
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        // Make sure the user knows that they're in production
        if ($this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Fetch command arguments
        $matches = $route->getMatches();

        if (empty($matches['path'])) {
            $matches['path'] = 'data/database/seeds';
        }

        if (! \is_dir($matches['path'])) {
            $console->write('The entered path (', ColorInterface::RED);
            $console->write($matches['path'], ColorInterface::LIGHT_BLUE);
            $console->writeLine(') does not exist', ColorInterface::RED);

            return 0;
        }

        $command = $this->shellPath . ' seed:create ' .
            '-c ' . $this->configPath . ' ' .
            $matches['name'] . ' ' .
            '--path ' . $matches['path'];

        // Execute the Phinx command
        \exec(
            $command,
            $this->output,
            $this->failure
        );

        if (! $this->failure) {
            // Let the user know that the Seed has been created
            $console->write('Created seeder: ');
            $console->write($matches['name'].'.php', ColorInterface::LIGHT_BLUE);
            $console->write(', in directory: ');
            $console->writeLine($matches['path'], ColorInterface::LIGHT_BLUE);
        } else {
            $console->writeLine('An error occurred, try again', ColorInterface::RED);
        }
        $console->writeLine('');

        return 0;
    }
}
