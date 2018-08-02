<?php
namespace Kruzya\Telegram;

use XF\Mvc\Entity\Entity;
use XF\Entity\UserAlert;

class Listener {
  public static function saveAlert(Entity $entity) {
    // Before sending, we need check: this is new alert or not.
    if ($entity->view_date != 0) {
      // User viewed this alert now (or later). Just skip.
      return;
    }

    // Check alert as viewable.
    if (!$entity->canView())
      return;

    // Check existing a Telegram account.
    /** @var \Kruzya\Telegram\Entity\User TelegramUser */
    $TelegramUser = Utils::getTelegramEntityByUser($entity->Receiver);
    if (!$TelegramUser || !$TelegramUser->notifications) {
      // skip this alert.
      return;
    }

    // Set new language.
    $old_language = \XF::language();
    \XF::setLanguage(\XF::app()->language($entity->Receiver->language_id));

    // Clear text.
    $text = $entity->render();
    $text = Utils::purifyHtml($text);

    $boardUrl = \XF::app()->options()->boardUrl;
    $text = str_replace('href="/', 'href="' . $boardUrl . '/', $text);

    @file_put_contents("/var/www/html/XF/test.txt", $text);

    // Send alert.
    $TelegramUser->sendMessage($text, 'HTML');

    // Reset language.
    \XF::setLanguage($old_language);
  }
}