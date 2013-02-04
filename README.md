CLI App
=======

[![Build Status](https://travis-ci.org/herrera-io/php-cli-app.png?branch=master)](https://travis-ci.org/herrera-io/php-cli-app)

A simplified CLI application template built on [Symfony Console](http://symfony.com/doc/current/components/console/index.html).

Summary
-------

CLI App is a template (or a framework, if you prefer) for creating command line applications. The template is built on the [Herrera.io service container](https://github.com/herrera-io/php-service-container) and Symfony's Console component. Any service registered with the service container will be available to any command added to the application. To see an example as to why this is simplified, please see the **Usage** section for more information.

Installation
------------

Add it to your list of Composer dependencies:

```sh
$ composer require herrera-io/cli-app=1.*
```

Usage
-----

```php
<?php

use Herrera\Cli\Application;

$app = new Application('MyApp', '1.2.3');

$app->add('myCommand', function ($input, $output) use ($app) {
    $output->writeln('Hello, ' . $input->getArgument('name') . '!');

    return 123;
})->addArgument('name');

$app->run();
```

Running it:

```sh
$ php myApp.php myCommand Guest
Hello, Guest!
```
