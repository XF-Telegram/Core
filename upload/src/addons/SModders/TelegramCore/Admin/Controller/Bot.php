<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Admin\Controller;


use SModders\Core\Admin\Controller\AbstractCrudController;
use XF\Mvc\Entity\Entity;
use XF\Mvc\FormAction;

class Bot extends AbstractCrudController
{
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
}