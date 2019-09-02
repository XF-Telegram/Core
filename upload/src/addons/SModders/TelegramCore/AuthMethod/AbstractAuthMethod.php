<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\AuthMethod;

use XF\App;
use XF\ConnectedAccount\Provider\AbstractProvider;

abstract class AbstractAuthMethod
{
    /** @var \XF\App */
    protected $app;

    /** @var \XF\ConnectedAccount\Provider\AbstractProvider */
    protected $provider;

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
    
    /**
     * Setups the auth method.
     */
    protected function setup()
    {
    }
    
    /**
     * @return \XF\Mvc\Reply\AbstractReply
     */
    abstract public function handle();
}