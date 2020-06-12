<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\ChatCommand;


use TelegramBot\Api\Types\Message;

class StartAuthenticate extends AbstractHandler
{
    public function run(Message $message, array $parameters = [])
    {
        if ($message->getChat()->getType() != 'private' ||
            !(count($parameters) == 1 && $parameters[0] == 'smodders_tgcore__authenticate'))
        {
            return $this->next($message, $parameters);
        }

        return $this->redirect('auth', $message);
    }
}
