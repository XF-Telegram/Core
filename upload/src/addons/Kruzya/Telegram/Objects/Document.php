<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a general file (as opposed
 * to photos, voice messages and audio files).
 */
class Document extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Optional.
   * Document thumbnail as defined by sender
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize|null
   */
  public $thumb = null;

  /**
   * Optional.
   * Original filename as defined by sender
   *
   * @var string|null
   */
  public $file_name = null;

  /**
   * Optional.
   * MIME type of the file as defined by sender
   *
   * @var string|null
   */
  public $mime_type = null;

  /**
   * Optional.
   * File size
   *
   * @var integer|null
   */
  public $file_size = null;

  protected function getRemappings() {
    return [
      'FileID'      => 'file_id',

      'Thumb'       => 'thumb',
      'Thumbnail'   => 'thumb',

      'FileName'    => 'file_name',

      'MIMEType'    => 'mime_type',
      'FileSize'    => 'file_size',
    ];
  }

  protected function getClassMaps() {
    return [
      'thumb'       => 'Kruzya\\Telegram\\Objects\\PhotoSize',
    ];
  }
}