<?php
/**
 * @see https://github.com/dotkernel/frontend/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/frontend/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace Dot\Migrations\Command;

use Dot\Console\Command\AbstractCommand;
use Zend\Console\Prompt\Confirm;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class BaseCommand
 * @package Dot\Migrations\Command
 */
abstract class BaseCommand extends AbstractCommand
{
    protected $isProduction;
    protected $env;
    protected $shellPath;
    protected $rootPath;
    protected $output;
    protected $failure;
    protected $configPath;

    public function __construct(bool $isProduction)
    {
        $this->isProduction = $isProduction;
        $this->env = $this->isProduction ? 'production' : 'development';
        $this->shellPath = \dirname(__DIR__, 4).'/vendor/bin/phinx';
        $this->rootPath = \dirname(__DIR__, 5);
        $this->configPath = $this->rootPath.'/config/migrations.php';
        $this->output = [];
        $this->failure = 0;
    }

    /**
     * @param  AdapterInterface $console
     *
     * @return bool
     */
    protected function shouldAbortInProduction(AdapterInterface $console)
    {
        // Validate that the user knows what they're doing
        if ($this->isProduction) {
            if (! Confirm::prompt("You're in production, are you sure you want to run the command? [y/n] ")) {
                $console->write('Command aborted');

                return true;
            }
        }

        return false;
    }
}
