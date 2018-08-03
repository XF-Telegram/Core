<?php
namespace Kruzya\Telegram\Service\Conversation;

use XF\Entity\ConversationMessage;
use XF\Entity\User;

use Kruzya\Telegram\Utils;

class Notifier extends XFCP_Notifier {
  protected function _sendNotifications($actionType, array $notifyUsers, ConversationMessage $message = NULL, User $sender = NULL) {
    $this->_sendTelegram($actionType, $notifyUsers, $message, $sender);
    return parent::_sendNotifications($actionType, $notifyUsers, $message, $sender);
  }

  private function _sendTelegram($actionType, array $notifyUsers, ConversationMessage $message = NULL, User $sender = NULL) {
    // If notifications disabled globally, ignore this event.
    if (!Utils::isNotificationsAllowed())
      return;

    if (!$sender && $message) {
      $sender = $message->User;
    }

    $boardName = \XF::app()->options()->boardTitle;
    $boardUrl = \XF::app()->options()->boardUrl;

    /** @var \XF\Entity\User $user */
    foreach ($notifyUsers as $receiver) {
      $TelegramUser = Utils::getTelegramEntityByUser($receiver);
      // check, enabled notifications or not.
      if (!$TelegramUser->notifications) {
        // disabled. skip this user.
        continue;
      }

      // all ok. Send notification. But format him.
      $conversationTitle  = htmlspecialchars($message->Conversation->title);
      $conversationUrl    = \XF::app()->router()->buildLink('conversations/unread', $message);
      $senderName         = htmlspecialchars($sender->username);
      $senderUrl          = \XF::app()->router()->buildLink('members', $sender);

      $message_format = [
        'conversation'  => "<a href=\"{$boardUrl}{$conversationUrl}\">{$conversationTitle}</a>",
        'sender'        => "<a href=\"{$boardUrl}{$senderUrl}\">{$senderName}</a>",
        'board'         => "<a href=\"{$boardUrl}\">{$boardName}</a>"
      ];

      $text = \XF::app()
        ->language($receiver->language_id)
        ->phrase("tg_notifications.conversations_{$actionType}", $message_format, true)
        ->render('raw');

      if ($TelegramUser->sendMessage($text, 'HTML', true) == -1) {
        // message don't delivered. why?
        // anyway, just turn off the notifications (if protection enabled) so as not to go to the ban at the Telegram for flooding.
        if (Utils::getFloodProtect()) {
          $TelegramUser->notifications = 0;
          $TelegramUser->save();
        }
      }
    }
  }
}
