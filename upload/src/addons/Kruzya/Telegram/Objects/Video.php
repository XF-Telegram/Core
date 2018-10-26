<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a video file.
 */
class Video extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Video width as defined by sender
   *
   * @var integer
   */
  public $width;

  /**
   * Video height as defined by sender
   *
   * @var integer
   */
  public $height;

  /**
   * Duration of the video in seconds as defined by
   * sender
   *
   * @var integer
   */
  public $duration;

  /**
   * Optional.
   * Video thumbnail
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize|null
   */
  public $thumb = null;

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

      'Width'       => 'width',
      'Height'      => 'height',
      'Duration'    => 'duration',

      'Thumb'       => 'thumb',
      'Thumbnail'   => 'thumb',

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