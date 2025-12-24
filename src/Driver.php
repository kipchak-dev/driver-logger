<?php

namespace Kipchak\Driver\Logger;

use Kipchak\Core\Interfaces;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Kipchak\Driver\Config\Driver as Config;
Use Monolog\Logger;
use Monolog\Level;
Use Monolog\Handler\StreamHandler;

class Driver implements Interfaces\Driver
{
    protected static ContainerInterface $container;

    public static function initialise(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function set(): void
    {
        self::$container->set('drivers.logger', function(ContainerInterface $c): ?Logger {
            if (!empty(Config::get('kipchak.api'))) {
                $apiConfig = Config::get('kipchak.api');
                if (
                    isset($apiConfig['loglevel']) &&
                    in_array(
                        $apiConfig['loglevel'],
                        [
                            Level::Info,
                            Level::Debug,
                            Level::Warning,
                            Level::Alert,
                            Level::Critical,
                            Level::Emergency,
                            Level::Error
                        ])) {
                    $logLevel = $apiConfig['loglevel'];
                } else {
                    $logLevel = $apiConfig['debug'] === true ? Level::Debug : Level::Info;
                }

                $log = new Logger($apiConfig['name']);
                $log->pushHandler(new StreamHandler('php://stdout', $logLevel));

                return $log;
            }

            return null;
        });
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(?string $configKey = null): Logger
    {
        return self::$container->get('drivers.logger');
    }

}