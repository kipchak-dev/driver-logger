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
    protected static ?ContainerInterface $container = null;
    protected static $apiConfig;

    public static function initialise(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    public static function set(): void
    {
        self::$container->set('drivers.logger', function(ContainerInterface $c): ?Logger {
            if (!empty(Config::get('kipchak.api'))) {
                $logLevel = self::getLogLevel();
                $log = new Logger(self::$apiConfig['name']);
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
        if(self::$container !== null &&self::$container->has('drivers.logger') && $configKey === null){
            return self::$container->get('drivers.logger');
        }
        $name = $configKey ?? 'kipchak';
        $log = new Logger($name);
        $log->pushHandler(new StreamHandler('php://stdout', self::getLogLevel()));

        return $log;
    }

    protected static function loadConfig(): void
    {
        self::$apiConfig = Config::get('kipchak.api');
    }

    protected static function getLogLevel(): Level
    {
        self::loadConfig();
        if (
            isset(self::$apiConfig['loglevel']) &&
            in_array(
                self::$apiConfig['loglevel'],
                [
                    Level::Info,
                    Level::Debug,
                    Level::Warning,
                    Level::Alert,
                    Level::Critical,
                    Level::Emergency,
                    Level::Error
                ])) {
            $logLevel = self::$apiConfig['loglevel'];
        } else {
            $logLevel = self::$apiConfig['debug'] === true ? Level::Debug : Level::Info;
        }

        return $logLevel;
    }

}