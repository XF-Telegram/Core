<?php


namespace SModders\TelegramCore\Cron;


use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class LongPoll
{
    public static function process()
    {
        $app = \XF::app();
        $registry = $app->registry();

        // Get offset.
        $offset = $registry->get('smodders_tgcore.lpOffset');
        if (!$offset)
        {
            $offset = 0;
        }

        /** @var \TelegramBot\Api\Client $telegramApi */
        $telegramClient = $app->get('smodders.telegram')->client();
        
        // Handle updates.
        $updates = $telegramClient->getUpdates($offset, 55, 1);
        foreach ($updates as $update)
        {
            $telegramClient->handle([$update]);
            $offset = $update->getUpdateId() + 1;
        }

        // Set offset.
        $registry->set('smodders_tgcore.lpOffset', $offset);
    }
}