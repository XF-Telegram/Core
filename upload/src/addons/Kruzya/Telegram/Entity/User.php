<?php
namespace Kruzya\Telegram\Entity;

use XF\Mvc\Entity\Structure;
use XF\Mvc\Entity\Entity;

use Kruzya\Telegram\Utils;

class User extends Entity {
  public static function getStructure(Structure $structure) {
    $structure->table      = 'tg_user';
    $structure->shortName  = 'Kruzya\\Telegram:User';
    $structure->primaryKey = 'id';

    $structure->relations  = [];
    $structure->getters    = [];
    $structure->columns    = [
      'id'            => [
        'type'        => self::UINT,
        'required'    => true
      ],
      'first_name'    => [
        'type'        => self::STR,
        'default'     => ''
      ],
      'last_name'     => [
        'type'        => self::STR,
        'default'     => ''
      ],
      'username'      => [
        'type'        => self::STR,
        'default'     => ''
      ],
      'photo_url'     => [
        'type'        => self::STR,
        'default'     => ''
      ],
      'notifications' => [
        'type'        => self::BOOL,
        'default'     => false,
        'required'    => true
      ],
      'updated'       => [
        'type'        => self::UINT,
        'default'     => time(),
        'required'    => true
      ]
    ];

    return $structure;
  }

  public function sendMessage($text, $markup = '', $disable_url_preview = false) {
    $response = Utils::getApiResponse('sendMessage', [
      'chat_id'                   => $this->get('id'),
      'text'                      => $text,
      'parse_mode'                => $markup,
      'disable_web_page_preview'  => $disable_url_preview
    ]);

    if ($response['ok'])
      return $response['result']['message_id'];
    return -1;
  }

  public function deleteMessage($message_id) {
    $response = Utils::getApiResponse('deleteMessage', [
      'chat_id'     => $this->get('id'),
      'message_id'  => $message_id
    ]);

    return $response['ok'];
  }
}
