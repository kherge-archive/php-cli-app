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
 * The service container-based application.
 *
 * @author Kevin Herrera <kevin@herrera.io>
 */
class Application extends Container implements HelperInterface
{
    /**
     * The helper set.
     *
     * @var HelperSet
     */
    private $helperSet;

    /**
     * @override
     */
    public function __construct(array $container = array())
    {
        parent::__construct($container);

        $this->registerDefaultServices();
    }

    /**
     * Adds a new command.
     *
     * @param string   $name     The name.
     * @param callable $callable The callable.
     *
     * @return Command The new command.
     */
    public function add($name, $callable)
    {
        $command = $this['console.command_factory']($name, $callable);

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
        return 'app';
    }

    /**
     * Runs the application.
     *
     * @return integer The exit status code.
     */
    public function run()
    {
        return $this['console.run']();
    }

    /**
     * Sets a helper.
     *
     * @param HelperInterface $helper A helper.
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

    /**
     * Registers the default application services.
     */
    protected function registerDefaultServices()
    {
        $this->register(new Provider\ErrorHandlingServiceProvider());
        $this->register(new Provider\ConsoleServiceProvider());
    }
}
