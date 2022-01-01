<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;
use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;
use XF\Util\Arr;

class Setup extends AbstractSetup
{
    use StepRunnerInstallTrait;
    use StepRunnerUpgradeTrait;
    use StepRunnerUninstallTrait;

    /**
     * Creates the tables.
     * @return void
     * @throws \XF\PrintableException
     */
    protected function installStep1()
    {
        /**
         * @var string $name
         * @var \Closure $closure
         */
        foreach ($this->getTables() as $name => $closure)
        {
            $this->createTable($name, $closure);
        }
    }

    /**
     * Creates a new Connected Account provider.
     * @return void
     * @throws \XF\PrintableException
     */
    protected function installStep2()
    {
        $provider = $this->app->em()->create('XF:ConnectedAccountProvider');
        $provider->bulkSet($this->getProviderDetails());
        $provider->save();
    }

    /**
     * Upgrades the column type `id` to BigInt.
     * @return void
     */
    public function upgrade2004071Step1()
    {
        $this->upgradeUserIdToLong();
    }

    /**
     * Deletes all connections with Telegram.
     * @return void
     */
    protected function uninstallStep1()
    {
        $this->db()->delete('xf_user_connected_account', 'provider = ?', $this->getProviderDetails('provider_id'));
    }

    /**
     * Deletes the provider.
     * @return void
     */
    protected function uninstallStep2()
    {
        $this->db()->delete('xf_connected_account_provider', 'provider_id = ?', $this->getProviderDetails('provider_id'));
    }

    /**
     * Drops the tables.
     * @return void
     * @throws \XF\PrintableException
     */
    protected function uninstallStep3()
    {
        /**
         * @var string $name
         * @var \Closure $_
         *
         * $_ unused.
         */
        foreach (array_reverse($this->getTables()) as $name => $_)
        {
            $this->dropTable($name);
        }
    }

    /**
     * @return array
     */
    protected function getTables()
    {
        $tables = [];
        $prefix = 'xf_smodders_tgcore_';

        $tables[$prefix . 'user'] = function (Create $table)
        {
            $table->addColumn('id',         'bigint')->primaryKey();
            $table->addColumn('first_name', 'varchar', 64)->setDefault('');
            $table->addColumn('last_name',  'varchar', 64)->setDefault('');
            $table->addColumn('username',   'varchar', 32)->nullable();

            $table->addColumn('updated_at', 'int');
        };

        return $tables;
    }

    /**
     * @param string|null $field
     * @return array|string|int
     */
    protected function getProviderDetails($field = null)
    {
        $details = [
            'provider_id'       => 'smodders_telegram',
            'provider_class'    => 'SModders\TelegramCore:Provider\Telegram',
            'display_order'     => 175,
            'options'           => [],
        ];

        if ($field === null)
        {
            return $details;
        }

        return $details[$field];
    }

    protected function upgradeUserIdToLong()
    {
        $this->alterTable('xf_smodders_tgcore_user', function (Alter $table)
        {
            $table->changeColumn('id', 'bigint');
        });
    }
}
