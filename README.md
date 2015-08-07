Command [![Build Status](https://travis-ci.org/php-ddd/command.svg)](https://travis-ci.org/php-ddd/command) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-ddd/command/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/php-ddd/command/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/php-ddd/command/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/php-ddd/command/?branch=master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/993051dc-280f-4c82-91a4-1e9bcf6a12c4/mini.png)](https://insight.sensiolabs.com/projects/993051dc-280f-4c82-91a4-1e9bcf6a12c4)
=======

This library provides some useful tools in order to create a simple command system.

How it works
------------

```php
// configuration
$handler = new AddItemToChartCommandHandler();
$locator = new CommandHandlerLocator();
$locator->register('AddItemToChartCommand', $handler);

$bus = new SequentialCommandBus($locator);

// usage
$command = new AddItemToChartCommand($item, $chart);
$bus->dispatch($command); // internally, the bus will call the corresponding handler.
```

Conventions
-----------

We want to follow the Single Responsibility principle. Hence:
* A `CommandHandler` can only handle one `CommandInterface`
* A `CommandBus` will only dispatch some `CommandInterface` (and nothing more)
* A `CommandHandlerLocator` is responsible of registering associations between `Command` and `CommandHandler`

It allows us to force some other conventions like the name of the `CommandHandler` class that needs to match the
name of the `Command` it handle. E.g: `AddItemToChartCommand` will be handled by a `AddItemToChartCommandHandler` object.
