<?php
namespace Kruzya\Telegram;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

class Setup extends AbstractSetup
{
  public function install(array $stepParams = []) {
    $db = $this->db();
    $db->insert('xf_connected_account_provider', [
      'provider_id'           => 'telegram',
      'provider_class'        => 'Kruzya\\Telegram:Provider\\Telegram',
      'display_order'         => 150,
      'options'               => '[]'
    ]);

    $sm = $db->getSchemaManager();
    $sm->createTable('tg_user', function (Create $table) {
      $table->addColumn('id',             'int')->primaryKey();
      $table->addColumn('first_name',     'varchar', 64)->setDefault('');
      $table->addColumn('last_name',      'varchar', 64)->setDefault('');
      $table->addColumn('username',       'varchar', 32)->nullable();
      $table->addColumn('photo_url',      'varchar', 256);

      $table->addColumn('updated',        'int');
    });
  }

  public function upgrade(array $stepParams = []) {
    $db = $this->db();
    $sm = $db->getSchemaManager();

    if (!$this->IsAddOnInstalled('Kruzya/TelegramNotifications')) {
      $sm->alterTable('tg_user', function (Alter $table) {
        if ($table->getColumnDefinition('notifications'))
          $table->dropColumns(['notifications']);
      });

      $sm->dropTable('tg_notifications_queue');
    }
  }

  public function uninstall(array $stepParams = []) {
    $db = $this->db();
    $sm = $db->getSchemaManager();

    $db->delete('xf_connected_account_provider', "provider_id = 'telegram'");
    $sm->dropTable('tg_user');
  }

  private function IsAddOnInstalled($ID) {
    $AddOn = $this->app->em()->find('XF:AddOn', $ID);
    if (!$AddOn)
      return false;

    return $AddOn->active;
  }
}
