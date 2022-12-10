<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\ChatCommand;


use SModders\TelegramCore\Entity\UserCommand;
use TelegramBot\Api\Types\Message;

class UserDefinedCommandHandler extends AbstractHandler
{
    public function run(Message $message, array $parameters = [])
    {
        $commandName = $this->dispatcher->getCurrentCommandName();

        // Try lookup command in our table.
        /** @var \SModders\TelegramCore\Entity\UserCommand $command */
        $command = $this->app->em()->find('SModders\TelegramCore:UserCommand', $commandName);
        if ($command)
        {
            $this->_handleSimpleCommand($command, $message, $parameters);
        }

        return $this->next($message, $parameters);
    }

    protected function _handleSimpleCommand(UserCommand $userCommand, Message $message, array $parameters)
    {
        $templateName = $userCommand->TemplateName;
        $templateParams = [
            'message' => $message,
            'parameters' => $parameters,
            'user_command' => $userCommand
        ];

        $messageText = $this->renderMessageTemplate($templateName, $templateParams);
        $this->telegramClient->sendMessage($message->getChat()->getId(), $messageText, 'HTML');
    }
}