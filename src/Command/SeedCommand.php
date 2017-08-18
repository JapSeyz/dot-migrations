<?php
/**
 * @see https://github.com/dotkernel/frontend/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/frontend/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Dot\AnnotatedServices\Annotation\Service;
use Dot\Console\Command\AbstractCommand;
use Zend\Console\Adapter\AdapterInterface;
use ZF\Console\Route;

/**
 * Class MigrateCommand
 * @package Dot\Migrations\Command
 *
 * @Service
 */
class SeedCommand extends AbstractCommand
{
    public function __construct(bool $isProduction)
    {
        parent::__construct($isProduction);
    }

    /**
     * @param Route $route
     * @param AdapterInterface $console
     * @return int
     */
    public function __invoke(Route $route, AdapterInterface $console)
    {
        // Check for production envrionment
        if ($this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Get command arguments
        $matches = $route->getMatches();

        // Let the user know that the command has started
        $console->write('Seeding tables');

        // Prepare the command
        $command = $this->packagePath.'vendor/bin/phinx migrate '.
            '-c '.$this->rootPath.'config/autoload/migrations.global.php '.
            $matches['name'];

        // Check whether or not to seed all or a single seeder
        if (\array_key_exists('name', $matches)) {
            $command .= ' -s '.$matches['name'];
        }

        // Execute the command
        \shell_exec($command);

        // Let the user know that the tables has been seeded
        $console->write('Finished seeding tables');

        return 0;
    }
}