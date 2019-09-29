<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\AuthMethod;


class Direct extends AbstractAuthMethod
{
    public function handle()
    {
        return $this->controller->redirect("tg://resolve?domain={$this->getBotName()}&start=smodders_tgcore__authenticate");
    }
}