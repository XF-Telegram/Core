<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Admin\Controller;


use TelegramBot\Api\BotApi;
use TelegramBot\Api\Exception;
use XF\Mvc\Reply\AbstractReply;
use XF\PrintableException;

class Telegram extends \XF\Admin\Controller\AbstractController
{
    /**
     * @return \XF\Mvc\Reply\View
     */
    public function actionIndex()
    {
        return $this->view('SModders\TelegramCore:Navigation', 'smodders_tgcore__index');
    }

    /**
     * @return \XF\Mvc\Reply\Message
     */
    public function actionVerifyConnection()
    {
        if ($this->isConnectionExists($error))
        {
            return $this->message(\XF::phrase('action_completed_successfully'));
        }
    
        return $this->message(\XF::phrase('smodders_tgcore.failure_connection', ['message' => $error]));
    }

    public function actionGetWebhookInfo()
    {
        $this->assertTokenExists();
        $apiResult = $this->assertSuccessRun(function (BotApi $api)
        {
            return $api->call('getWebhookInfo');
        }, null, true);
        
        // If connection failed - we got an AbstractReply body.
        if ($apiResult instanceof AbstractReply)
        {
            return $apiResult;
        }
        
        return $this->view('SModders\TelegramCore:Misc\ViewWebhookInfo', 'smodders_tgcore__webhook_info', ['info' => $apiResult]);
    }

    public function actionUpdateWebhookDetails()
    {
        $this->assertTokenExists();
        $this->service('SModders\TelegramCore:WebHook')
            ->update($this->options()->smodders_tgcore__updateMode == 'webhook');
    
        return $this->message(\XF::phrase('action_completed_successfully'));
    }

    /**
     * @param null|string $message
     * @param null|int $code
     * @return bool
     */
    protected function isConnectionExists(&$message = null, &$code = null)
    {
        // Next code always throws exception.
        try {
            $this->assertSuccessRun(function (BotApi $api) {
                $api->getMe();
            }, '1:TEST');
        }
        catch (Exception $e)
        {
            if (in_array($e->getCode(), [0, 404]))
            {
                return true;
            }

            $code = $e->getCode();
            $message = $e->getMessage();
            return false;
        }
    }

    /**
     * @param Exception $exception
     * @param null|string $message
     * @param null|int $code
     * @return bool
     */
    protected function isConnectionExistsByException(Exception $exception, &$message = null, &$code = null)
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();
        
        if (in_array($code, [0, 404]))
        {
            return true;
        }
        
        return false;
    }

    /**
     * @param \Closure $call
     * @param null|string $token
     * @param bool $onlyPrintable
     * @return bool|mixed
     * @throws Exception
     * @throws \XF\Mvc\Reply\Exception
     * @throws PrintableException
     */
    protected function assertSuccessRun(\Closure $call, $token = null, $onlyPrintable = false)
    {
        // Next code can throw exception.
        try {
            $api = $this->telegram()->api($token);
            
            return $call($api);
        }
        catch (Exception $e)
        {
            if (!$this->isConnectionExistsByException($e, $error))
            {
                throw $this->exception($this->message(\XF::phrase('smodders_tgcore.failure_connection', ['message' => $error])));
            }
            
            throw $onlyPrintable ? new PrintableException($e->getMessage(), 500) : $e;
        }
    }

    protected function assertTokenExists()
    {
        $token = $this->telegram()->get('bot.token');
        if (empty($token))
        {
            throw $this->exception($this->message(\XF::phrase('smodders_tgcore.no_token')));
        }
    }

    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected function telegram()
    {
        return $this->app->get('smodders.telegram');
    }
}