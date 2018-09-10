<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a voice note.
 */
class Voice extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Duration of the audio in seconds as defined by
   * sender
   *
   * @var integer
   */
  public $duration;

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
      'Duration'    => 'duration',
      'MIMEType'    => 'mime_type',
      'FileSize'    => 'file_size',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}