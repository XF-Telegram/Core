<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;

use XF\App;
use XF\Container;

class Listener
{
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
}