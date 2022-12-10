<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;


use SModders\TelegramCore\Entity\Bot;

class BotApi extends \TelegramBot\Api\BotApi
{
    /** @var Bot|null */
    protected $bot;

    public function __construct($token, $trackerToken = null)
    {
        // Token can be a "Bot" entity. In this case, we need
        // grab token and save "Bot" entity in instance for
        // internal purposes.
        if (is_object($token) && $token instanceof Bot)
        {
            $this->bot = $token;
            $token = $this->bot->token;
        }

        parent::__construct($token, $trackerToken);
    }

    /**
     * @return Bot|null
     */
    public function bot()
    {
        return $this->bot;
    }
}