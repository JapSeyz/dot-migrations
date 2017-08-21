<?php
/**
 * @see https://github.com/dotkernel/frontend/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/frontend/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Dot\AnnotatedServices\Annotation\Service;
use Zend\Console\Adapter\AdapterInterface;
use ZF\Console\Route;

/**
 * Class RollbackCommand
 * @package Dot\Migrations\Command
 *
 * @Service
 */
class RollbackCommand extends BaseCommand
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

        // Let the user know that the roll back has started
        $console->write('Rolling back to previous batch');

        // Run the Phinx command
        \exec(
            $this->packagePath.'/vendor/bin/phinx rollback '.
            '-e '.$this->env.' '.
            '-c '.$this->rootPath.'/config/autoload/migrations.global.php',
            $this->output,
            $this->failure
        );

        if (! $this->failure) {
            // Let the user know that the roll back has completed
            $console->write('Finished rolling back tables');
        }

        return 0;
    }
}
