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
     * Fired inside the importers container in the Import sub-container.
     *
     * @param Import $container
     * @param Container $parentContainer
     * @param array $importers
     */
    public static function import_importer_classes(Import $container, Container $parentContainer, array &$importers)
    {
        $importers[] = 'SModders\TelegramCore:Telegram';
    }
}
