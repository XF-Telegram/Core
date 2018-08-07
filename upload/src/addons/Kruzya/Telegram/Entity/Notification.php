<?php
namespace Kruzya\Telegram\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

use Kruzya\Telegram\Utils;

class Notification extends Entity {
  public static function getStructure(Structure $structure) {
    $structure->table       = 'tg_notifications_queue';
    $structure->shortName   = 'Kruzya\\Telegram:Notification';
    $structure->primaryKey  = 'id';
    $structure->getters     = [];

    $structure->columns     = [
      'id'              => [
        'type'          => self::UINT,
        'autoIncrement' => true,
        'nullable'      => true,
      ],
      'receiver'        => [
        'type'          => self::UINT,
        'required'      => true,
      ],
      'message'         => [
        'type'          => self::STR,
        'required'      => true,
      ],
      'marktype'        => [
        'type'          => self::STR,
        'default'       => 'none',
        'allowedValues' => ['none', 'HTML', 'MarkDown'],
      ],
      'status'          => [
        'type'          => self::STR,
        'default'       => 'planned',
        'allowedValues' => ['planned', 'finished', 'failed'],
      ],
    ];
    $structure->relations   = [
      'User'            => [
        'entity'        => 'Kruzya\\Telegram:User',
        'type'          => self::TO_ONE,
        'conditions'    => [['id', '=', '$receiver']]
      ]
    ];

    return $structure;
  }
}