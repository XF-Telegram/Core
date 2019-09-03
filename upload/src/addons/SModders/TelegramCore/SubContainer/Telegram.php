<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\SubContainer;


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

        $this->initializeAuthMethods($container);
        $this->initializeApi($container);
    }
    
    /**
     * Initializes a factory for auth methods and map.
     * @param Container $container
     */
    protected function initializeAuthMethods(Container $container)
    {
        $container->factory('authMethod', function($type, array $params, Container $c)
        {
            /** @var array $map */
            $map = $c['authMethods'];
            if (isset($map[$type]))
            {
                $type = $map[$type]['provider'];
            }
        
            $class = \XF::stringToClass($type, '%s\AuthMethod\%s');
            $class = $this->extendClass($class);
        
            if (!class_exists($class))
            {
                throw new \InvalidArgumentException("Unknown auth method class '$class'");
            }
        
            return new $class($params['provider']);
        });
    
        $container['authMethods'] = function (Container $c)
        {
            return [
                'direct'    => [
                    'provider'  => 'SModders\TelegramCore:Direct',
                    'phrase'    => 'smodders_tgcore.authMethod_direct',
                ],

                'oauth'     => [
                    'provider'  => 'SModders\TelegramCore:OAuth',
                    'phrase'    => 'smodders_tgcore.authMethod_oauth'
                ],
            ];
        };
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
                $api = new BotApi($token);
                $api->setProxy($c['proxy']);
                return $api;
            };
        };

        $container['client'] = function (Container $c)
        {
            return function ($token) use ($c)
            {
                $client = new Client($token);
                $client->setProxy($c['proxy']);
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
     * @param $type
     * @param AbstractProvider $provider
     * @return \SModders\TelegramCore\AuthMethod\AbstractAuthMethod
     */
    public function authMethod($type, AbstractProvider $provider)
    {
        return $this->container->create('authMethod', $type, ['provider' => $provider]);
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
}