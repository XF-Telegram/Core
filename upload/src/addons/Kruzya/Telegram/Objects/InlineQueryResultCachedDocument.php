<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a file stored on the Telegram
 * servers. By default, this file will be sent by the
 * user with an optional caption. Alternatively, you
 * can use input_message_content to send a message
 * with the specified content instead of the file.
 *
 * NOTE: This will only work in Telegram versions
 *       released after 9 April, 2016. Older clients
 *       will ignore them.
 */
class InlineQueryResultCachedDocument extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be document
   *
   * @var string
   */
  public $type = 'document';

  /**
   * A valid file identifier for the file
   *
   * @var string
   */
  public $document_file_id;

  /**
   * Optional.
   * Short description of the result
   *
   * @var string|null
   */
  public $description = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'DocumentFileID'  => 'document_file_id',
      'Description'     => 'description',
    ]);
  }
}