<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Repository;


use XF\Mvc\Entity\Repository;

class Command extends Repository
{
    public function rebuildAddOnCommandsCache()
    {
        $cache = [];
        $_commands = $this->finder('SModders\TelegramCore:Command')->order('execution_order')
            ->fetch()->groupBy('name');

        foreach ($_commands as $name => $groupedCommandHandlers)
        {
            $commandHandlers = [];

            /** @var \SModders\TelegramCore\Entity\Command $handler */
            foreach ($groupedCommandHandlers as $handler)
            {
                $commandHandlers[] = [
                    'provider' => \XF::stringToClass($handler->provider_class, '%s\ChatCommand\%s'),
                    'execution_order' => $handler->execution_order
                ];
            }

            $cache[$name] = $commandHandlers;
        }

        $this->app()->registry()->set('smTgCore.cmds_addOns', $cache);
        return $cache;
    }
}
