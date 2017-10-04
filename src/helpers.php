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
    function config($path, $default = '')
    {
        $projectRoot = \dirname(__DIR__, 4);
        $parts = \explode('.', $path);
        $currentPath = $projectRoot;
        $configFile = false;
        foreach ($parts as $i => $part) {
            $currentPath .= '/'.\array_shift($parts);
            if (\is_dir($currentPath)) {
                continue;
            }
            if (\file_exists($currentPath.'.php')) {
                $configFile = $currentPath.'.php';

                break;
            }
        }
        if (! $configFile) {
            return $default;
        }

        try {
            $arr = include $configFile;
        } catch (\Exception $e) {
            return $default;
        }

        try {
            foreach ($parts as $part) {
                $arr = $arr[$part];
            }

            return $arr;
        } catch (\Exception $e) {
            return $default;
        }
    }
}
