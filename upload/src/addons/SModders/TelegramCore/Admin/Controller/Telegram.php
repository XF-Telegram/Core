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
    use SharedControllerMethodsTrait;

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

    protected function assertTokenExists()
    {
        $token = $this->telegram()->get('bot.token');
        if (empty($token))
        {
            throw $this->exception($this->message(\XF::phrase('smodders_tgcore.no_token')));
        }
    }
}