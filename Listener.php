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
use XF\Mvc\Entity\Manager;
use XF\Mvc\Entity\Structure;
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
     * Called when the BB code renderer map is accessed.
     *
     * @param array $rendererMap The current renderer map.
     */
    public static function bb_code_renderer_map(array &$rendererMap)
    {
        $rendererMap['smTgCoreMarkdown'] = 'SModders\TelegramCore:Markdown';
    }
}