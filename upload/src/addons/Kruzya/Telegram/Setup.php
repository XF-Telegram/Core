<?php
namespace Kruzya\Telegram;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;

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

    $db->getSchemaManager()->createTable('tg_user', function (Create $table) {
      $table->addColumn('id',             'int')->primaryKey();
      $table->addColumn('first_name',     'varchar', 64)->setDefault('');
      $table->addColumn('last_name',      'varchar', 64)->setDefault('');
      $table->addColumn('username',       'varchar', 32)->nullable();
      $table->addColumn('photo_url',      'varchar', 256);

      $table->addColumn('notifications',  'bool')->setDefault(false);
      $table->addColumn('updated',        'int');
    });
  }

  public function upgrade(array $stepParams = []) {
    $this->db()->getSchemaManager()->alterTable('tg_user', function (Alter $table) {
      // 1.0.3 beta
      if (!$table->getColumnDefinition('notifications'))
        $table->addColumn('notifications', 'bool')->setDefault(false)->after('photo_url');
    });
  }

  public function uninstall(array $stepParams = []) {
    $db = $this->db();

    $db->delete('xf_connected_account_provider', "provider_id = 'telegram'");
    $db->getSchemaManager()->dropTable('tg_user');
  }
}
