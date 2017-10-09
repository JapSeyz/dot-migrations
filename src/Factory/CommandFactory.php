<?php

declare(strict_types=1);

namespace Dot\Migrations\Factory;

use Interop\Container\ContainerInterface;

class CommandFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        // Get global config
        $config = $container->get('config');

        // Use the debug flag to set production env
        $isProduction = ! $config['debug'];

        // Return the command
        return new $requestedName($isProduction);
    }
}
