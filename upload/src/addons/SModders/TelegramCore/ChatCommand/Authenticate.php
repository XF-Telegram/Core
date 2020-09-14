<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\ChatCommand;


use TelegramBot\Api\Types\Message;

class Authenticate extends AbstractHandler
{
    /** @var \ArrayObject */
    protected $options;

    protected function setup()
    {
        $this->options = $this->app->options();
    }

    /**
     * @param Message $message
     * @param array $parameters
     * @throws \TelegramBot\Api\Exception
     * @throws \TelegramBot\Api\HttpException
     * @throws \TelegramBot\Api\InvalidJsonException
     */
    public function run(Message $message, array $parameters = [])
    {
        $userId = $message->getChat()->getId();
        $keyboard = $this->getKeyboard();
        $messageText = $this->getMessageText($message);

        $this->telegramClient->call('sendMessage', [
            'chat_id' => $userId,
            'parse_mode' => 'HTML',
            'text' => $messageText,

            'reply_markup' => json_encode($keyboard),
        ]);
    }

    /**
     * @param Message $message
     * @return string
     */
    protected function getMessageText(Message $message)
    {
        return $this->renderMessageTemplate('smodders_tgcore__directauth_message', [
            'message' => $message,
            'board' => [
                'url' => $this->options->boardUrl,
                'title' => $this->options->boardTitle,
            ],
        ]);
    }

    /**
     * @return \array[][][]
     */
    protected function getKeyboard()
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        'text' => \XF::phraseDeferred('button.login'),
                        'login_url' => [
                            'url' => $this->options->boardUrl . '/connected_account.php',
                            'request_write_access' => true,
                        ]
                    ]
                ]
            ]
        ];
    }
}
