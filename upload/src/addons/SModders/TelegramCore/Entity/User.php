<?php

/**
 * This file is a part of [Telegram] Core.
 * All rights reserved.
 *
 * Developed by SourceModders.
 */

namespace SModders\TelegramCore\Entity;


use TelegramBot\Api\InvalidArgumentException;
use XF\Mvc\Entity\Structure;

/**
 * FIELDS
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property int $updated_at
 */
class User extends Entity
{
    public static function getStructure(Structure $structure)
    {
        $structure = parent::getStructure($structure);
        $structure->table .= 'user';
        $structure->shortName .= 'User';
        $structure->primaryKey = 'id';

        $structure->columns = [
            'id'            => ['type'  => self::UINT,  'required'  => true],
            'first_name'    => ['type'  => self::STR,   'default'   => ''],
            'last_name'     => ['type'  => self::STR,   'default'   => ''],
            'username'      => ['type'  => self::STR,   'default'   => ''],
            'updated_at'    => ['type'  => self::UINT,  'default'   => \XF::$time,  'required'  => true],
        ];
        
        return $structure;
    }
    
    /**
     * Updates information about user if required.
     * NOTE: this method call save(), if information has been updated.
     *
     * @param int $time
     */
    public function updateIfRequired($time = 86400)
    {
        if (\XF::$time - $time < $this->updated_at)
        {
            return;
        }
        
        try {
            $userInformation = $this->app()
                ->get('smodders.telegram')->api()
                ->getChat($this->id)->toJson(true);
            
            $this->bulkSetIgnore($userInformation);
        }
        catch (InvalidArgumentException $e)
        {
            // looks like a user doesn't exists.
        }
        
        $this->updated_at = \XF::$time;
        $this->save();
    }
}