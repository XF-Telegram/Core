<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents one size of a photo or a
 * file / sticker thumbnail.
 */
class PhotoSize extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Photo width
   *
   * @var integer
   */
  public $width;

  /**
   * Photo height
   *
   * @var integer
   */
  public $height;

  /**
   * Optional.
   * File size.
   *
   * @var integer|null
   */
  public $file_size = null;

  protected function getRemappings() {
    return [
      'FileID'    =>  'file_id',
      'Width'     =>  'width',
      'Height'    =>  'height',
      'FileSize'  =>  'file_size',
    ];
  }

  protected function getClassMaps() {
    return [];
  }
}