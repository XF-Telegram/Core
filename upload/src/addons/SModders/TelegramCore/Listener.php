<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;

use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Message;
use XF\App;
use XF\Container;
use XF\Import\Manager;
use XF\SubContainer\Import;

class Listener
{
    /**
     * Called after the global \XF\App object has been setup.
     *
     * @param App $app
     */
    public static function app_setup(App $app)
    {
        /** @var \XF\Container $container */
        $container = $app->container();

        // Make Telegram great again!
        $container['smodders.telegram'] = function (Container $c) use ($app)
        {
            $class = $app->extendClass('SModders\TelegramCore\SubContainer\Telegram');
            return new $class($c, $app);
        };
    }
    
    /**
     * Called after the Telegram API Client \TelegramBot\Api\Client object is created.
     *
     * @param Client $client
     */
    public static function smodders_tgcore__client_setup(Client $client)
    {
        $client->command('start', function (Message $message) use ($client)
        {
            // \XF::dump($message); exit();
            $chat = $message->getChat();
            if ($chat->getType() != 'private')
            {
                return;
            }
            
            if ($message->getText() != '/start smodders_tgcore__authenticate')
            {
                return;
            }
            
            $options = \XF::options();
            $client->call('sendMessage', [
                'chat_id'       => $chat->getId(),
                'parse_mode'    => 'HTML',
                
                'reply_markup'  => json_encode([
                    'inline_keyboard'   => [
                        [
                            [
                                'text'      => \XF::phraseDeferred('button.login'),
                                'login_url' => [
                                    'url'                   => $options->boardUrl . '/connected_account.php',
                                    'request_write_access'  => true,
                                ]
                            ]
                        ]
                    ]
                ]),
    
                'text'          => \XF::app()->templater()->renderTemplate('public:smodders_tgcore__directauth_message', [
                    'message'   => $message,
                    'board'     => [
                        'url'   => $options->boardUrl,
                        'title' => $options->boardTitle,
                    ],
                ]),
            ]);
        });
    }
    
    /**
     * Fired inside the importers container in the Import sub-container.
     *
     * @param Import $container
     * @param Container $parentContainer
     * @param array $importers
     */
    public static function import_importer_classes(Import $container, Container $parentContainer, array &$importers)
    {
        $importers[] = 'SModders\\TelegramCore:Telegram';
    }
}