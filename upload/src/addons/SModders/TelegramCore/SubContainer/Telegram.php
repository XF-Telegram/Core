<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\SubContainer;

use SModders\TelegramCore\Entity\User;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;
use XF\ConnectedAccount\Provider\AbstractProvider;
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

            /** @var \SModders\TelegramCore\ConnectedAccount\Provider\Telegram $telegramProvider */
            $telegramProvider = $telegramProviderEntity->getHandler();
            if (!$telegramProvider)
            {
                throw new \RuntimeException("Can't create instance for Telegram provider.");
            }
    
            return array_replace($telegramProvider->getDefaultOptions(), $telegramProviderEntity->options);
        };

        $container['bot.isInstalled'] = function (Container $c)
        {
            return !empty($c['bot.token']);
        };

        $container['bot.name'] = function (Container $c)
        {
            return $c['bot']['name'];
        };

        $container['bot.token'] = function (Container $c)
        {
            return $c['bot']['token'];
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
    
    public function asTelegramVisitorById($userId, \Closure $action, $setLanguage = true)
    {
        /** @var \SModders\TelegramCore\Entity\User|null $user */
        $user = \XF::em()->find('SModders\\TelegramCore:User', $userId);
        if (!$user)
        {
            return $action(false);
        }

        return $this->asTelegramVisitor($user, $action, $setLanguage);
    }
    
    public function asTelegramVisitor(User $user, \Closure $action, $setLanguage = true)
    {
        /** @var \XF\Entity\UserConnectedAccount|null $xfUserConnectedAccount */
        $xfUserConnectedAccount = \XF::em()->findOne('XF:UserConnectedAccount', [
            'provider'      => 'smodders_telegram',
            'provider_key'  => $user->id,
        ], 'User');
        if (!$xfUserConnectedAccount)
        {
            return $action(false);
        }

        $xfUser = $xfUserConnectedAccount->User;
        
        $oldLanguage = \XF::language();
        if ($setLanguage)
        {
            $language = \XF::app()->language($xfUser->language_id);
            \XF::setLanguage($language);
        }
        
        try
        {
            return \XF::asVisitor($xfUser, $action);
        }
        finally
        {
            if ($setLanguage)
            {
                \XF::setLanguage($oldLanguage);
            }
        }
    }
}