<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\SubContainer;

use SModders\TelegramCore\ChatCommand\AbstractHandler;
use SModders\TelegramCore\Entity\User;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use XF\Container;
use XF\SubContainer\AbstractSubContainer;


class Telegram extends AbstractSubContainer
{
    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $container = $this->container;

        $this->initializeApi($container);
        $this->initializeBotData($container);
        $this->initializeCommandDispatcher($container);
    }
    
    /**
     * Initializes a items in container for API accessing.
     *
     * @param Container $container
     */
    protected function initializeApi(Container $container)
    {
        $container['api'] = function (Container $c)
        {
            return function ($token) use ($c)
            {
                // TODO: add extending vendor classes.
                // $className = \XF::extendClass('TelegramBot\\Api\\BotApi');
                $api = new BotApi($token);
                $api->setProxy($c['proxy']);
    
                \XF::extension()->fire('smodders_tgcore__api_setup', [$api]);
                return $api;
            };
        };

        $container['client'] = function (Container $c)
        {
            return function ($token) use ($c)
            {
                // TODO: add extending vendor classes.
                // $className = \XF::extendClass('TelegramBot\\Api\\Client');
                $client = new Client($token);
                $client->setProxy($c['proxy']);

                // Scratch for fixing internal EventCollection from third-party library
                $client->on(function() { return true; });
    
                \XF::extension()->fire('smodders_tgcore__client_setup', [$client]);
                return $client;
            };
        };

        $container['proxy'] = function (Container $c)
        {
            return $this->app->options()['smodders_tgcore__proxy'];
        };
    }
    
    /**
     * Initializes a items in container for Bot Data accessing.
     *
     * @param Container $container
     */
    protected function initializeBotData(Container $container)
    {
        $container['bot'] = function (Container $c)
        {
            /** @var \XF\Entity\ConnectedAccountProvider $telegramProviderEntity */
            $telegramProviderEntity = $this->app->em()->find('XF:ConnectedAccountProvider', 'smodders_telegram');
            if (!$telegramProviderEntity)
            {
                throw new \RuntimeException("Can't find Connected Account Provider. Is this AddOn installed?");
            }

            $botId = $telegramProviderEntity->options['bot_id'] ?? -1;
            if ($botId != -1)
            {
                $bot = $this->app->em()->create('SModders\TelegramCore:Bot');
                $bot->setReadOnly(true);

                return $bot;
            }

            return $this->app->em()->findOne('SModders\TelegramCore:Bot', $botId);
        };

        $container['bot.isInstalled'] = function (Container $c)
        {
            return !empty($c['bot.token']);
        };

        $container['bot.name'] = function (Container $c)
        {
            return $c['bot']['username'];
        };

        $container['bot.token'] = function (Container $c)
        {
            return $c['bot']['token'];
        };
    }

    /**
     * Initializes a items in container for command processing from Telegram
     * users.
     *
     * @param Container $container
     */
    protected function initializeCommandDispatcher(Container $container)
    {
        $telegram = $this;
        $app = $this->app;

        $container['commands.addOns'] = $app->fromRegistry('smTgCore.cmds_addOns', function (Container $c) use($app) {
            return $app['em']->getRepository('SModders\TelegramCore:Command')->rebuildAddOnCommandsCache();
        });

        $container['commandDispatcher'] = function (Container $c) use ($telegram, $app)
        {
            return function (Client $client) use ($c, $telegram, $app)
            {
                /** @var \SModders\TelegramCore\CommandDispatcher $dispatcher */
                $className = \XF::extendClass('SModders\TelegramCore\CommandDispatcher');
                $dispatcher = new $className($app, $telegram, $client);

                foreach ($c['commands.addOns'] as $name => $handlers)
                {
                    foreach ($handlers as $commandHandler)
                    {
                        $dispatcher->addCommandListener($name, function (Message $message, array $parameters, \Closure $nextCall)
                        use ($commandHandler, $app, $dispatcher)
                        {
                            /** @var AbstractHandler $handler */
                            $className = \XF::extendClass($commandHandler['provider']);
                            $handler = new $className($app, $dispatcher);
                            $handler->setNextCall($nextCall);

                            return $handler->run($message, $parameters);
                        }, $commandHandler['execution_order']);
                    }
                }

                $app->fire('smodders_tgcore__dispatcher_setup', [&$dispatcher]);
                return $dispatcher;
            };
        };
    }

    /**
     * @param null|string $token
     * @return \TelegramBot\Api\BotApi
     */
    public function api($token = null)
    {
        $container = $this->container;
        if ($token === null)
        {
            $token = $container['bot.token'];
        }
        
        return $container['api']($token);
    }
    
    /**
     * @param null|string $token
     * @return \TelegramBot\Api\Client
     */
    public function client($token = null)
    {
        $container = $this->container;
        if ($token === null)
        {
            $token = $container['bot.token'];
        }
        
        return $container['client']($token);
    }
    
    /**
     * @return bool
     */
    public function isInstalled()
    {
        return $this->container['bot.isInstalled'];
    }

    /**
     * @param $userId
     * @param \Closure $action
     * @param bool $setLanguage
     * @param bool $setStyle
     * @return mixed
     * @throws \Exception
     */
    public function asTelegramVisitorById($userId, \Closure $action, $setLanguage = true, $setStyle = true)
    {
        /** @var \SModders\TelegramCore\Entity\User|null $user */
        $user = \XF::em()->find('SModders\\TelegramCore:User', $userId);
        if (!$user)
        {
            return $action(false, false);
        }

        return $this->asTelegramVisitor($user, $action, $setLanguage);
    }

    /**
     * @param User $user
     * @param \Closure $action
     * @param bool $setLanguage
     * @param bool $setStyle
     * @return mixed
     * @throws \Exception
     */
    public function asTelegramVisitor(User $user, \Closure $action, $setLanguage = true, $setStyle = true)
    {
        /** @var \XF\Entity\UserConnectedAccount|null $xenConnectedAccount */
        $xenConnectedAccount = \XF::em()->findOne('XF:UserConnectedAccount', [
            'provider'      => 'smodders_telegram',
            'provider_key'  => $user->id,
        ]);

        /** @var \XF\Entity\User $xenUser */
        $xenUser = null;
        if (!$xenConnectedAccount)
        {
            /** @var \XF\Repository\User $userRepo */
            $userRepo = $this->app->repository('XF:User');
            $xenUser = $userRepo->getGuestUser();
        } else {
            $xenUser = $xenConnectedAccount->User;
        }

        // Additional wrapper. We're want add user entity from argument start.
        $originalAction = $action;
        $action = function() use ($originalAction, $user)
        {
            $arguments = [$user] + func_get_args();

            return call_user_func_array($originalAction, $arguments);
        };

        // Finally - call the function chain.
        return $this->asVisitor($xenUser, $action, $setLanguage, $setStyle);
    }

    /**
     * Temporarily take an action with the given user considered to be the visitor.
     * Also changes the language.
     *
     * @param \XF\Entity\User $user
     * @param \Closure $action
     * @param boolean $setLanguage
     * @param boolean $setStyle
     * @return mixed
     * @throws \Exception
     */
    public function asVisitor(\XF\Entity\User $user, $action, $setLanguage = true, $setStyle = true)
    {
        if ($setLanguage)
        {
            $originalAction = $action;
            $action = function() use ($originalAction, $user)
            {
                $app = \XF::app();
                $templater = $app->templater();

                $newLanguage = $app->language($user->language_id);
                $oldLanguage = \XF::language();
                $oldTemplaterLanguage = $templater->getLanguage();

                \XF::setLanguage($newLanguage);
                $templater->setLanguage($newLanguage);

                try
                {
                    return call_user_func_array($originalAction, func_get_args());
                }
                finally
                {
                    if ($oldLanguage != null)
                    {
                        \XF::setLanguage($oldLanguage);
                        $templater->setLanguage($oldTemplaterLanguage);
                    }
                }
            };
        }
        if ($setStyle)
        {
            $originalAction = $action;
            $action = function() use ($originalAction, $user)
            {
                $app = \XF::app();
                $templater = $app->templater();

                $newStyle = $app->style($user->style_id);
                $oldStyle = $templater->getStyle();

                $templater->setStyle($newStyle);

                try
                {
                    return call_user_func_array($originalAction, func_get_args());
                }
                finally
                {
                    if ($oldStyle != null)
                    {
                        $templater->setStyle($oldStyle);
                    }
                }
            };
        }

        return \XF::asVisitor($user, $action);
    }

    /**
     * @return \SModders\TelegramCore\CommandDispatcher
     */
    public function dispatcher(Client $client = null)
    {
        if ($client === null)
        {
            $client = $this->client();
        }

        return $this->container['commandDispatcher']($client);
    }
}