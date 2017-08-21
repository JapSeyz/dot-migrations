<?php
/**
 * @see https://github.com/dotkernel/frontend/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/frontend/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

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

        // Execute the Phinx command
        \exec(
            $this->packagePath.'/vendor/bin/phinx seed:create '.
            '-c '.$this->rootPath.'/config/autoload/migrations.global.php '.
            $matches['name'],
            $this->output,
            $this->failure
        );

        if (! $this->failure) {
            // Let the user know that the Seed has been created
            $console->write('Created seeder '.$matches['name'].'.php');
        }

        return 0;
    }
}
