<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a file. By default, this file
 * will be sent by the user with an optional caption.
 *
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the file. Currently, only .PDF and
 * .ZIP files can be sent using this method.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultDocument extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be document
   *
   * @var string
   */
  public $type = 'document';

  /**
   * A valid URL for the file
   *
   * @var string
   */
  public $document_url;

  /**
   * Mime type of the content of the file, either
   * "application/pdf" or "application/zip"
   *
   * @var string
   */
  public $mime_type;

  /**
   * Optional.
   * Thumbnail width
   *
   * @var integer|null
   */
  public $thumb_width = null;

  /**
   * Optional.
   * Thumbnail height
   *
   * @var integer|null
   */
  public $thumb_height = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'DocumentURL'   => 'document_url',
      'MIMEType'      => 'mime_type',

      'ThumbWidth'    => 'thumb_width',
      'ThumbHeight'   => 'thumb_height',
    ]);
  }
}