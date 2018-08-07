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

      $table->addColumn('notifications',  'bool')->setDefault(0);
      $table->addColumn('updated',        'int');
    });

    $this->makeNotificationsQueueTable($sm);
  }

  public function upgrade(array $stepParams = []) {
    $db = $this->db();
    $sm = $db->getSchemaManager();

    $sm->alterTable('tg_user', function (Alter $table) {
      // 1.0.3 beta
      if (!$table->getColumnDefinition('notifications'))
        $table->addColumn('notifications', 'bool')->setDefault(0)->after('photo_url');
    });

    if (!$sm->tableExists('tg_notifications_queue')) {
      $this->makeNotificationsQueueTable($sm);
    }
  }

  public function uninstall(array $stepParams = []) {
    $db = $this->db();
    $sm = $db->getSchemaManager();

    $db->delete('xf_connected_account_provider', "provider_id = 'telegram'");
    $sm->dropTable('tg_user');
    $sm->dropTable('tg_notifications_queue');
  }

  // Some additional functions
  private function makeNotificationsQueueTable(\XF\Db\SchemaManager $sm) {
    $sm->createTable('tg_notifications_queue', function (Create $table) {
      $table->addColumn('id',       'int')->autoIncrement();
      $table->addColumn('receiver', 'int');
      $table->addColumn('message',  'text');
      $table->addColumn('marktype', 'enum')->values(['none', 'HTML', 'MarkDown'])->setDefault('none');
      $table->addColumn('status',   'enum')->values(['planned', 'finished', 'failed'])->setDefault('planned');
    });
  }
}
