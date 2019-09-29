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
    public function checkCsrfIfNeeded($action, ParameterBag $params)
    {
        if (strtolower($action) == 'handlewebhook')
        {
            return;
        }

        return parent::checkCsrfIfNeeded($action, $params);
    }
    
    public function actionHandleWebhook(ParameterBag $params)
    {
        $this->assertPostOnly();
        $this->assertWebHookToken($this->request->get('token'));

        try {
            $this->getTelegramContainer()
                ->client()->run();
        } catch (\Exception $e) {
            \XF::logException($e);
            return $this->error($e->getMessage(), 500);
        }
        
        return $this->message('Ok');
    }

    protected function assertWebHookToken($token)
    {
        if ($token != Hash::hashText($this->getTelegramContainer()->get('bot.token')))
        {
            throw $this->exception($this->noPermission());
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