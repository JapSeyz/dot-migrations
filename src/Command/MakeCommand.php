<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use ZF\Console\Route;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class MakeCommand
 * @package Dot\Migrations\Command
 */
class MakeCommand extends BaseCommand
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

        if(!$matches['path']){
        	$matches['path'] = 'data/database/migrations';
        }

        if ( ! \is_dir($matches['path'])) {
            $console->writeLine('The entered path does not exist');
            return 0;
        }

        // Execute the Phinx command
        $command = $this->shellPath . ' create ' .
            '-c ' . $this->configPath . ' ' .
            $matches['name'] . ' ' .
            '--path ' . $matches['path'];

        \exec(
            $command,
            $this->output,
            $this->failure
        );

        if ( ! $this->failure) {
            $name = \strtolower(\preg_replace('/(?<!^)[A-Z]/', '_$0', $matches['name']));
            // Let the user know that the Migration has been created
            $console->writeLine('Created Migration: ' . \date('YmdHis') . '_' . $name . '.php');
        }

        return 0;
    }
}
