<?php

namespace Herrera\Cli\Tests\Provider;

use Herrera\Cli\Provider\ErrorHandlingServiceProvider;
use Herrera\PHPUnit\TestCase;
use Herrera\Service\Container;

class ErrorHandlingServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();
        $container->register(new ErrorHandlingServiceProvider());

        $this->setExpectedException(
            'ErrorException',
            'Test error.'
        );

        trigger_error('Test error.', E_USER_ERROR);
    }
}