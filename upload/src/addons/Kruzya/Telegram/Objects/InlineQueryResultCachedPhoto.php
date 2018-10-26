<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a photo stored on the
 * Telegram servers. By default, this photo will be
 * sent by the user with an optional caption.
 *
 * Alternatively, you can use input_message_content
 * to send a message with the specified content
 * instead of the photo.
 */
class InlineQueryResultCachedPhoto extends AbstractInlineQueryCachedResult {
  /**
   * Type of the result, must be photo
   *
   * @var string
   */
  public $type = 'photo';

  /**
   * A valid file identifier of the photo
   *
   * @var string
   */
  public $photo_file_id;

  /**
   * Optional.
   * Short description of the result
   *
   * @var string|null
   */
  public $description = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'PhotoFileID' => 'photo_file_id',
      'Description' => 'description',
    ]);
  }
}