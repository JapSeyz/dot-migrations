<?php

declare(strict_types=1);

namespace Dot\Migrations\Factory;

use Dot\Authentication\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ResetCommandFactory
{
    public function __invoke(ContainerInterface $container, $requestedName)
    {
        // Get global config
        $config = $container->get('config');

        // Use the debug flag to set production env
        $isProduction = ! $config['debug'];

        $adapter = $container->get('database');
        $database = $config['db']['adapters']['database']['database'];

        // Return the command
        return new $requestedName($isProduction, $adapter, $database);
    }
}
