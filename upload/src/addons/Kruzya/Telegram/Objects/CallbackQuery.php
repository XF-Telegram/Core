<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents an incoming callback query
 * from a callback button in an inline keyboard. If
 * the button that originated the query was attached
 * to a message sent by the bot, the field message
 * will be present. If the button was attached to a
 * message sent via the bot (in inline mode), the
 * field inline_message_id will be present. Exactly
 * one of the fields data or game_short_name will be
 * present.
 *
 * NOTE: After the user presses a callback button,
 *       Telegram clients will display a progress bar
 *       until you call answerCallbackQuery. It is,
 *       therefore, necessary to react by calling
 *       answerCallbackQuery even if no notification
 *       to the user is needed (e.g., without
 *       specifying any of the optional parameters).
 */
class CallbackQuery extends AbstractObject {
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
   * Message with the callback button that originated
   * the query. Note that message content and message
   * date will not be available if the message is too
   * old
   *
   * @var \Kruzya\Telegram\Objects\Message|null
   */
  public $message = null;

  /**
   * Optional.
   * Identifier of the message sent via the bot in
   * inline mode, that originated the query.
   *
   * @var string|null
   */
  public $inline_message_id = null;

  /**
   * Global identifier, uniquely corresponding to the
   * chat to which the message with the callback
   * button was sent. Useful for high scores in
   * games.
   *
   * @var string
   */
  public $chat_instance;

  /**
   * Optional.
   * Data associated with the callback button. Be
   * aware that a bad client can send arbitrary data
   * in this field.
   *
   * @var string|null
   */
  public $data = null;

  /**
   * Optional.
   * Short name of a Game to be returned, serves as
   * the unique identifier for the game
   *
   * @var string|null
   */
  public $game_short_name = null;

  protected function getRemappings() {
    return [
      'ID'              => 'id',
      'From'            => 'from',
      'Message'         => 'message',
      'InlineMessageID' => 'inline_message_id',
      'ChatInstance'    => 'chat_instance',
      'Data'            => 'data',
      'GameShortName'   => 'game_short_name',
    ];
  }

  protected function getClassMaps() {
    return [
      'from'            => 'Kruzya\\Telegram\\Objects\\User',
      'message'         => 'Kruzya\\Telegram\\Objects\\Message',
    ];
  }
}