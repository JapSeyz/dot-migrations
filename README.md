# dot-migrations

Add migrations and seeders to your DotKernel3 project.

### Usage

Some new commands as been added to the `php dot` command.

* make:migration <**name**> [**path**]
    * Make a migration file, which will be used to create new tables and rows in the database
    * The path is used to pre-fill the namespace, and has to match a path in the config file.
    * **data/database/migrations** is the default path if none are supplied
* make:seed <**name**> [**path**]
    * Make a seeder, which will be used to seed the database with data.
    * The path is optional and will default to **data/database/seeds**.
    * When supplying your own path, it must match a path from the config file.
* migrate [**--force|-f**]
    * Migrate the missing migrations, use the --force in your deployment script to avoid the production environment warning.
* migrate:reset [**--force|-f**] [**--hard|-h**]
    * Rollback all migrations and reset the database.
    * Supplying **--force** will prevent the environment warning in production.
    * Supplying the **--hard** flag will drop and re-create the entire schema.
* migrate:rollback
    * Rollback a single batch of migrations only.
* migrate:seed [**path**] [**--force|-f**]
    * Run all seeders, or optionally provide a path to a specific seeder to run.
    * If a path is provided, please escape it with double-quotes like "Data\Database\Seeder\UserTableSeeder"
    * Supplying the **--force** flag will prevent the environment warning in production.
* migrate:god
    * The God command is intended for development and will 
    recreate the schema, re-migrate and re-seed the database.

To run any of them simply run `php dot <command>`.
DotKernel will take care of the rest, putting the files in the
correct directories etc.
Settings can be found in the `migrations.php.dist`.

### Installation

1) Installing is extremely easy, all you have to do is run `composer require japseyz/dot-migrations`, and copy the `migrations.php.dist` file to the `/config` folder and remove the .dist ending.

2) After that is done, you open up `/config/config.php` and add `\Dot\Migrations\ConfigProvider::class,` to the `$aggregator` array.

3) Create a folder inside `data` named `database`, and inside this, create two folders; `migrations` and `seeds`, this is where your migrations and seeds will go.

4) That's it, all you have to do now is run `composer dump-autoload` and enjoy access to migrations and seeders, all you have to do is run `php dot`


### Troubleshooting

##### The migration commands does not show up, what do I do?
If you've follow the installation, but no commands show up, try deleting `/data/config-cache.php` and running `php dot` again.
