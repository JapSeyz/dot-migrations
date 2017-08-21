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
use Zend\Console\Prompt\Confirm;
use Dot\AnnotatedServices\Annotation\Inject;
use Dot\Migrations\Factory\CommandFactory;
use Zend\Console\Adapter\AdapterInterface;

/**
 * Class BaseCommand
 * @package Dot\Migrations\Command
 *
 * @Service
 */
abstract class BaseCommand extends AbstractCommand
{
    protected $isProduction;
    protected $env;
    protected $packagePath;
    protected $rootPath;
    protected $output;
    protected $failure;

    /**
     *
     * @inject({CommandFactory::Class})
     */
    public function __construct(bool $isProduction)
    {
        $this->isProduction = $isProduction;
        $this->env = $this->isProduction ? 'production' : 'development';
        $this->packagePath = \dirname(__DIR__, 2);
        $this->rootPath = \dirname(__DIR__, 5);
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
