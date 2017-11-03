<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Dot\Migrations\Factory\ResetCommandFactory;
use Zend\Console\ColorInterface;
use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class GodCommand
 * @package Dot\Migrations\Command
 */
class GodCommand extends BaseCommand
{
    /**
     * GodCommand constructor.
     *
     * @param bool $isProduction
     */
    public function __construct($isProduction)
    {
        parent::__construct($isProduction);
    }

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

        $console->writeLine('Hard-resetting database');
        exec('php dot migrate:reset -h -f');

        $console->writeLine('Migrating database');
        exec('php dot migrate -f');

        $console->writeLine('Seeding database');
        exec('php dot migrate:seed -f');

        $console->writeLine('');
        $console->writeLine('The God-command has finished', ColorInterface::LIGHT_BLUE);
        $console->writeLine('');
    }
}
