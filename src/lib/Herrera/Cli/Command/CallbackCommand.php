<?php

namespace Herrera\Cli\Command;

use Closure;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Allows a callback to be used as a command.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class CallbackCommand extends Command
{
    /**
     * The callback.
     *
     * @var callable
     */
    private $callback;

    /**
     * Sets the command name and callback.
     *
     * @param string   $name     The name.
     * @param callable $callback The callback.
     */
    public function __construct($name, $callback)
    {
        parent::__construct($name);

        $this->callback = $callback;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return call_user_func($this->callback, $input, $output);
    }
}