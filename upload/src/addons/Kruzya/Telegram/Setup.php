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
      $table->addColumn('id',         'int')->primaryKey();
      $table->addColumn('first_name', 'varchar', 64)->setDefault('');
      $table->addColumn('last_name',  'varchar', 64)->setDefault('');
      $table->addColumn('username',   'varchar', 32)->nullable();
      $table->addColumn('photo_url',  'varchar', 256);

      $table->addColumn('updated',    'int');
    });
  }

  public function upgrade(array $stepParams = []) {}

  public function uninstall(array $stepParams = []) {
    $db = $this->db();

    $db->delete('xf_connected_account_provider', "provider_id = 'telegram'");
    $db->getSchemaManager()->dropTable('tg_user');
  }
}
