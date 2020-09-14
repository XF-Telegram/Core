<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Entity;


use SModders\TelegramCore\ChatCommand\AbstractHandler;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property integer command_id
 * @property string name
 * @property string provider_class
 * @property integer execution_order
 */
class Command extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->table .= 'command';
        $structure->shortName .= 'Command';
        $structure->primaryKey = 'command_id';

        $structure->columns = [
            'command_id'        => ['type' => self::UINT, 'autoIncrement' => true],
            'name'              => ['type' => self::STR, 'maxLength' => 32, 'required' => true],
            'provider_class'    => ['type' => self::STR, 'maxLength' => 100, 'required' => true],
            'execution_order'   => ['type' => self::UINT, 'default' => 10],
        ];

        return $structure;
    }

    protected function _postSave()
    {
        if ($this->isChanged(['name', 'provider_class', 'execution_order']))
        {
            $this->repository('SModders\TelegramCore:Command')
                ->rebuildAddOnCommandsCache();
        }
    }
}