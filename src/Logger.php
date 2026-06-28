<?php

namespace Kipchak\Driver\Logger;

use Kipchak\Core\Interfaces;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Kipchak\Driver\Config\Config;
Use Monolog\Logger as MonoLogger;
use Monolog\Level;
Use Monolog\Handler\StreamHandler;

class Logger implements Interfaces\Driver
{
    protected static ?ContainerInterface $container = null;
    protected static $apiConfig;

    public static function initialise(ContainerInterface $container, ?string $configFileName = null): void
    {
        self::$container = $container;
        self::set();
    }

    public static function set(): void
    {
        self::$container->set('drivers.logger', function(ContainerInterface $c): ?MonoLogger {
            if (!empty(Config::get('kipchak.api'))) {
                $logLevel = self::getLogLevel();
                $log = new MonoLogger(self::$apiConfig['name']);

                // Optional list of Monolog processors. Each entry is either a
                // fully-qualified class name (instantiated with no constructor
                // args) or an already-constructed processor instance/callable.
                // Bundled processors live under Kipchak\Driver\Logger\Processors\.
                foreach ((self::$apiConfig['logProcessors'] ?? []) as $processor) {
                    if (is_string($processor)) {
                        $processor = new $processor();
                    }
                    $log->pushProcessor($processor);
                }

                $log->pushHandler(new StreamHandler('php://stdout', $logLevel));

                return $log;
            }

            return null;
        });
    }

    /**
     * @param string|null $configKey
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function get(?string $configKey = null): MonoLogger
    {
        return self::$container->get('drivers.logger');
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