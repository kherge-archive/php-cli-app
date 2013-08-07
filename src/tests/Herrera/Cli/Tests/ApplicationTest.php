<?php

namespace Herrera\Cli\Tests;

use Herrera\Cli\Application;
use Herrera\Cli\Tests\Test\TestHelper;
use Herrera\PHPUnit\TestCase;
use Symfony\Component\Console\Application as Console;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ApplicationTest extends TestCase
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Console
     */
    private $console;

    public function testAdd()
    {
        $this->assertSame(
            $this->app->add(
                'test',
                function () {
                }
            ),
            $this->console->find('test')
        );
    }

    public function testGetHelperSet()
    {
        $this->assertSame(
            $this->console->getHelperSet(),
            $this->app->getHelperSet()
        );
    }

    public function testRun()
    {
        $actual = array(
            'input' => null,
            'output' => null,
        );

        $expected = array(
            'input' => new ArrayInput(array('test')),
            'output' => new NullOutput(),
        );

        $this->app['console.input'] = $expected['input'];
        $this->app['console.output'] = $expected['output'];

        $this->app->add(
            'test',
            function ($input, $output) use (&$actual) {
                $actual['input'] = $input;
                $actual['output'] = $output;
            }
        );

        $this->app->run();

        $this->assertSame($expected, $actual);
    }

    public function testSet()
    {
        $helper = new TestHelper();

        $this->app->set($helper);

        $this->assertSame(
            $helper,
            $this->console->getHelperSet()->get('test')
        );
    }

    public function testSetHelperSet()
    {
        $set = new HelperSet();

        $this->app->setHelperSet($set);

        $this->assertSame($set, $this->app->getHelperSet());
    }

    protected function setUp()
    {
        $this->app = new Application(
            array(
                'app.name' => 'Test',
                'app.version' => '1.2.3',
                'console.auto_exit' => false,
            )
        );

        $this->console = $this->app['console'];
    }
}
