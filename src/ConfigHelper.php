<?php

declare(strict_types=1);
namespace Dot\Migrations;


class ConfigHelper
{
    private static $currentFile;

    public static function get($path, $default){
        // Fetch the project root, outside of the Vendor directory
        $projectRoot = \dirname(__DIR__, 4);

        // Explode the path-parts
        $parts = \explode('.', $path);

        // Start off looking at the project-root
        $currentPath = $projectRoot;

        // No config file found, yet
        $configFile = false;

        // Look over each part to see if it's a directory or file
        foreach ($parts as $i => $part) {
            $currentPath .= '/'.\array_shift($parts);

            // If it's a directory, keep iterating inwards
            if (\is_dir($currentPath)) {
                continue;
            }

            // If it's a file, we've found or config-file
            // Store the file location and break the loop
            if (\file_exists($currentPath.'.php')) {
                $configFile = $currentPath.'.php';

                break;
            }
        }

        // Validate that a config-file was found
        if (! $configFile) {
            return $default;
        }

        // Make sure we do not create an infinite loop
        // by loading the same file we're already in
        if(self::$currentFile === $configFile){
            return null;
        }
        self::$currentFile = $configFile;

        // Load the config file
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
