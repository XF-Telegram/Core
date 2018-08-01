<?php
namespace Kruzya\Telegram\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

class User extends Entity {
  public static function getStructure(Structure $structure) {
    $structure->table      = 'tg_user';
    $structure->shortName  = 'Kruzya\\Telegram:User';
    $structure->primaryKey = 'id';

    $structure->relations  = [];
    $structure->getters    = [];
    $structure->columns    = [
      'id' => [
        'type'     => self::UINT,
        'required' => true
      ],
      'first_name' => [
        'type'     => self::STR,
        'default'  => ''
      ],
      'last_name'  => [
        'type'     => self::STR,
        'default'  => ''
      ],
      'username'   => [
        'type'     => self::STR,
        'default'  => ''
      ],
      'photo_url'  => [
        'type'     => self::STR,
        'default'  => ''
      ],
      'updated'    => [
        'type'     => self::UINT,
        'default'  => time(),
        'required' => true
      ]
    ];

    return $structure;
  }
}
