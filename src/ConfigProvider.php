<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Migrations;

use Dot\Migrations\Command\MakeCommand;
use Dot\Migrations\Command\SeedCommand;
use Dot\Migrations\Command\ResetCommand;
use Dot\Migrations\Command\MigrateCommand;
use Dot\Migrations\Factory\CommandFactory;
use Dot\Migrations\Command\MakeSeedCommand;
use Dot\Migrations\Command\RollbackCommand;

/**
 * Class ConfigProvider
 * @package Dot\Migrations
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),

            'dot_console' => $this->getCommands(),
        ];
    }

    public function getDependencies()
    {
        return [
            'factories' => [
                MakeCommand::class => CommandFactory::class,
                MakeSeedCommand::class => CommandFactory::class,
                MigrateCommand::class => CommandFactory::class,
                ResetCommand::class => CommandFactory::class,
                RollbackCommand::class => CommandFactory::class,
                SeedCommand::class => CommandFactory::class,
            ],
        ];
    }

    public function getCommands()
    {
        return [
            'commands' => [
                [
                    'name' => 'make:migration <name> [path]',
                    'description' => 'Generate a migration',
                    'handler' => MakeCommand::class,
                ],
                [
                    'name' => 'migrate [--force]',
                    'description' => 'Run migrations',
                    'handler' => MigrateCommand::class,
                ],
                [
                    'name' => 'migrate:rollback',
                    'description' => 'Rollback to previous migration',
                    'handler' => RollbackCommand::class,
                ],
                [
                    'name' => 'migrate:reset',
                    'description' => 'Reset your database',
                    'handler' => ResetCommand::class,
                ],
                [
                    'name' => 'make:seed <name> [path]',
                    'description' => 'Generate a seeder',
                    'handler' => MakeSeedCommand::class,
                ],
                [
                    'name' => 'migrate:seed [name]',
                    'description' => 'Run seeders',
                    'handler' => SeedCommand::class,
                ],
            ],
        ];
    }
}
