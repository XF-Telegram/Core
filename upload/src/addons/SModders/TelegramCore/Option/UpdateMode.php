<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Option;

use XF\Entity\Option;
use XF\Option\AbstractOption;

class UpdateMode extends AbstractOption
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
        
        // Undefined mode.
        if (!in_array($optionValue, ['none', 'longpoll', 'webhook']))
        {
            return false;
        }

        // Verify bot setup.
        if (!self::telegram()->isInstalled())
        {
            return false;
        }
    
        return \XF::service('SModders\TelegramCore:WebHook')
            ->update($optionValue == 'webhook');
    }
    
    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected static function telegram()
    {
        return \XF::app()->get('smodders.telegram');
    }
}