<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Entity;


use SModders\TelegramCore\CommandDispatcher;
use SModders\TelegramCore\BotApi;
use SModders\TelegramCore\Client;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property-read integer bot_id
 * @property string title
 * @property string username
 * @property string token
 * @property boolean listen_events
 * @property string secret_token
 *
 * GETTERS
 * @property-read Client Client
 * @property-read BotApi Api
 * @property-read CommandDispatcher CommandDispatcher
 *
 * TODO: move command cache to this entity.
 */
class Bot extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->table .= 'bot';
        $structure->shortName .= 'Bot';
        $structure->primaryKey = 'bot_id';

        $structure->columns = [
            'bot_id'        => ['type' => self::UINT, 'autoIncrement' => true],
            'title'         => ['type' => self::STR, 'maxLength' => 128, 'required' => true],
            'username'      => ['type' => self::STR, 'maxLength' => 32, 'required' => true],
            'token'         => ['type' => self::STR, 'maxLength' => 64, 'required' => true],
            'listen_events' => ['type' => self::BOOL, 'default' => false],
            'secret_token'  => ['type' => self::STR, 'maxLength' => 32]
        ];

        $structure->getters = [
            'Client' => true,
            'Api' => true,
            'CommandDispatcher' => true
        ];

        return $structure;
    }

    protected function _postSave()
    {
        if (!$this->isChanged(['secret_token', 'listen_events']))
        {
            return;
        }

        /** @var \SModders\TelegramCore\Service\WebHook $webHookService */
        $webHookService = $this->app()->service('SModders\TelegramCore:WebHook', $this);
        $webHookService->update($this->listen_events);
    }

    /**
     * Recalculates secret token and saves in local entity.
     */
    public function recalculateSecretToken()
    {
        $this->secret_token = \XF\Util\Hash::hashText($this->token);
    }

    protected function getClient()
    {
        return $this->telegramContainer()->client($this);
    }

    protected function getApi()
    {
        return $this->telegramContainer()->api($this);
    }

    protected function getCommandDispatcher()
    {
        return $this->telegramContainer()->dispatcher($this->Client);
    }

    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected function telegramContainer()
    {
        return $this->app()->container('smodders.telegram');
    }
}