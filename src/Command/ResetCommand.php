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
 * Class ResetCommand
 * @package Dot\Migrations\Command
 *
 * @Service
 */
class ResetCommand extends AbstractCommand
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
        // Check for production
        if ($this->shouldAbortInProduction($console)) {
            return 0;
        }

        // Let the user know that the command has started
        $console->write('Resetting tables');

        // Run the Phinx command
        \shell_exec($this->packagePath.'vendor/bin/phinx rollback -t 0 -f -e '.
            '-c '.$this->rootPath.'config/autoload/migrations.global.php');

        // Let the user know that the command has finished
        $console->write('Finished resetting tables');

        return 0;
    }
}
