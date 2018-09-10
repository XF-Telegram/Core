<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a Telegram user or bot.
 */
class User extends AbstractObject {
  /**
   * Unique identifier for this user or bot
   *
   * @var integer
   */
  public $id;

  /**
   * True, if this user is a bot
   *
   * @var boolean
   */
  public $is_bot;

  /**
   * User‘s or bot’s first name
   *
   * @var string
   */
  public $first_name;

  /**
   * Optional.
   * User‘s or bot’s last name
   *
   * @var string|null
   */
  public $last_name = null;

  /**
   * Optional.
   * User‘s or bot’s username
   *
   * @var string|null
   */
  public $username = null;

  /**
   * Optional.
   * IETF language tag of the user's language
   *
   * @var string|null
   */
  public $language_code = null;

  protected function getRemappings() {
    return [
      'ID'            => 'id',
      'IsBot'         => 'is_bot',
      'FirstName'     => 'first_name',
      'LastName'      => 'last_name',
      'Username'      => 'username',
      'LanguageCode'  => 'language_code',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}