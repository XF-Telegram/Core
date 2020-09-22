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

class CommandHandlers extends AbstractCrudController
{
    protected function _entityName()
    {
        return 'SModders\TelegramCore:UserCommand';
    }

    protected function _route()
    {
        return 'smodders_telegram/command-handlers';
    }

    protected function _titleColumnName()
    {
        return 'title';
    }

    protected function _templatePrefix()
    {
        return 'smodders_tgcore__command_handlers';
    }

    protected function _editableFields(Entity $entity)
    {
        return [
            'title' => 'str'
        ];
    }

    protected function entitySaveProcess(Entity $entity)
    {
        $commandName = $this->filter('title', 'str');

        /** @var \SModders\TelegramCore\Entity\UserCommand $entity */
        $formAction = parent::entitySaveProcess($entity);
        $formAction->setup(function ($formAction) use ($entity, $commandName)
        {
            if ($entity->isInsert())
            {
                /** @var \SModders\TelegramCore\Entity\Command $command */
                $command = $this->em()->create('SModders\TelegramCore:Command');
                $command->bulkSet([
                    'name' => $commandName,
                    'provider_class' => 'SModders\TelegramCore:UserDefinedCommandHandler',
                    'execution_order' => 50
                ]);

                $command->save();
                $entity->command_id = $command->command_id;
            }
        });

        $messageTemplate = $entity->MasterTemplate;
        $formAction->setupEntityInput($messageTemplate, $this->filter([
            'template' => 'str'
        ]));

        $formAction->apply(function () use ($messageTemplate)
        {
            $messageTemplate->save();
        });

        return $formAction;
    }
}
