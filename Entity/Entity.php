<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Entity;


use XF\Mvc\Entity\Structure;

class Entity extends \XF\Mvc\Entity\Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure->shortName = 'SModders\TelegramCore:';
        $structure->table = 'xf_smodders_tgcore_';

        return $structure;
    }
}