<?php
namespace Kruzya\Telegram;

use XF\Mvc\Entity\Entity;
use XF\Entity\UserAlert;

class Listener {
  public static function saveAlert(Entity $entity) {
    // If notifications disabled globally, ignore this event.
    if (!Utils::isNotificationsAllowed($entity->Receiver))
      return;

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
    $text = \XF::asVisitor($entity->Receiver, function () use($entity) {
      return Utils::purifyHtml($entity->render());
    });

    $boardUrl = \XF::app()->options()->boardUrl;
    $text = str_replace('href="/', 'href="' . $boardUrl . '/', $text);

    // Add alert to queue.
    $TelegramUser->addNotification($text, 'HTML');

    // Reset language.
    \XF::setLanguage($old_language);
  }
}