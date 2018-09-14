<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a result of an inline query that was
 * chosen by the user and sent to their chat partner.
 *
 * NOTE: It is necessary to enable inline feedback
 *       via @Botfather in order to receive these
 *       objects in updates.
 */
class ChosenInlineResult extends AbstractObject {
  /**
   * The unique identifier for the result that was
   * chosen
   *
   * @var string
   */
  public $result_id;

  /**
   * The user that chose the result
   *
   * @var \Kruzya\Telegram\Objects\User
   */
  public $from;

  /**
   * Optional.
   * Sender location, only for bots that require user
   * location
   *
   * @var \Kruzya\Telegram\Objects\Location|null
   */
  public $location = null;

  /**
   * Optional.
   * Identifier of the sent inline message. Available
   * only if there is an inline keyboard attached to
   * the message. Will be also received in callback
   * queries and can be used to edit the message.
   *
   * @var string|null
   */
  public $inline_message_id = null;

  /**
   * The query that was used to obtain the result
   * 
   * @var string
   */
  public $query;

  protected function getRemappings() {
    return [
      'ResultID'        => 'result_id',
      'From'            => 'from',
      'Location'        => 'location',
      'InlineMessageID' => 'inline_message_id',
      'Query'           => 'query',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'            => 'Kruzya\\Telegram\\Objects\\User',
      'location'        => 'Kruzya\\Telegram\\Objects\\Location',
    ];
  }
}