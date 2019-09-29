<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Pub\Controller;

use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Util\Hash;

class Telegram extends AbstractController
{
    public function handleWebhookAction(ParameterBag $params)
    {
        $this->assertWebHookToken($params->token);

        try {
            $this->getTelegramContainer()
                ->client()->run();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    protected function assertWebHookToken($token)
    {
        if ($token != Hash::hashText($this->getTelegramContainer()['bot.token']))
        {
            return $this->noPermission();
        }
    }
    
    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected function getTelegramContainer()
    {
        return $this->app['smodders.telegram'];
    }
}