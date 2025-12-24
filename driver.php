<?php

use Psr\Container\ContainerInterface;
use Kipchak\Driver\Logger\Driver;

/**
 * @var $container ContainerInterface
 */

Driver::initialise($container);
Driver::set();