<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Option;


use XF\Option\AbstractOption;

class Proxy extends AbstractOption
{
    /**
     * @param string $optionValue
     * @param Option $option
     * @return bool
     */
    public static function verifyMode(&$optionValue, \XF\Entity\Option $option)
    {
        if ($option->isInsert())
        {
            // insert - just trust the default value
            return true;
        }
    
        // If this is current value - ignore.
        if ($option->option_value == $optionValue)
        {
            return true;
        }

        // Ignore option changing, if current update mode is not webhook.
        if (\XF::options()->smodders_tgcore__updateMode != 'webhook')
        {
            return true;
        }

        return \XF::service('SModders\TelegramCore:WebHook')
            ->update(true);
    }
}