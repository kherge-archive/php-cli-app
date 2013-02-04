<?php

namespace Herrera\Cli\Tests\Command;

use Herrera\Cli\Command\CallbackCommand;
use Herrera\PHPUnit\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class CallbackCommandTest extends TestCase
{
    public function testExecute()
    {
        $input = new ArrayInput(array());
        $output = new NullOutput();

        $result_input = null;
        $result_output = null;

        $command = new CallbackCommand('test', function (
            $input, $output
        ) use (
            &$result_input, &$result_output
        ){
            $result_input = $input;
            $result_output = $output;
        });

        $command->run($input, $output);

        $this->assertSame($input, $result_input);
        $this->assertSame($output, $result_output);
    }
}