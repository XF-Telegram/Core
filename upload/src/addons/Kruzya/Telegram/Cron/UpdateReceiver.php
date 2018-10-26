<?php
namespace Kruzya\Telegram\Cron;

class UpdateReceiver {
  /**
   * @var \Kruzya\Telegram\UpdateManager
   */
  protected static $manager = null;

  public static function updateMode() {
    $manager = self::initManager();
    $manager->updateMode();
  }

  public static function processUpdates() {
    $manager = self::initManager();
    $manager->getUpdates(); 
  }

  /**
   * For internal purposes.
   */
  protected static function initManager() {
    if (self::$manager === null) {
      $app = \XF::app();
      $className = $app->extendClass('Kruzya\Telegram\UpdateManager');
      self::$manager = new $className($app);
    }

    return self::$manager;
  }
}