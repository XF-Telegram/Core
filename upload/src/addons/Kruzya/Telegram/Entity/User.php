<?php
namespace Kruzya\Telegram\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

use Kruzya\Telegram\Utils;

class User extends Entity {
  public static function getStructure(Structure $structure) {
    $structure->table      = 'xf_tg_user';
    $structure->shortName  = 'Kruzya\Telegram:User';
    $structure->primaryKey = 'id';

    $structure->relations  = [];
    $structure->getters    = [];
    $structure->columns    = [
      'id'            => [
        'type'        => self::UINT,
        'required'    => true,
      ],
      'first_name'    => [
        'type'        => self::STR,
        'default'     => '',
      ],
      'last_name'     => [
        'type'        => self::STR,
        'default'     => '',
      ],
      'username'      => [
        'type'        => self::STR,
        'default'     => '',
      ],
      'photo_url'     => [
        'type'        => self::STR,
        'default'     => '',
      ],
      'updated'       => [
        'type'        => self::UINT,
        'default'     => time(),
        'required'    => true,
      ]
    ];

    return $structure;
  }

  /**
   * Updates information about user if required.
   * NOTE: this method call save(), if information has been updated.
   */
  public function UpdateIfRequired($time = 86400) {
    $CurrentTS = time();

    if ($CurrentTS - 86400 > $this->updated) {
      $chat = $this->api()->getChat();
      if ($chat['ok']) {
        $chat = $chat['result'];

        $this->bulkSet([
          'first_name'  => $chat['first_name'],
          'last_name'   => isset($chat['last_name']) ? $chat['last_name'] : '',
          'username'    => isset($chat['username'])  ? $chat['username']  : '',

          'updated'     => $CurrentTS,
        ]);
        $this->save();
      }
    }
  }

  /**
   * Returns a API object for working with API.
   *
   * @return \Kruzya\Telegram\API
   */
  public function api() {
    return Utils::api()->setGlobalVariables([
      'chat_id'   => $this->id,
      'user_id'   => $this->id, //for user specific methods.
    ]);
  }
}
