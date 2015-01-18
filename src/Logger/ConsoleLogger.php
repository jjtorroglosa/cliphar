<?php
/*
 * Copyright (c) 2015 Juan José Torroglosa Ramón
 *
 * This file is part of the Cliphar package.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Cliphar\Logger;

use Cliphar\Logger\Decorator\ConsoleMessageDecorator;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleLogger extends AbstractLogger
{
    private $levelToInt = array(
        LogLevel::DEBUG => 10,
        LogLevel::INFO => 20,
        LogLevel::NOTICE => 30,
        LogLevel::WARNING => 40,
        LogLevel::ERROR => 50,
        LogLevel::CRITICAL => 60,
        LogLevel::ALERT => 70,
        LogLevel::EMERGENCY => 80
    );

    /**
     * @var array
     */
    private $verbosityLevelMap = array(
        LogLevel::EMERGENCY => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::ALERT => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::CRITICAL => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::ERROR => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::WARNING => OutputInterface::VERBOSITY_NORMAL,
        LogLevel::NOTICE => OutputInterface::VERBOSITY_VERBOSE,
        LogLevel::INFO => OutputInterface::VERBOSITY_VERY_VERBOSE,
        LogLevel::DEBUG => OutputInterface::VERBOSITY_DEBUG,
    );

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $levelThresholdToStderr;
    /**
     * @var null
     */
    private $decorator;

    /**
     * @param OutputInterface $output
     * @param string $levelThresholdToStderr
     * @param array $verbosityLevelMap
     * @param ConsoleMessageDecorator|null $decorator
     */
    public function __construct(
        OutputInterface $output,
        $levelThresholdToStderr = LogLevel::WARNING,
        $verbosityLevelMap = array(),
        $decorator = null
    ) {
        $this->output = $output;
        $this->levelThresholdToStderr = $levelThresholdToStderr;
        $this->verbosityLevelMap = $verbosityLevelMap + $this->verbosityLevelMap;
        $this->decorator = $decorator ?: new ConsoleMessageDecorator();
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        // Write to the error output if necessary and available
        if ($this->isStderrThresholdReached($level) && $this->output instanceof ConsoleOutputInterface) {
            $output = $this->output->getErrorOutput();
        } else {
            $output = $this->output;
        }

        if ($output->getVerbosity() >= $this->verbosityLevelMap[$level]) {
            $output->writeln($this->getDecoratedMessage($level, $this->interpolate($message, $context)));
        }
    }

    /**
     * Interpolates context values into the message placeholders
     *
     * @author PHP Framework Interoperability Group
     *
     * @param string $message
     * @param array  $context
     *
     * @return string
     */
    private function interpolate($message, array $context)
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace[sprintf('{%s}', $key)] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

    /**
     * @param $level
     * @return bool
     */
    private function isStderrThresholdReached($level)
    {
        return $this->levelToInt[$level] >= $this->levelToInt[$this->levelThresholdToStderr];
    }

    /**
     * @param $level
     * @param $message
     * @return string
     */
    private function getDecoratedMessage($level, $message)
    {
        return $this->decorator->decorate($level, $message);
    }
}