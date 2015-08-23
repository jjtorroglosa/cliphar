<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Command;

use Cliphar\BaseApplication;
use Cliphar\InputDefinition\InputDefinitionParser;
use Cliphar\InputDefinition\Model\InputDefinition;
use ReflectionFunction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandFactory
{
    private static $parser;

    /**
     * @param string $name
     * @param string $definition
     * @param callable $callable
     * @return Command
     * @throws \Cliphar\InputDefinition\Exception\OptionsParsingException
     */
    public static function createCommand($name, $definition, $callable)
    {
        self::$parser = self::$parser ?: new InputDefinitionParser();
        $inputDefinition = self::$parser->parse($definition);

        $command = new Command($name);

        self::addArguments($inputDefinition, $command);
        self::addOptions($inputDefinition, $command);

        $command->setCode(self::getCallableWrapperFor($callable));

        return $command;
    }

    /**
     * @param InputDefinition $inputDefinition
     * @param Command $command
     * @return array
     */
    private static function addArguments($inputDefinition, $command)
    {
        foreach ($inputDefinition->getArguments() as $argument) {
            $mode = $argument->isRequired()
                ? InputArgument::REQUIRED
                : InputArgument::OPTIONAL;

            $defaultValue = $argument->hasDefaultValue()
                ? $argument->getDefaultValue()
                : null;

            $command->addArgument($argument->getName(), $mode, null, $defaultValue);
        }
    }

    /**
     * @param InputDefinition $inputDefinition
     * @param Command $command
     */
    private static function addOptions($inputDefinition, $command)
    {
        foreach ($inputDefinition->getOptions() as $option) {
            $shortcut = $option->hasAbbreviatedName()
                ? $option->getAbbreviatedName()
                : null;

            $mode = null;
            if ($option->hasDefaultValue()) {
                $mode = InputOption::VALUE_REQUIRED;
            }

            $defaultValue = $option->hasDefaultValue()
                ? $option->getDefaultValue()
                : null;

            $command->addOption($option->getName(), $shortcut, $mode, null, $defaultValue);
        }
    }

    private static function getCallableWrapperFor($callable)
    {
        return function (InputInterface $inputInterface, OutputInterface $outputInterface) use ($callable) {
            $reflectionClosure = new ReflectionFunction($callable);
            $closureParameters = $reflectionClosure->getParameters();
            $parameters = array();

            if (count($closureParameters) > 0
                && $closureParameters[0]->getClass()->getName() === 'Symfony\Component\Console\Input\InputInterface') {
                $parameters[0] = $inputInterface;
                array_shift($closureParameters);
            }

            $container = BaseApplication::getInstance()->getContainer();
            foreach ($closureParameters as $p) {
                $parameters[] = $container->get($p->getClass()->getName());
            }

            array_merge(array($inputInterface, $parameters));
            call_user_func_array($callable, $parameters);
        };
    }
}