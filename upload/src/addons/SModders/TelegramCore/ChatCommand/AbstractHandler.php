<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\ChatCommand;


use SModders\TelegramCore\CommandDispatcher;
use SModders\TelegramCore\SubContainer\Telegram;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;
use XF\App;

abstract class AbstractHandler
{
    /** @var \XF\App */
    protected $app;

    /** @var CommandDispatcher */
    protected $dispatcher;

    /** @var \TelegramBot\Api\BotApi */
    protected $telegramClient;

    /** @var \SModders\TelegramCore\SubContainer\Telegram */
    protected $telegramContainer;

    /** @var \Closure|null */
    protected $nextCall;

    public function __construct(App $app, CommandDispatcher $dispatcher)
    {
        $this->app = $app;
        $this->dispatcher = $dispatcher;
        $this->telegramClient = $dispatcher->client();
        $this->telegramContainer = $dispatcher->container();

        $this->setup();
    }

    protected function setup()
    {
    }

    public abstract function run(Message $message, array $parameters = []);

    /**
     * @return App
     */
    public function app()
    {
        return $this->app;
    }

    /**
     * @param \Closure $next
     * @return $this
     */
    public function setNextCall(\Closure $next)
    {
        $this->nextCall = $next;
        return $this;
    }

    protected function next(Message $message = null, array $parameters = [])
    {
        if (!$this->nextCall)
        {
            return NULL;
        }

        return ($this->nextCall)($message, $parameters);
    }

    protected function redirect($command, Message $message = null, array $parameters = [])
    {
        return $this->dispatcher->runCommand($command, $message, $parameters);
    }
}
