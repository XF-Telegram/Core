<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Service;


use XF\PrintableException;
use XF\Service\AbstractService;
use XF\Util\Hash;

class WebHook extends AbstractService
{
    public function update($setup)
    {
        $url = $setup ? $this->getWebhookUrl() : '';

        try {
            $this->telegram()->api()
                ->setWebhook($url);
        } catch (\Exception $e) {
            \XF::logException($e); // :thinking:
            return false;
        }
        
        return true;
    }
    
    protected function getWebhookUrl()
    {
        $app = $this->app;
        $options = $app->options();
        
        $link = $app->router('public')->buildLink('canonical:smodders_telegram/handle-webhook', null, [
            'token' => Hash::hashText($app->get('smodders.telegram')->get('bot.token'))
        ]);
        
        $webProxy = $options['smodders_tgcore__webHookProxy'];
        if (!empty($webProxy))
        {
            $link = str_replace('{webHook}', urlencode($link), $webProxy);
        }
        
        $this->assertWebHookIsHttps($link);
        return $link;
    }
    
    /**
     * Triggers internal link checking.
     * Checking is just verifies URL protocol.
     *
     * @param string $link
     * @throws PrintableException
     */
    protected function assertWebHookIsHttps(&$link)
    {
        if (strncmp($link, 'https', 5) != 0)
        {
            throw new PrintableException(\XF::phrase("smodders_tgcore.invalid_webhook_url"));
        }
    }
    
    /**
     * @return \SModders\TelegramCore\SubContainer\Telegram
     */
    protected function telegram()
    {
        return $this->app->get('smodders.telegram');
    }
}
