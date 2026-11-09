# HTTP Driver for Kipchak

This is a logging driver for Kipchak and is based on Monolog.

#### Composer Package 

`kipchak/driver-logger`

#### Sample Config File:

This driver requires the `kipchak.api.php` config file to be present in the config directory of your project.

Please see the sample config file at https://github.com/kipchak-dev/starter/blob/master/config/kipchak.api.php.

## How to use it?

Install it via composer: `composer require kipchak/driver-logger`.

### Example Usage

```php
// Load the HTTP driver.
$logger = Kipchak\Driver\Logger\Logger::get();
// Use $logger like Monolog's logger.
```

### Registering Monolog processors

Add an optional `logProcessors` array to your `kipchak.api.php` config. Each
entry can be a fully-qualified class name (instantiated with no constructor
args) or an already-constructed processor instance / callable. Processors
listed here are pushed onto the Monolog logger before any handler runs.

```php
return [
    'name' => 'my-api',
    // ...
    'logProcessors' => [
        \App\Logger\Processors\MyProcessor::class,
    ],
];
```

The driver does not bundle any processors of its own — bring your own.

## What is a Kipchak Driver?

Kipchak Drivers are used to connect Kipchak to various data sources or storage systems.

They provide a standardized interface for interacting with different data sources, allowing developers to
focus on building their applications rather than dealing with the complexities of each data source.

Drivers were introduced as a part of the Kipchak 2.0 release.

Drivers are basically Container Dependencies injected into Kipchak's Service Container.

## How do Kipchak Drivers work?

Some Kipchak drivers are wired into the Service Container via a config file (where required).

If applicable, you will find a sample config file in this repository as well as in the starter project at https://1x.ax/mamluk/kipchak/starter.

Each driver defines an implementation of Kipchak's Driver Interface. Where applicable, the dependency may
accept a parameter for a specific instance of the driver specified as a config property, for instance, in the case
of multiple database connections or S3 buckets.

So you may access the driver by invoking \Mamluk\Kipchak\Driver\Memcached\Memcached::get('cache'), where
`cache` is the name of one of the memcached connection pools specified in the config file.