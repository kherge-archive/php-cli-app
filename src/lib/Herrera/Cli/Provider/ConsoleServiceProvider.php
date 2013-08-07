<?php

namespace Herrera\Cli\Provider;

use Herrera\Service\Container;
use Herrera\Service\ProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Registers the Symfony Console application as a service.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ConsoleServiceProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container)
    {
        $container['console.defaults'] = $container->once(
            function (Container $container) {
                $defaults = array(
                    'app.name' => 'UNKNOWN',
                    'app.version' => 'UNKNOWN',
                    'console.auto_exit' => true,
                    'console.input.argv' => null,
                    'console.input.definition' => null,
                    'console.output.verbosity' => ConsoleOutput::VERBOSITY_NORMAL,
                    'console.output.decorated' => null,
                );

                foreach ($defaults as $key => $value) {
                    if (false === isset($container[$key])) {
                        $container[$key] = $value;
                    }
                }
            }
        );

        $container['console'] = $container->once(
            function (Container $container) {
                $container['console.defaults'];

                $console = new Application(
                    $container['app.name'],
                    $container['app.version']
                );

                $console->setAutoExit($container['console.auto_exit']);

                /** @var HelperInterface $container */
                $console->getHelperSet()->set($container);

                return $console;
            }
        );

        $container['console.command_factory'] = $container->many(
            function ($name, $callback) {
                $command = new Command($name);
                $command->setCode($callback);

                return $command;
            }
        );

        $container['console.input'] = $container->once(
            function (Container $container) {
                $container['console.defaults'];

                return new ArgvInput(
                    $container['console.input.argv'],
                    $container['console.input.definition']
                );
            }
        );

        $container['console.output'] = $container->once(
            function (Container $container) {
                $container['console.defaults'];

                return new ConsoleOutput(
                    $container['console.output.verbosity'],
                    $container['console.output.decorated'],
                    $container['console.output.formatter']
                );
            }
        );

        $container['console.output.formatter'] = $container->once(
            function () {
                return new OutputFormatter();
            }
        );

        $container['console.run'] = $container->many(
            function () use ($container) {
                return $container['console']->run(
                    $container['console.input'],
                    $container['console.output']
                );
            }
        );
    }
}
