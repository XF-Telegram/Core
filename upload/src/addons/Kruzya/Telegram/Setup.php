<?php
namespace Kruzya\Telegram;

use XF\AddOn\AbstractSetup;
use XF\Db\Schema\Create;
use XF\Db\Schema\Alter;

use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\AddOn\StepRunnerUninstallTrait;

/**
 * TODO (before release 1.1)
 *
 * -> Add table prefix xf_
 */
class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	/**
	 * Install
	 */
	public function installStep1() {
		$this->db()->insert('xf_connected_account_provider', [
			'provider_id'           => 'telegram',
			'provider_class'        => 'Kruzya\\Telegram:Provider\\Telegram',
			'display_order'         => 150,
			'options'               => '[]'
		]);
	}

	public function installStep2() {
		$this->db->getSchemaManager()->createTable('tg_user', function (Create $table) {
			$table->addColumn('id',             'int')->primaryKey();
			$table->addColumn('first_name',     'varchar', 64)->setDefault('');
			$table->addColumn('last_name',      'varchar', 64)->setDefault('');
			$table->addColumn('username',       'varchar', 32)->nullable();
			$table->addColumn('photo_url',      'varchar', 256);

			$table->addColumn('updated',        'int');
		})
	}

	/**
	 * Upgrade
	 */
	public function upgrade1003070Step1() {
		$this->db()->getSchemaManager()->alterTable('tg_user', function (Alter $table) {
			$table->addColumn('notifications', 'bool')->setDefault(0)->after('photo_url');
		});
	}

	public function upgrade1006070Step1() {
		$this->db()->getSchemaManager()->createTable('tg_notifications_queue', function (Create $table) {
			$table->addColumn('id',       'int')->autoIncrement();
			$table->addColumn('receiver', 'int');
			$table->addColumn('message',  'text');
			$table->addColumn('marktype', 'enum')->values(['none', 'HTML', 'MarkDown'])->setDefault('none');
			$table->addColumn('status',   'enum')->values(['planned', 'finished', 'failed'])->setDefault('planned');
		});
	}

	public function upgrade1007070Step1() {
		$sm = $this->db()->getSchemaManager();

		$sm->dropTable('tg_notifications_queue');
		$sm->alterTable('tg_user', function (Alter $table) {
			$table->dropColumns(['notifications']);
		});
	}

	/**
	 * Uninstall
	 */
	public function uninstallStep1() {
		$db = $this->db()->delete('xf_connected_account_provider', "provider_id = 'telegram'");
	}

	public function uninstallStep2() {
		$this->db()->getSchemaManager()->dropTable('tg_user');
	}
}
