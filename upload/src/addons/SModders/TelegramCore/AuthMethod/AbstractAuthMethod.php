<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\AuthMethod;

use XF\Entity\ConnectedAccountProvider;
use XF\App;
use XF\ConnectedAccount\Provider\AbstractProvider;
use XF\Http\Request;
use XF\Mvc\Controller;

abstract class AbstractAuthMethod
{
    /** @var \XF\App */
    protected $app;

    /** @var \TelegramBot\Api\BotApi */
    protected $api;

    /** @var \XF\ConnectedAccount\Provider\AbstractProvider */
    protected $provider;
    
    /** @var \XF\Entity\ConnectedAccountProvider */
    protected $providerEntity;

    /** @var \XF\Mvc\Controller */
    protected $controller;

    /** @var \XF\Http\Request */
    protected $request;

    /**
     * AbstractAuthMethod constructor.
     * @param App $app
     * @param AbstractProvider $provider
     */
    public function __construct(App $app, AbstractProvider $provider)
    {
        $this->app = $app;
        $this->provider = $provider;
        
        $this->setup();
    }
    
    protected function setup()
    {
        $this->api = $this->app['smodders.telegram']->api();

        $this->_setup();
    }
    
    /**
     * Setups the auth method.
     */
    protected function _setup()
    {
    }
    
    /**
     * @return \XF\Mvc\Reply\AbstractReply
     */
    abstract public function handle();
    
    /**
     * @param \XF\Mvc\Controller $controller
     * @return $this
     */
    public function setController(Controller $controller)
    {
        $this->controller = $controller;
        return $this;
    }
    
    /**
     * @param \XF\Entity\ConnectedAccountProvider $providerEntity
     * @return $this
     */
    public function setProviderEntity(ConnectedAccountProvider $providerEntity)
    {
        $this->providerEntity = $providerEntity;
        return $this;
    }

	/**
	 * @param \XF\Http\Request $request
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @param array $data
	 * @param $secret
	 */
    public function verifyAuth(array $data)
	{
		$request = $this->request;
		$data = [];

		foreach (['id', 'first_name', 'last_name', 'date', 'hash', 'username', 'photo_url'] as $key)
		{
			$data[$key] = $request->get($key, 'string');
		}

		if (!$this->isValidHash($data))
        {
            return false;
        }

		// $this->app->em()->find('SModders\TelegramCore:')
        return true;
	}

	protected function getBotName()
    {
        return $this->providerEntity->options['name'];
    }
}