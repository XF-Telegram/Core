<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Entity;


use XF\Entity\Template;
use XF\Mvc\Entity\Structure;

/**
 * COLUMNS
 * @property string title
 * @property integer command_id
 *
 * RELATIONS
 * @property-read Template MasterTemplate_
 * @property-read Command Command
 *
 * GETTERS
 * @property-read Template MasterTemplate
 * @property-read string TemplateName
 */
class UserCommand extends Entity
{
    /**
     * @return string
     */
    public function getTemplateName($useObsoleteTitle = true)
    {
        $postfix = $useObsoleteTitle ? $this->getExistingValue('title') : $this->get('title');
        return '_smodders_tgcore__user_command.' . $postfix;
    }

    /**
     * @return Template
     */
    public function getMasterTemplate()
    {
        $template = $this->MasterTemplate_;
        if (!$template)
        {
            $template = $this->_em->create('XF:Template');
            $template->bulkSet([
                'style_id' => 0,
                'addon_id' => '',
                'title' => $this->_getDeferredValue(function () { return $this->getTemplateName(); }),
                'type' => 'email'
            ]);
        }

        return $template;
    }

    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->shortName .= 'UserCommand';
        $structure->table .= 'user_command';
        $structure->primaryKey = 'title';

        $structure->columns = [
            'title' => ['type' => self::STR, 'maxLength' => 50,
                'required' => 'smodders_tgcore__please_enter_valid_command_name',
                'unique' => 'smodders_tgcore__command_name_must_be_unique',
                'match' => 'alphanumeric'
            ],

            'command_id' => ['type' => self::UINT, 'default' => 0]
        ];

        $structure->relations = [
            'MasterTemplate' => [
                'entity' => 'XF:Template',
                'type' => self::TO_ONE,
                'conditions' => [
                    ['style_id', '=', 0],
                    ['type', '=', 'email'],
                    ['title', '=', '_smodders_tgcore__user_command.', '$title'],
                    ['addon_id', '=', '']
                ]
            ],

            'Command' => [
                'entity' => 'SModders\TelegramCore:Command',
                'type' => self::TO_ONE,
                'conditions' => 'command_id'
            ]
        ];

        $structure->getters = [
            'MasterTemplate'    => true,
            'TemplateName'      => false
        ];

        return $structure;
    }

    protected function _preSave()
    {
        if ($this->isChanged('title') && $this->isUpdate())
        {
            $this->MasterTemplate->title = $this->getTemplateName(false);
        }
    }
}