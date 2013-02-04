<?php

namespace Herrera\Cli;

use Herrera\Cli\Provider;
use Herrera\Service\Container;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\HelperInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * This class serves as the command line application class, service container
 * (by extending the `Herrera\Service\Container` class), and a helper for the
 * Symfony Console. By making the class a helper, commands will be able to
 * access the registered services.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class Application extends Container implements HelperInterface
{
    /**
     * The console helper set.
     *
     * @var HelperSet
     */
    private $helperSet;

    /**
     * Sets the application name and version, and registers default services.
     *
     * @param string $name    The name.
     * @param string $version The version.
     */
    public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        parent::__construct();

        $this->register(new Provider\ErrorHandlingServiceProvider());
        $this->register(new Provider\CommandFactoryServiceProvider());
        $this->register(new Provider\ConsoleServiceProvider(), array(
            'app.name' => $name,
            'app.version' => $version
        ));
    }

    /**
     * Adds a new command.
     *
     * @param string   $name     The name.
     * @param callable $callback The callback.
     *
     * @return Command The new command.
     */
    public function add($name, $callback)
    {
        $command = $this['command_factory']($name, $callback);

        $this['console']->add($command);

        return $command;
    }

    /**
     * {@inheritDoc}
     */
    public function getHelperSet()
    {
        return $this->helperSet;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'cli';
    }

    /**
     * Runs the console application.
     *
     * @return integer The exit status code.
     */
    public function run()
    {
        return $this['console']->run(
            $this['console.input'],
            $this['console.output']
        );
    }

    /**
     * Sets a helper.
     *
     * @param HelperInterface $helper The helper.
     */
    public function set(HelperInterface $helper)
    {
        $this['console']->getHelperSet()->set($helper);
    }

    /**
     * {@inheritDoc}
     */
    public function setHelperSet(HelperSet $helperSet = null)
    {
        $this->helperSet = $helperSet;
    }
}