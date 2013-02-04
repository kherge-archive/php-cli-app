<?php

namespace Herrera\Cli\Tests\Provider;

use Herrera\Cli\Application;
use Herrera\Cli\Provider\ConsoleServiceProvider;
use Herrera\PHPUnit\TestCase;
use Herrera\Service\Container;

class ConsoleServiceProviderTest extends TestCase
{
    public function testRegister()
    {
        $container = new Application('Test!', '1.2.3');

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
        $this->assertEquals('Test!', $container['console']->getName());
        $this->assertEquals('1.2.3', $container['console']->getVersion());
    }
}