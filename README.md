# HTTP Driver for Kipchak

This driver is a Config management driver for Kipchak.

It is bundled into Kipchak and does not need to be installed separately.

#### Composer Package 

`kipchak/driver-config`

#### Sample Config File:

This driver does not require any configuration.

## How to use it?

Install it via composer: `composer require kipchak/driver-config`.

### Example Usage

```php
// Load the HTTP driver.
$http = \Mamluk\Kipchak\Driver\Config\Driver::get('file_name_without_extension')
// Use the HTTP client ($http) client as you normally would.
```

## What is a Kipchak Driver?

Kipchak Drivers are used to connect Kipchak to various data sources or storage systems. 

They provide a standardized interface for interacting with different data sources, allowing developers to 
focus on building their applications rather than dealing with the complexities of each data source.

Drivers were introduced as a part of the Kipchak 2.0 release.

Drivers are basically Container Dependencies injected into Kipchak's Service Container.

## How do Kipchak Drivers work?

Kipchak drivers are wired into the Service Container via a config file (where required).

Each driver defines an implementation of Kipchak's Driver Interface. 
Where applicable, the dependency may accept a parameter for a specific instance of the driver specified as a onfig property, 
for instance, in the case of multiple database connections or S3 buckets.

So you may access the driver by invoking \Mamluk\Kipchak\Driver\Http\Driver::get('default'), where 
`default` is the name of the connection specified in the config file.