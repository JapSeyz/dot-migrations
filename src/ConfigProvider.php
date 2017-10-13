<?php
/**
 * @see https://github.com/japseyz/dot-migrations/ for the canonical source repository
 * @copyright Copyright (c) 2017 Apidemia (https://www.apidemia.com)
 * @license https://github.com/japseyz/dot-migrations/blob/master/LICENSE.md MIT License
 */

declare(strict_types = 1);

namespace Dot\Migrations;

use Dot\Migrations\Command\GodCommand;
use Dot\Migrations\Command\MakeCommand;
use Dot\Migrations\Command\SeedCommand;
use Dot\Migrations\Command\ResetCommand;
use Dot\Migrations\Command\MigrateCommand;
use Dot\Migrations\Factory\CommandFactory;
use Dot\Migrations\Command\MakeSeedCommand;
use Dot\Migrations\Command\RollbackCommand;
use Dot\Migrations\Factory\ResetCommandFactory;

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
                ResetCommand::class => ResetCommandFactory::class,
                RollbackCommand::class => CommandFactory::class,
                SeedCommand::class => CommandFactory::class,
                GodCommand::class => CommandFactory::class,
            ],
        ];
    }

    public function getCommands()
    {
        return [
            'commands' => [
                [
                    'name' => 'make:migration',
                    'route' => '<name> [<path>]',
                    'short_description' => 'Generate a new migration',
                    'description' => 'Add a new migration class to the specified path.'.PHP_EOL,
                    'options_descriptions' => [
                        'name' => 'CamelCased name to give to the migration class.',
                        'path' => 'The location to save the migration at, it must be provided in the config-file. Defaults to data/database/migrations.',
                    ],
                    'handler' => MakeCommand::class,
                ],
                [
                    'name' => 'migrate',
                    'route' => '[--force|-f]:force',
                    'short_description' => 'Run all remaining migrations',
                    'description' => 'Migrates the database with every migration that has not been previously run.'.PHP_EOL,
                    'options_descriptions' => [
                        '--force' => 'Disables the environment-check when in production.',
                    ],
                    'handler' => MigrateCommand::class,
                ],
                [
                    'name' => 'migrate:rollback',
                    'short_description' => 'Rollback the latest batch of migrations',
                    'description' => 'Rolls back all the migrations that was migrated with the latest migrate command.'.PHP_EOL,
                    'handler' => RollbackCommand::class,
                ],
                [
                    'name' => 'migrate:reset',
                    'route' => '[--hard|-h]:hard [--force|-f]:force',
                    'short_description' => 'Rolls back every single migration that has been run',
                    'description' => 'Rollback every single migration, or recreates the schema if -h is provided.'.PHP_EOL,
                    'options_descriptions' => [
                        '--hard' => 'Drops and recreates the schema instead of rolling back every migration.',
                        '--force' => 'Disables the environment-check when in production.',
                    ],
                    'handler' => ResetCommand::class,
                ],
                [
                    'name' => 'make:seed',
                    'route' => '<name> [<path>]',
                    'short_description' => 'Generate a new seeder',
                    'description' => 'Add a new seeder class to the specified path.'.PHP_EOL,
                    'options_descriptions' => [
                        'name' => 'CamelCased name to give to the seeder class.',
                        'path' => 'The location to save the seeder at, it must be provided in the config-file. Defaults to data/database/seeds.',
                    ],
                    'handler' => MakeSeedCommand::class,
                ],
                [
                    'name' => 'migrate:seed',
                    'route' => '[<path>] [--force|-f]:force',
                    'short_description' => 'Run database seeders',
                    'description' => 'Runs all database seeders, even if they have already run once, optionally runs only a single seeder.'.PHP_EOL,
                    'options_descriptions' => [
                        'path' => 'The FQCN of the seeder to run, please escape it like with double-quotes like so: "Data\Database\Seeder\UserTableSeeder".',
                        '--force' => 'Disables the environment-check when in production,',
                    ],
                    'handler' => SeedCommand::class,
                ],
                [
                    'name' => 'migrate:god',
                    'short_description' => 'Reset, re-migrate and re-seed the database',
                    'description' => 'Only intended for development purposes, the GOD command hard-resets, re-migrates and re-seeds the database.'.PHP_EOL,
                    'handler' => GodCommand::class,
                ],
            ],
        ];
    }
}
