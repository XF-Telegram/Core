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
     * Inserts the default command handlers.
     * @return void
     */
    protected function installStep3()
    {
        $this->upgrade2005010Step2();
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
     * Upgrades the column type `id` to BigInt.
     * @return void
     */
    public function upgrade2005018Step1()
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
     * Drops the internal cache.
     * @return void
     */
    protected function uninstallStep4()
    {
        $this->app->registry()->delete('smTgCore.cmds_addOns');
    }

    /**
     * Creates a internal table for command handlers storing.
     */
    public function upgrade2005010Step1()
    {
        $this->createTableFromInternalArr('xf_smodders_tgcore_command');
    }

    /**
     * Inserts the default command handlers.
     */
    public function upgrade2005010Step2()
    {
        $this->db()->insertBulk('xf_smodders_tgcore_command', [
            [
                'name'              => 'start',
                'provider_class'    => 'SModders\TelegramCore:StartAuthenticate'
            ],
            [
                'name'              => 'auth',
                'provider_class'    => 'SModders\TelegramCore:Authenticate',
                'execution_order'   => 50
            ]
        ]);
    }

    public function upgrade2005010Step3()
    {
        $this->createTableFromInternalArr('xf_smodders_tgcore_user_command');
    }

    public function upgrade2005013Step1()
    {
        $this->createTableFromInternalArr('xf_smodders_tgcore_bot');
    }

    public function upgrade2005013Step2()
    {
        $app = $this->app;
        $options = $app->options();
        $em = $app->em();

        /** @var \XF\Entity\ConnectedAccountProvider $provider */
        $provider = $em->find('XF:ConnectedAccountProvider', $this->getProviderDetails('provider_id'));
        $options = $provider->options;
        if ($provider->isUsable())
        {
            /** @var \SModders\TelegramCore\Entity\Bot $bot */
            $bot = $em->create('SModders\TelegramCore:Bot');
            $bot->username = $options['name'];
            $bot->title = 'Authorization bot (@' . $bot->username . ')';
            $bot->token = $options['token'];
            $bot->listen_events = ($options['smodders_tgcore__updateMode'] ?? 'none') != 'none';
            $bot->recalculateSecretToken();
            $bot->save();

            $provider->options = [
                'bot_id' => $bot->bot_id
            ];
        }
        else
        {
            $provider->options = [
                'bot_id' => -1
            ];
        }

        $provider->save();
    }

    protected function createTableFromInternalArr($name)
    {
        $tables = $this->getTables();
        $this->createTable($name, $tables[$name]);
    }

    /**
     * @return array
     */
    protected function getTables()
    {
        $tables = [];
        $prefix = 'xf_smodders_tgcore_';

        $tables[$prefix . 'bot'] = function (Create $table)
        {
            $table->addColumn('bot_id', 'int')->primaryKey()->autoIncrement();
            $table->addColumn('title', 'varchar', 128);
            $table->addColumn('username', 'varchar', 32);
            $table->addColumn('token', 'varchar', 64);
            $table->addColumn('listen_events', 'bool')->setDefault(0);

            $table->addColumn('secret_token', 'varchar', 32);
            $table->addKey('secret_token');
        };
        $tables[$prefix . 'user'] = function (Create $table)
        {
            $table->addColumn('id',         'bigint')->primaryKey();
            $table->addColumn('first_name', 'varchar', 64)->setDefault('');
            $table->addColumn('last_name',  'varchar', 64)->setDefault('');
            $table->addColumn('username',   'varchar', 32)->nullable();

            $table->addColumn('updated_at', 'int');
        };
        $tables[$prefix . 'command'] = function (Create $table)
        {
            $table->addColumn('command_id', 'int')->unsigned()
                ->primaryKey()->autoIncrement();
            $table->addColumn('name', 'varchar', 32);
            $table->addColumn('provider_class', 'varchar', 100);
            $table->addColumn('execution_order', 'int')->setDefault(10);
        };
        $tables[$prefix . 'user_command'] = function (Create $table)
        {
            $table->addColumn('title', 'varchar', 50)->primaryKey();
            $table->addColumn('command_id', 'int')->unsigned();
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

    public function postInstall(array &$stateChanges)
    {
        $this->rebuildInternalCaches();
    }

    public function postUpgrade($previousVersion, array &$stateChanges)
    {
        $this->rebuildInternalCaches();
    }

    protected function rebuildInternalCaches()
    {
        /** @var \SModders\TelegramCore\Repository\Command $commandRepo */
        $commandRepo = $this->app->repository('SModders\TelegramCore:Command');

        $commandRepo->rebuildAddOnCommandsCache();
    }

    protected function upgradeUserIdToLong()
    {
        $this->alterTable('xf_smodders_tgcore_user', function (Alter $table)
        {
            $table->changeColumn('id', 'bigint');
        });
    }
}
