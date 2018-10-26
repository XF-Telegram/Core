<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one special entity in a
 * text message. For example, hashtags, usernames,
 * URLs, etc. 
 */
class MessageEntity extends AbstractObject {
  /**
   * Type of the entity.
   * Can be:
   * -> mention (@username)
   * -> hashtag
   * -> cashtag
   * -> bot_command
   * -> url
   * -> email
   * -> phone_number
   * -> bold (bold text)
   * -> italic (italic text)
   * -> code (monowidth string)
   * -> pre (monowidth block)
   * -> text_link (for clickable text URLs)
   * -> text_mention (for users without usernames)
   *
   * @var string
   */
  public $type;

  /**
   * Offset in UTF-16 code units to the start of the
   * entity
   *
   * @var integer
   */
  public $offset;

  /**
   * Length of the entity in UTF-16 code units
   *
   * @var integer
   */
  public $length;

  /**
   * Optional.
   * For "text_link" only, url that will be opened
   * after user taps on the text
   *
   * @var string|null
   */
  public $url = null;

  /**
   * Optional. For "text_mention" only, the mentioned user
   *
   * @var \Kruzya\Telegram\Objects\User|null
   */
  public $user = null;

  protected function getRemappings() {
    return [
      'Type'    => 'type',
      'Offset'  => 'offset',
      'Length'  => 'length',
      'URL'     => 'url',
      'User'    => 'user',
    ];
  }

  protected function getClassMaps() {
    return [
      'user'    =>  'Kruzya\\Telegram\\Objects\\User',
    ];
  }
}