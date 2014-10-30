Command
=======

[![Build Status](https://travis-ci.org/php-ddd/command.svg)](https://travis-ci.org/php-ddd/command)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/php-ddd/command/badges/quality-score.png](https://scrutinizer-ci.com/g/php-ddd/command/)

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