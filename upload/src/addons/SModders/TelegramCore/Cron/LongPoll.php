<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Cron;


use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class LongPoll
{
    public static function process()
    {
        $app = \XF::app();
        if ($app->options()->smodders_tgcore__updateMode != 'longpoll')
        {
            return;
        }

        $registry = $app->registry();

        // Get offset.
        $offset = $registry->get('smodders_tgcore.lpOffset');
        if (!$offset)
        {
            $offset = 0;
        }

        /** @var \SModders\TelegramCore\SubContainer\Telegram $telegram */
        $telegram = $app->get('smodders.telegram');
        $client = $telegram->client();
        $dispatcher = $telegram->dispatcher();
        
        // Handle updates.
        $updates = $client->getUpdates($offset, 55, 1);
        foreach ($updates as $update)
        {
            $dispatcher->run([$update]);
            $offset = $update->getUpdateId() + 1;
        }

        // Set offset.
        $registry->set('smodders_tgcore.lpOffset', $offset);
    }
}