<?php
/**
 * @see https://github.com/dotkernel/dot-mail/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/dotkernel/dot-mail/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Migrations;

use Dot\Migrations\Command\SeedCommand;
use Dot\Migrations\Command\ResetCommand;
use Dot\Migrations\Command\CreateCommand;
use Dot\Migrations\Command\MigrateCommand;
use Dot\Migrations\Factory\CommandFactory;
use Dot\Migrations\Command\RollbackCommand;
use Dot\Migrations\Command\CreateSeedCommand;

/**
 * Class ConfigProvider
 * @package Dot\Mail
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
                CreateCommand::class => CommandFactory::class,
                CreateSeedCommand::class => CommandFactory::class,
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
                    'name' => 'create:migration <name>',
                    'description' => 'Generate a migration',
                    'handler' => CreateCommand::class,
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
                    'name' => 'create:seed <name>',
                    'description' => 'Generate a seeder',
                    'handler' => CreateSeedCommand::class,
                ],
                [
                    'name' => 'seed [name]',
                    'description' => 'Run seeders',
                    'handler' => SeedCommand::class,
                ],
            ],
        ];
    }
}
