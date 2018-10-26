<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a sticker stored on the
 * Telegram servers. By default, this sticker will be
 * sent by the user. Alternatively, you can use
 * input_message_content to send a message with the
 * specified content instead of the sticker.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultCachedSticker extends AbstractObject {
  /**
   * Type of the result, must be sticker
   *
   * @var string
   */
  public $type = 'sticker';

  /**
   * Unique identifier for this result, 1-64 bytes
   *
   * @var string
   */
  public $id;

  /**
   * A valid file identifier of the sticker
   *
   * @var string
   */
  public $sticker_file_id;

  /**
   * Optional.
   * Inline keyboard attached to the message
   *
   * @var \Kruzya\Telegram\Objects\InlineKeyboardMarkup|null
   */
  public $reply_markup = null;

  /**
   * Optional.
   * Content of the message to be sent instead of the
   * sticker
   *
   * @var \Kruzya\Telegram\Objects\AbstractInputMessageContent|null
   */
  public $input_message_content = null;

  protected function getRemappings() {
    return [
      'Type'                => 'type',
      'ID'                  => 'id',
      'StickerFileID'       => 'sticker_file_id',
      'ReplyMarkup'         => 'reply_markup',
      'InputMessageContent' => 'input_message_content',
    ];
  }

  protected function getClassMaps() {
    return [
      'reply_markup'          => 'Kruzya\\Telegram\\Objects\\InlineKeyboardMarkup',
      'input_message_content' => 'Kruzya\\Telegram\\Objects\\AbstractInputMessageContent',
    ];
  }
}