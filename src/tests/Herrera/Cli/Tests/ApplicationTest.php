<?php

namespace Herrera\Cli\Tests;

use Herrera\Cli\Application;
use Herrera\PHPUnit\TestCase;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class ApplicationTest extends TestCase
{
    private $app;

    public function testAdd()
    {
        $this->assertSame(
            $this->app->add('test', function () {}),
            $this->app['console']->find('test')
        );
    }

    public function testGetHelperSet()
    {
        $this->assertSame(
            $this->app['console']->getHelperSet(),
            $this->app->getHelperSet()
        );
    }

    public function testRun()
    {
        $input = new ArrayInput(array('test'));
        $output = new NullOutput();

        $this->app['console.auto_exit'] = false;
        $this->app['console.input'] = $input;
        $this->app['console.output'] = $output;

        $result_input = null;
        $result_output = null;

        $this->app->add('test', function (
            $input, $output
        ) use (
            &$result_input, &$result_output
        ){
            $result_input = $input;
            $result_output = $output;
        });

        $this->app->run();

        $this->assertSame($input, $result_input);
        $this->assertSame($output, $result_output);
    }

    public function testSet()
    {
        $helper = new TestHelper();

        $this->app->set($helper);

        $this->assertSame(
            $helper,
            $this->app['console']->getHelperSet()->get('test')
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
        $this->app = new Application('Test', '1.2.3');
    }
}

class TestHelper extends Helper
{
    public function getName()
    {
        return 'test';
    }
}