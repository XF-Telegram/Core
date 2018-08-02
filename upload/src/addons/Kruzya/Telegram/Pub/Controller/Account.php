<?php
namespace Kruzya\Telegram\Pub\Controller;

use Kruzya\Telegram\Utils;

class Account extends \XF\Pub\Controller\Account {
  public function actionPreferences() {
    $view = parent::actionPreferences();
    $telegramUser = Utils::getTelegramEntityByUser(\XF::visitor());
    if (get_class($view) == 'XF\Mvc\Reply\View')
      $view->setParam('telegram', $telegramUser);

    return $view;
  }

  protected function preferencesSaveProcess(\XF\Entity\User $visitor) {
    $form = parent::preferencesSaveProcess($visitor);
    $telegramUser = Utils::getTelegramEntityByUser($visitor);

    if ($telegramUser) {
      // $result = $this->filter('telegram[notifications]', 'boolean');
      $result = $this->filter([
        'telegram'  => [
          'notifications' => 'bool'
        ]
      ]);
      $result = $result['telegram']['notifications'];

      $telegramUser->notifications = $result;
      $telegramUser->save();
    }

    return $form;
  }
}