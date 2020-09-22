<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Pub\Controller;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;
use XF\Mvc\ParameterBag;
use XF\Pub\Controller\AbstractController;
use XF\Util\Hash;

class Telegram extends AbstractController
{
    protected function csrfExceptions()
    {
        return ['handlewebhook'];
    }

    public function checkCsrfIfNeeded($action, ParameterBag $params)
    {
        if (in_array(strtolower($action), $this->csrfExceptions()))
        {
            return;
        }

        return parent::checkCsrfIfNeeded($action, $params);
    }
    
    public function actionHandleWebhook(ParameterBag $params)
    {
        $this->assertPostOnly();
        $this->setResponseType('json');
        $bot = $this->assertBotExists($this->request->get('token'));

        try
        {
            if ($data = BotApi::jsonValidate($this->request()->getInputRaw(), true))
            {
                $bot->CommandDispatcher->run([Update::fromResponse($data)]);
            }
        }
        catch (\Exception $e)
        {
            \XF::logException($e);
            return $this->error($e->getMessage(), 500);
        }

        return $this->message('Ok');
    }

    protected function assertBotExists($token)
    {
        /** @var \SModders\TelegramCore\Entity\Bot $bot */
        $bot = $this->em()->findOne('SModders\TelegramCore:Bot', ['secret_token', $token]);
        if (!$bot)
        {
            throw $this->exception($this->notFound());
        }

        return $bot;
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