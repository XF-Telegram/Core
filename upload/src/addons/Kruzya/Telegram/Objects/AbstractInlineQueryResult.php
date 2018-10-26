<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one result of an inline
 * query.
 */
abstract class AbstractInlineQueryResult extends AbstractObject {
  /**
   * Type of the result
   *
   * @var string
   */
  public $type;

  /**
   * Unique identifier for this result, 1-64 Bytes
   *
   * @var string
   */
  public $id;

  /**
   * Title of the result
   *
   * @var string
   */
  public $title;

  /**
   * Content of the message to be sent
   *
   * @var \Kruzya\Telegram\Objects\InputMessageContent
   */
  public $input_message_content;

  /**
   * Optional.
   * Inline keyboard attached to the message
   *
   * @var \Kruzya\Telegram\Objects\AbstractInputMessageContent|null
   */
  public $reply_markup = null;

  protected function getRemappings() {
    return [
      'Type'                  => 'type',
      'ID'                    => 'id',

      'Title'                 => 'title',

      'InputMessageContent'   => 'input_message_content',
      'ReplyMarkup'           => 'reply_markup',
    ];
  }

  protected function getClassMaps() {
    return [
      'input_message_content' => 'Kruzya\\Telegram\\Objects\\AbstractInputMessageContent',
      'reply_markup'          => 'Kruzya\\Telegram\\Objects\\InlineKeyboardMarkup',
    ];
  }
}