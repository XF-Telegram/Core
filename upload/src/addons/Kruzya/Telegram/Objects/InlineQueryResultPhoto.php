<?php
namespace Kruzya\Telegram\Objects;

/**
 * Represents a link to a photo. By default, this
 * photo will be sent by the user with optional
 * caption. Alternatively, you can use
 * input_message_content to send a message with the
 * specified content instead of the photo.
 */
class InlineQueryResultPhoto extends AbstractInlineQueryMediaResult {
  /**
   * Type of the result, must be photo
   *
   * @var string
   */
  public $type = 'photo';

  /**
   * A valid URL of the photo. Photo must be in jpeg
   * format. Photo size must not exceed 5MB
   *
   * @var string
   */
  public $photo_url;

  /**
   * Optional.
   * Width of the photo
   *
   * @var integer|null
   */
  public $photo_width = null;

  /**
   * Optional.
   * Height of the photo
   *
   * @var integer|null
   */
  public $photo_height = null;

  /**
   * Optional.
   * Short description of the result
   *
   * @var string|null
   */
  public $description = null;

  protected function getRemappings() {
    return array_merge(parent::getRemappings(), [
      'PhotoURL'      => 'photo_url',
      'PhotoWidth'    => 'photo_width',
      'PhotoHeight'   => 'photo_height',
      'Description'   => 'description',
    ]);
  }
}