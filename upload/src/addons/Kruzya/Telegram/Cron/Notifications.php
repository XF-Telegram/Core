<?php
namespace Kruzya\Telegram\Cron;

use Kruzya\Telegram\Utils;

class Notifications {
  public static function processNotifications() {
    $notifications = \XF::finder('Kruzya\\Telegram:Notification')
      ->with('User')
      ->where('status', '=', 'planned')
      ->limit(25)
      ->fetch();

    foreach ($notifications as $notification) {
      $result = $notification->User->sendMessage($notification->message, $notification->marktype, true);
      if ($result == -1) {
        $notification->status = 'failed';

        if (Utils::getFloodProtect()) {
          $notification->User->notifications = 0;
          $notification->User->save();
        }
      } else
        $notification->status = 'finished';

      $notification->save();
    }
  }
}