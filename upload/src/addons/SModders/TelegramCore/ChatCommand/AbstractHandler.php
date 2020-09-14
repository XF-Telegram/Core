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

    /** @var \XF\Template\Templater */
    protected $templater;

    /** @var \Closure|null */
    protected $nextCall;

    public function __construct(App $app, CommandDispatcher $dispatcher)
    {
        $this->app = $app;
        $this->dispatcher = $dispatcher;
        $this->telegramClient = $dispatcher->client();
        $this->telegramContainer = $dispatcher->container();
        $this->templater = $app->templater();

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

    protected function renderMessageTemplate($templateName, array $parameters = [], $addDefaultParams = true)
    {
        if (strpos($templateName, ':') === FALSE)
        {
            // "email" group is looks like more correct for messages then "public",
            // because in my understanding, template group "public" should be used
            // only for rendering in user interface. "email" should be used when
            // it contains any markup for any messages for user in third-party
            // services (like e-mails or Telegram).
            //
            // Some time ago i experimented with creating own template group, and i
            // did it, but dismissed, because when XF performs add-on installation,
            // he can't import template for direct authentications (when user
            // interact with bot). Listener is not enabled on this moment.
            //
            // R.I.P. own template group.

            $templateName = 'email:' . $templateName;
        }

        if ($addDefaultParams)
        {
            // We don't have "xf" in this context because we're running too early.
            // So we create him manually!
            //
            // Note: this triggers event "templater_global_data".
            $parameters['xf'] = $this->app->getGlobalTemplateData();
        }

        $messageText = $this->templater->renderTemplate($templateName, $parameters, $addDefaultParams);
        return trim($messageText);
    }
}
