<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\SubContainer;


use TelegramBot\Api\BotApi;
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
            return function ($token)
            {
                return new BotApi($token);
            };
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

    
}