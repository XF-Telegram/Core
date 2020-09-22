<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;


use SModders\TelegramCore\SubContainer\Telegram;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;
use XF\App;

class CommandDispatcher
{
    /** @var \XF\App */
    protected $app;

    /** @var \SModders\TelegramCore\SubContainer\Telegram */
    protected $container;

    /** @var \TelegramBot\Api\Client */
    protected $client;

    /** @var array */
    protected $commands = [];

    /** @var string|null */
    protected $_runningCommand = null;

    /** @var array */
    protected $_arguments = [];

    public function __construct(App $app, Telegram $container, Client $client)
    {
        $this->app = $app;
        $this->container = $container;
        $this->client = $client;
    }

    /**
     * @return string|null
     */
    public function getCurrentCommandName()
    {
        return $this->_runningCommand;
    }

    /**
     * @return array
     */
    public function getCurrentCommandParameters()
    {
        return $this->_arguments;
    }

    /**
     * @return bool
     */
    public function isHandlingCommand()
    {
        return $this->_runningCommand !== null;
    }

    /**
     * @return \TelegramBot\Api\Client
     */
    public function client()
    {
        return $this->client;
    }

    /**
     * @return Telegram
     */
    public function container()
    {
        return $this->container;
    }

    /**
     * @param array $updates
     */
    public function run(array $updates)
    {
        $this->app->extension()->fire('smodders_tgcore__dispatcher_run_pre', [$this, &$updates]);

        // Firstly, call client handlers.
        $this->client->handle($updates);

        // Now we can try determine command for everything updates.
        /** @var \TelegramBot\Api\Types\Update $update */
        foreach ($updates as $update)
        {
            $command = $this->getCommand($update);
            if ($command === null)
            {
                // We don't know, how it should be handled.
                // This isn't command.
                continue;
            }

            /** @var string $name */
            /** @var array $parameters */
            list($name, $parameters) = [$command['command'], $command['parameters']];
            $this->runCommand($name, $update->getMessage(), $parameters);
        }
    }

    /**
     * @param $name
     * @param Message $message
     * @param array $parameters
     * @return mixed
     */
    public function runCommand($name, Message $message, array $parameters = [])
    {
        $this->_runningCommand = $name;
        $this->_arguments = $parameters;

        $commandChain = $this->buildCommandChain($name);
        $result = $commandChain($message, $parameters);

        $this->_runningCommand = null;
        $this->_arguments = [];

        return $result;
    }

    public function buildCommandChain($name)
    {
        $closures = [];

        if (array_key_exists($name, $this->commands))
        {
            // Setup array with all closures for calling.
            foreach ($this->commands[$name] as $prioritizedClosures)
            {
                foreach ($prioritizedClosures as $closure)
                {
                    $closures[] = $closure;
                }
            }

            $closures = array_reverse($closures);
        }

        $handleCommand = function () { return NULL; };
        foreach ($closures as $closure)
        {
            $handleCommand = function (Message $message, array $parameters) use ($handleCommand, $closure)
            {
                return $closure($message, $parameters, $handleCommand);
            };
        }

        $container = $this->container;
        return function (Message $message, array $parameters) use ($handleCommand, $container)
        {
            $user = $message->getFrom();
            $userId = $user->getId();

            return $container->asTelegramVisitorById($userId, function () use ($message, $parameters, $handleCommand)
            {
                return $handleCommand($message, $parameters);
            });
        };
    }

    /**
     * @param Update $update
     * @return array|null
     */
    protected function getCommand(Update $update)
    {
        $message = $update->getMessage();
        if (!$message)
        {
            return null;
        }

        if (!preg_match(self::commandRegExp(), $message->getText(), $matches))
        {
            return null;
        }

        $parameters = isset($matches[3]) && !empty($matches[3]) ?
            str_getcsv($matches[3], chr(32)) : [];

        $command = [
            'command'       => $matches[1],
            'parameters'    => $parameters
        ];

        if (isset($matches[2]) && !empty($matches[2]))
        {
            if ($matches[2] != '@' . $this->container['bot.name'])
            {
                return null;
            }
        }

        return $command;
    }

    protected static function commandRegExp()
    {
        return '/^(?:@\w+\s)?\/([^\s@]+)(@\S+)?\s?(.*)$/';
    }

    /**
     * @param $command
     * @param \Closure $closure
     * @param int $priority
     */
    public function addCommandListener($command, \Closure $closure, $priority = 10)
    {
        // Verify command in our chain.
        if (!array_key_exists($command, $this->commands))
        {
            $this->commands[$command] = [];
        }

        // Verify priority in our chain.
        if (!array_key_exists($priority, $this->commands[$command]))
        {
            $this->commands[$command][$priority] = [];
        }

        // Add command.
        $this->commands[$command][$priority][] = $closure;
    }
}