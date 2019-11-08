<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Import\Importer;


use XF\Db\AbstractAdapter;
use XF\Import\Importer\AbstractImporter;
use XF\Import\StepState;

class Telegram extends AbstractImporter
{
    /**
     * @return array
     */
    public static function getListInfo()
    {
        return [
            'target'    => '[Telegram] Core 2.x',
            'source'    => '[Telegram] Core 1.0.7',
            'beta'      => true,
        ];
    }
    
    protected function getBaseConfigDefault()
    {
        return [];
    }
    
    public function renderBaseConfigOptions(array $vars)
    {
    }
    
    public function validateBaseConfig(array &$baseConfig, array &$errors)
    {
        if (!$this->validateVersion(\XF::db(), $versionError))
        {
            $errors[] = $versionError;
            return false;
        }

        return true;
    }
    
    protected function getStepConfigDefault()
    {
        return [];
    }
    
    public function renderStepConfigOptions(array $vars)
    {
        return '';
    }
    
    public function validateStepConfig(array $steps, array &$stepConfig, array &$errors)
    {
        return true;
    }
    
    public function canRetainIds()
    {
        return false;
    }
    
    public function resetDataForRetainIds()
    {
    }
    
    public function getSteps()
    {
        return [
            'telegramUsers' => [
                'title' => \XF::phrase('smodders_tgcore.accounts')
            ],

            'connectedAccounts' => [
                'title' => \XF::phrase('connected_accounts'),
                'depends' => ['telegramUsers'] // idk what i do to work this, lol
            ],
        ];
    }
    
    protected function doInitializeSource()
    {
    }
    
    public function getFinalizeJobs(array $stepsRun)
    {
        $jobsToRun = [];

        if (in_array('connectedAccounts', $stepsRun))
        {
            $jobsToRun[] = 'SModders\\TelegramCore:RebuildConnectedAccountCache';
        }
        
        return $jobsToRun;
    }
    
    // ############### STEPS FOR IMPORTING DATA FROM OLD ADD-ON ###############
    public function stepTelegramUsers(StepState $state)
    {
        $db = \XF::db();
        $versionId = $this->resolveAddOnVersion($db);
        $sourceTable = ($versionId >= 1010034 ? 'xf_' : '') . 'tg_user';
    
        $state->imported = $db->query("
            INSERT IGNORE INTO xf_smodders_tgcore_user
            SELECT id, first_name, last_name, username, updated
            FROM $sourceTable
        ")->rowsAffected();
        return $state->complete();
    }

    public function stepConnectedAccounts(StepState $state)
    {
        $state->imported = \XF::db()->query("
            UPDATE xf_user_connected_account
            SET provider = 'smodders_telegram'
            WHERE provider = 'telegram'
        ")->rowsAffected();
        
        return $state->complete();
    }
    
    /**
     * Validates an old Add-On version and prepares error message.
     *
     * @param AbstractAdapter $db
     * @param string|null $versionError
     * @return boolean
     */
    protected function validateVersion(AbstractAdapter $db, &$versionError = null)
    {
        $versionId = $this->resolveAddOnVersion($db);
        if ($versionId === null)
        {
            $versionError = \XF::phrase('smodders_tgcore.previous_installation_not_found');
            return false;
        }

        if ($versionId < 1007010)
        {
            $versionError = \XF::phrase('smodders_tgcore.previous_installation_too_old', ['required' => '1.0.7']);
            return false;
        }

        return true;
    }
    
    /**
     * Returns the add-on version by database connection.
     *
     * @param AbstractAdapter $db
     * @return int|null
     */
    protected function resolveAddOnVersion(AbstractAdapter $db)
    {
        $addOnVersion = $db->fetchOne("SELECT version_id FROM xf_addon WHERE addon_id = 'Kruzya/Telegram'");
        if (!$addOnVersion)
        {
            return null;
        }

        return intval($addOnVersion);
    }
}