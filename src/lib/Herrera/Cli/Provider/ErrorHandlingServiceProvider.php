<?php

namespace Herrera\Cli\Provider;

use ErrorException;
use Herrera\Service\Container;
use Herrera\Service\ProviderInterface;

/**
 * Integrates error handling with the service container.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class ErrorHandlingServiceProvider implements ProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function register(Container $container)
    {
        set_error_handler(function (
            $code,
            $message,
            $file,
            $line
        ) use ($container){
            $container['error_handler']($code, $message, $file, $line);
        });

        $container['error_handler'] = $container->many(function (
            $code,
            $message,
            $file,
            $line
        ){
            throw new ErrorException($message, $code, 1, $file, $line);
        });
    }
}