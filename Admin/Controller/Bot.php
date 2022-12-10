<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Admin\Controller;


use SModders\TelegramCore\BotApi;
use XF\Mvc\Entity\Entity;
use XF\Mvc\FormAction;
use XF\Mvc\ParameterBag;
use XF\Mvc\Reply\AbstractReply;

class Bot extends AbstractCrudController
{
    use SharedControllerMethodsTrait;

    protected function _entityName()
    {
        return 'SModders\TelegramCore:Bot';
    }

    protected function _route()
    {
        return 'smodders_telegram/bots';
    }

    protected function _titleColumnName()
    {
        return 'title';
    }

    protected function _templatePrefix()
    {
        return 'smodders_tgcore__bots';
    }

    protected function _editableFields(Entity $entity)
    {
        return [
            'token' => 'str',
            'title' => 'str',
            'listen_events' => 'bool'
        ];
    }

    /**
     * @param \SModders\TelegramCore\Entity\Bot $entity
     * @return void|FormAction
     */
    protected function entitySaveProcess(Entity $entity)
    {
        $token = $this->filter('token', 'str');

        $form = parent::entitySaveProcess($entity);
        $form->setup(function (FormAction $form) use ($entity)
        {
            try
            {
                $api = $entity->Api;
                $user = $api->getMe();
            }
            catch (\Exception $e)
            {
                $message = $e->getMessage();
                if ($message == 'Not Found')
                {
                    $message = \XF::phrase('smodders_tgcore.invalid_token');
                }

                $form->logError($message);
                return;
            }

            $entity->username = $user->getUsername();
            if (empty($entity->title))
            {
                $entity->title = '@' . $entity->username;
            }
        });
        $form->setup(function (FormAction $form) use ($entity)
        {
            if ($entity->isChanged('token'))
            {
                $entity->recalculateSecretToken();
            }
        });

        return $form;
    }

    public function actionGetWebhookInfo(ParameterBag $params)
    {
        /** @var \SModders\TelegramCore\Entity\Bot $bot */
        $bot = $this->assertRecordExists('SModders\TelegramCore:Bot', $params->bot_id);
        $apiResult = $this->assertSuccessRun(function (BotApi $api)
        {
            return $api->call('getWebhookInfo');
        }, $bot, true);

        // If connection failed - we got an AbstractReply body.
        if ($apiResult instanceof AbstractReply)
        {
            return $apiResult;
        }

        return $this->view('SModders\TelegramCore:Misc\ViewWebhookInfo', 'smodders_tgcore__webhook_info', ['info' => $apiResult]);
    }

    public function actionUpdateWebhookDetails(ParameterBag $params)
    {
        /** @var \SModders\TelegramCore\Entity\Bot $bot */
        $bot = $this->assertRecordExists('SModders\TelegramCore:Bot', $params->bot_id);
        $this->service('SModders\TelegramCore:WebHook', $bot)
            ->update($bot->listen_events);

        return $this->message(\XF::phrase('action_completed_successfully'));
    }

}