<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\AuthMethod;


class OAuth extends AbstractAuthMethod
{
    public function handle()
    {
        $viewParams = [
            'botName'       => $this->getBotName(),
            'redirectUri'   => $this->provider->getRedirectUri($this->providerEntity),
        ];
        
        return $this->controller->view('SModders\TelegramCore:AuthMethod\OAuth', 'smodders_tgcore.authmethod_oauth', $viewParams);
    }
}