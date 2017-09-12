<?php

declare(strict_types=1);

// Expose a config() function,
// that will get a config value without a factory
// for classless config files.
if (! \function_exists('config')) {
    // $path is a dot-notation file and array-key path
    // which will automatically resolve where the file
    // is located and the array begins.
    //
    // app.config.user.name
    // could find user['name'] in app/config.php
    //
    // The second is the default value
    // if no config value is found.
    function config($path, $default)
    {
        return \Dot\Migrations\ConfigHelper::get($path, $default);
    }
}
