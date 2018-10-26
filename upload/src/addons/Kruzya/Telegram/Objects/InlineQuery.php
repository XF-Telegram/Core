<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents an incoming inline query.
 * When the user sends an empty query, your bot could
 * return some default or trending results.
 */
class InlineQuery extends AbstractObject {
  /**
   * Unique identifier for this query
   *
   * @var string
   */
  public $id;

  /**
   * Sender
   *
   * @var \Kruzya\Telegram\Objects\User
   */
  public $from;

  /**
   * Optional.
   * Sender location, only for bots that request user
   * location
   *
   * @var \Kruzya\Telegram\Objects\Location|null
   */
  public $location = null;

  /**
   * Text of the query (up to 512 characters)
   *
   * @var string
   */
  public $query;

  /**
   * Offset of the results to be returned, can be
   * controlled by the bot
   *
   * @var string
   */
  public $offset;

  protected function getRemappings() {
    return [
      'ID'        => 'id',
      'From'      => 'from',
      'Location'  => 'location',

      'Query'     => 'query',
      'Offset'    => 'offset',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'      => 'Kruzya\\Telegram\\Objects\\User',
      'location'  => 'Kruzya\\Telegram\\Objects\\Location',
    ];
  }
}