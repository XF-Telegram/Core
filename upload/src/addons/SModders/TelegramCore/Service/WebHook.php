<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Service;


use XF\Service\AbstractService;
use XF\Util\Hash;

class WebHook extends AbstractService
{
    public static function update($setup)
    {
        try {
            $url = $setup ? self::getWebhookUrl() : '';

            self::telegram()->api()
                ->setWebhook($url);
        } catch (\Exception $e) {
            \XF::logException($e); // :thinking:
            return false;
        }
        
        return true;
    }
    
    protected static function getWebhookUrl()
    {
        $app = \XF::app();
        $options = $app->options();
        
        $link = $options->boardUrl . \XF::app()->router('public')->buildLink('smodders_telegram/handle-webhook', null, ['token' => Hash::hashText($app->get('smodders.telegram')->get('bot.token'))]);
        
        $webProxy = $options['smodders_tgcore__webHookProxy'];
        if (!empty($webProxy))
        {
            $link = str_replace('{webHook}', urlencode($link), $webProxy);
        }

        return $link;
    }
    
    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected static function telegram()
    {
        return \XF::app()->get('smodders.telegram');
    }
}