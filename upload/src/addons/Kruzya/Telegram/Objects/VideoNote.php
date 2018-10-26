<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a video message (available
 * in Telegram apps as of v.4.0).
 */
class VideoNote extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Duration of the video in seconds as defined by
   * sender
   *
   * @var integer
   */
  public $length;

  /**
   * Optional.
   * Video thumbnail
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize|null
   */
  public $thumb = null;

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

      'Duration'    => 'length',
      'Length'      => 'length',

      'Thumb'       => 'thumb',
      'Thumbnail'   => 'thumb',

      'FileSize'    => 'file_size',
    ];
  }

  protected function getClassMaps() {
    return [
      'thumb'       => 'Kruzya\\Telegram\\Objects\\PhotoSize',
    ];
  }
}