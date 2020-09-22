<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;


use SModders\TelegramCore\Entity\Bot;
use TelegramBot\Api\Events\EventCollection;

class Client extends \TelegramBot\Api\Client
{
    /** @var \SModders\TelegramCore\Entity\Bot|null */
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

        // We're don't use parent constructor because this is uses BotApi class from vendor library.
        $this->api = ($this->bot ?? $this->bot->Api) ?? \XF::app()->get('smodders.telegram')->api($token);
        $this->events = new EventCollection($trackerToken);
    }

    /**
     * @return Bot|null
     */
    public function bot()
    {
        return $this->bot;
    }
}
