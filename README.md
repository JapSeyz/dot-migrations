# dot-migrations

Add migrations and seeders to your DotKernel3 project.

### Usage

Some new commands as been added to the ```php dot``` command.

* make:migration <name>
    * Make a migration file, which will be used to create new tables and rows in the database
* make:seed <name>
    * Make a seeder, which will be used to seed the database with either test or default data.
* migrate [--force]
    * Migrate the missing migrations, use the --force in your deployment script to avoid the production environment warning.
* migrate:reset
    * Rollback all migrations and reset the database.
* migrate:rollback
    * Rollback the latest migration only.
* seed [name]
    * Run all seeders, or optionally provide a name, and only run that seeder

To run any of them simply run php dot <command>.
Dot kernel will take care of the rest, putting the files in the
right directory etc.
Settings can be found in the migrations.global.php.dist and should be configred properly
already, but the settings can be customised to fit your project.

The package also exposes a single method, the ```config()``` method, which allows you to
grab any config key/value, before it enters the global configuration, thus you don't need a
factory to grab it. This method play an important role i not duplicating config values.
