<?php

namespace Herrera\Cli\Provider;

use Herrera\Service\Container;
use Symfony\Component\Console\Command\Command;
use Herrera\Service\ProviderInterface;

/**
 * Adds a callback command factory to the service container.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CommandFactoryServiceProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container)
    {
        $container['command_factory'] = $container->many(function (
            $name,
            $callback
        ){
            $command = new Command($name);
            $command->setCode($callback);

            return $command;
        });
    }
}