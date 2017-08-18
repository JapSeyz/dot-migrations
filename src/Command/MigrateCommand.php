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
class MigrateCommand extends AbstractCommand
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
        // Fetch command arguments
        $matches = $route->getMatches();

        // Check for production, unless the --force flag is set
        if (! $matches['force'] && $this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Let the user know that the migration is starting
        $console->write('Migrating tables');

        // Run the Phinx command
        \shell_exec($this->packagePath.'vendor/bin/phinx migrate '.
            '-c '.$this->rootPath.'config/autoload/migrations.global.php');

        // Let the user know that the migrations has successfully
        $console->write('Finised migrating tables');

        return 0;
    }
}
