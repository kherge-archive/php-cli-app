CLI App
=======

[![Build Status][]](https://travis-ci.org/herrera-io/php-cli-app)

CLI App is a template for creating console applications based on Symfony
[Console][] and the Herrera.io [Service Container][]. Think Silex, but for
console applications, and easier.

Example
-------

```php
use Herrera\Cli\Application;

$app = new Application(
    array(
        'app.name' => 'MyApp',
        'app.version' => '1.2.3',
    )
);

$myCommand = $app->add(
    'myCommand',
    function ($in, $out) {
        $out->writeln('Hello, ' . $in->getArgument('name') . '!');

        return 123;
    }
);

$myCommand->addArgument('name');

$app->run();
```

Running the example:

```
$ php myApp.php myCommand world
Hello, world!
```

Installation
------------

Add it to your list of Composer dependencies:

```sh
$ composer require "herrera-io/cli-app=~2.0"
```

Usage
-----

Creating a new application is as simple as instantiating the `Application`
class. The class itself is an extension of the `Container` class from the
Herrera.io service container library.

```php
use Herrera\Cli\Application;

$app = new Application(
    array(
        'app.name' => 'Example',
        'app.version' => '1.0',
    )
);
```

> The purpose of `app.name` and `app.version` will be later discussed in the
> section titled **Configuration**. It is one of many customizable options for
> the application.

### Default Services

When the application is instantiated two services are registered:

- `Herrera\Cli\Provider\ErrorHandlingServiceProvider` &mdash; Replaces the
  current error handler with one provided by the service. The custom error
  handler will simply convert all errors into instances of `ErrorException`
  and throw them. The handler will respect the current `error_reporting()`
  setting.
- `Herrera\Cli\Provider\ConsoleServiceProvider` &mdash; The console service
  provider that is used by the application to configure, add commands, add
  helpers, and run.

The `Application` class is designed so that you can replace the default
registered services by overriding a single method. You can also extend
the method to register additional default services.

```php
class CustomApplication extends Application
{
    /**
     * @override
     */
    protected function registerDefaultServices()
    {
        parent::registerDefaultServices();

        $this->register(new Service());
    }
}
```

### Adding a Command

To add a new command to the application, you will need to call the `add()`
method. This method will create a new command and return it for further,
optional, configuration. The command returned is an instance of the
`Symfony\Component\Console\Command\Command` class.

```php
$command = $app->add(
    'commandName',
    function ($in, $out) {
        // command code
    }
);

$command->addArgument('argumentName');
```

### Adding a Helper

To add a helper to the application, you will need to call the `set()` method.
This method will register the helper with the current helper set. Any instance
of `Symfony\Component\Console\Helper\HelperInterface` is accepted.

```php
$app->set(new Helper());
```

### App Container as Helper

The `Application` container is registered as a helper in the console instance.
This will make it easier to access the container in order to use other services
within a command that extends the `Command` class.

```php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomCommand extends Command
{
    protected function configure()
    {
        $this->setName('customCommand');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $app = $this->getHelperSet()->get('app');
    }
}
```

### Running

Running the application is simple:

```php
$status = $app->run();
```

If auto exiting is disabled, `$status` will hold the exit status code.

### Configuration

The majority of the all available configuration parameters and servics lie
within the `ConsoleServiceProvider` that is registered with the `Application`
service container. The default parameters and services can be modified until
the `console` service is used. Any further changes will not take any effect.

These are the default console parameters:

```php
use Symfony\Component\Console\Output\ConsoleOutput;

array(
    // the name of the application
    'app.name' => 'UNKNOWN',

    // the version of the application
    'app.version' => 'UNKNOWN',

    // automatically exit once the app has run?
    'console.auto_exist' => true,

    // the overriding list of $_SERVER['argv'] values
    'console.input.argv' => null,

    // the default array of input definitions
    'console.input.definition' => null,

    // the default verbosity level
    'console.output.verbosity' => ConsoleOutput::VERBOSITY_NORMAL,

    // the default "use decorator" flag
    'console.output.decorator' => null,
)
```

These are the default console services:

```php
// the Symfony `Console` instance
$app['console'];

// creates new `Command` instances
$app['console.command_factory'];

// the Symfony `ArgvInput` instance
$app['console.input'];

// the Symfony `ConsoleOutput` instance
$app['console.output'];

// the Symfony `OutputFormatter` instance
$app['console.output.formatter'];

// runs `Application->run()` with input and output services
$app['console.run'];
```

[Build Status]: https://travis-ci.org/herrera-io/php-cli-app.png?branch=master
[Console]: http://symfony.com/doc/current/components/console/index.html
[Service Container]: https://github.com/herrera-io/php-service-container
