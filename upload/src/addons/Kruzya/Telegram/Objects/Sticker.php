<?php
namespace Kruzya\Telegram\Objects;

/**
 * This object represents a sticker.
 */
class Sticker extends AbstractObject {
  /**
   * Unique identifier for this file
   *
   * @var string
   */
  public $file_id;

  /**
   * Sticker width
   *
   * @var integer
   */
  public $width;

  /**
   * Sticker height
   *
   * @var integer
   */
  public $height;

  /**
   * Optional.
   * Sticker thumbnail in the .webp or .jpg format
   *
   * @var \Kruzya\Telegram\Objects\PhotoSize|null
   */
  public $thumb = null;

  /**
   * Optional.
   * Emoji associated with the sticker
   *
   * @var string|null
   */
  public $emoji = null;

  /**
   * Optional.
   * Name of the sticker set to which the sticker
   * belongs
   *
   * @var string|null
   */
  public $set_name = null;

  /**
   * Optional.
   * For mask stickers, the position where the mask
   * should be placed
   *
   * @var \Kruzya\Telegram\Objects\MaskPosition|null
   */
  public $mask_position = null;

  /**
   * Optional.
   * File size
   *
   * @var integer|null
   */
  public $file_size = null;

  protected function getRemappings() {
    return [
      'FileID'        => 'file_id',
      'Width'         => 'width',
      'Height'        => 'height',

      'Thumb'         => 'thumb',
      'Thumbnail'     => 'thumb',

      'Emoji'         => 'emoji',
      'SetName'       => 'set_name',
      'MaskPosition'  => 'mask_position',
      'FileSize'      => 'file_size',
    ];
  }

  protected function getClassMaps() {
    return [
      'thumb'         => 'Kruzya\\Telegram\\Objects\\PhotoSize'.
      'mask_position' => 'Kruzya\\Telegram\\Objects\\MaskPosition',
    ];
  }
}