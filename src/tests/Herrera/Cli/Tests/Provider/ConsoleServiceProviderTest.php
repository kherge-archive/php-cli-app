<?php

namespace Herrera\Cli\Tests\Provider;

use Herrera\Cli\Application;
use Herrera\PHPUnit\TestCase;
use Symfony\Component\Console\Command\Command;

class ConsoleServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Application(
            array(
                'app.name' => 'Test!',
                'app.version' => '1.2.3'
            )
        );

        $this->assertInstanceOf(
            'Symfony\\Component\\Console\\Input\\ArgvInput',
            $container['console.input']
        );
        $this->assertInstanceOf(
            'Symfony\\Component\\Console\\Output\\ConsoleOutput',
            $container['console.output']
        );
        $this->assertInstanceOf(
            'Symfony\\Component\\Console\\Application',
            $container['console']
        );

        /** @var \Symfony\Component\Console\Application $console */
        $console = $container['console'];

        $this->assertEquals('Test!', $console->getName());
        $this->assertEquals('1.2.3', $console->getVersion());

        $callback = function () {
        };

        /** @var Command $command */
        $command = $container['console.command_factory']('test', $callback);

        $this->assertInstanceOf(
            'Symfony\\Component\\Console\\Command\\Command',
            $command
        );
        $this->assertEquals('test', $command->getName());
        $this->assertSame(
            $callback,
            $this->getPropertyValue($command, 'code')
        );
    }
}
