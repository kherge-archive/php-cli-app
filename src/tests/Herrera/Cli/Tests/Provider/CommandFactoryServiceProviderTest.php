<?php

namespace Herrera\Cli\Tests\Provider;

use Herrera\Cli\Provider\CommandFactoryServiceProvider;
use Herrera\PHPUnit\TestCase;
use Herrera\Service\Container;

class CommandFactoryServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Container();

        $container->register(new CommandFactoryServiceProvider());

        $callback = function () {};
        $command = $container['command_factory']('test', $callback);

        $this->assertInstanceOf(
            'Herrera\\Cli\\Command\\CallbackCommand',
            $command
        );
        $this->assertEquals('test', $command->getName());
        $this->assertSame(
            $callback,
            $this->getPropertyValue($command, 'callback')
        );
    }
}